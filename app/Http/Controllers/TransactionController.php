<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Transaction_details;
use App\Model\Company;
use DB;

class TransactionController extends Controller
{  
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $length = 10;
        $start = $request->start?$request->start:0;
        $val = $request->searchTerm2;
        if($val!=''||$start>0){   
            $data =  DB::connection('pgsql')->select("select * from transaction where invoiceno ilike '%".$val."%' LIMIT $length offset $start");
            $count =  DB::connection('pgsql')->select("select * from transaction where invoiceno ilike '%".$val."%' ");
        }else{
            $data =  DB::connection('pgsql')->select("select * from transaction LIMIT $length");
            $count =  DB::connection('pgsql')->select("select * from transaction");
        }
        
        $count_all_record =  DB::connection('pgsql')->select("select count(*) as count from transaction");

        $data_array = array();

        foreach ($data as $key => $value) {
            $arr = array();
            $company_data = Company::where(['id'=>$value->companyid])->first();
            $arr['company'] =  $company_data->company;
            $arr['refno'] =  $value->referenceno;
            $arr['invno'] =  $value->invoiceno;
            $arr['id'] =  $value->id;
            $arr['tdate'] =  date_format(date_create($value->transactiondate),"F d Y");
            $data_array[] = $arr;
        }
        $page = sizeof($count)/$length;
        $getDecimal =  explode(".",$page);
        $page_count = round(sizeof($count)/$length);
        if(sizeof($getDecimal)==2){            
            if($getDecimal[1]<5){
                $page_count = $getDecimal[0] + 1;
            }
        }
        $datasets = array(["data"=>$data_array,"count"=>$page_count,"showing"=>"Showing ".(($start+10)-9)." to ".($start+10>$count_all_record[0]->count?$count_all_record[0]->count:$start+10)." of ".$count_all_record[0]->count, "patient"=>$data_array]);
        return response()->json($datasets);
    } 

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $p = new Transaction;
        $p->invoiceno = $request->head['invoiceno'];
        $p->companyid = $request->head['companyid']; 
        $p->created_by = $request->user;
        $p->created_dt = date('Y-m-d');
        $p->status = 1; 
        $p->transactiondate = $request->head['dot'];
        $p->save();         
        
        $getTotal = 0;
        foreach ($request->items as $val ) {
            $d = new Transaction_details;
            $d->transaction_id = $p->id;
            $d->product_id = $val['id'];
            $d->product = $val['product'];
            $d->qty = $val['qty'];
            $d->companyid = $request->head['companyid']; 
            $d->transactiondate = $request->head['dot'];
            $d->total = $val['total'];
            $d->price = $val['price'];
            $getTotal += $val['total'];
            $d->save();         
        }
        Transaction::where(['id'=>$p->id])->update([
            'referenceno'=> "TR".sprintf("%04d", $p->id),
            'total'=> $getTotal,
        ]);
        return response()->json($p->id);
    }

    public function update(Request $request)
    {
        Transaction::where(['id'=>$request->id])->update([
            'invoiceno'=> $request->data['invoiceno'],
            'companyid'=> $request->data['companyid'],
            'transactiondate'=> $request->data['dot'],
            'updated_by'=> 1,
            'updated_dt'=>  date('Y-m-d'),
        ]);
        return true;
    }

    public function getTransaction($id)
    {
        $data = Transaction_details::where(['transaction_id'=>$id])->get();
        return response()->json($data);
    }

    public function getTransactionHeader($id)
    {
        $data = Transaction::where(['id'=>$id])->first();
        return response()->json($data);
    }

    public function report(Request $request)
    {
        /* $data =  DB::connection('pgsql')->select("select to_char(tt.transactiondate,'Mon') as mon,extract(year from tt.transactiondate) as yyyy,tt.companyid,count(tt.companyid) as cnt
        from transaction tt group by 1,2,tt.companyid;"); */
        $sd = date_format(date_create($request->items['from']),'d F Y');
        $td = date_format(date_create($request->items['to']),'d F Y');
        $data =  DB::connection('pgsql')->select("select  tt.companyid,count(tt.companyid) as cnt
        from transaction tt
         where tt.transactiondate between '$sd' and '$td' group by tt.companyid;");
       
        $start    = new \DateTime($request->items['from']);
        $start->modify('first day of this month');
        $end      = new \DateTime($request->items['to']);
        $end->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        //echo iterator_count($period);

        $data_array = array();
        $cat_array = array();
        foreach ($data as $key => $value) {
            $arr = array();
            $arr_data = array();
            $company_data = Company::where(['id'=>$value->companyid])->first(); 
            /* $tdata = DB::connection('pgsql')->select("SELECT *
            FROM transaction_details td where transactiondate between '01 Feb 2023' and '28 Mar 2023' and companyid = $value->companyid"); */
            

            foreach ($period as $dt) {
                //echo $dt->format("Y-m") . "<br>\n";
                $cat_array[] = $dt->format("M");
                $last_day = date('t F Y', strtotime($dt->format("Y-m-d")));
                $start_day = $dt->format("d F Y");
                $tdata = DB::connection('pgsql')->select("select  to_char(tt.transactiondate,'Mon') as mon,extract(year from tt.transactiondate) as yyyy,tt.companyid,count(tt.companyid) as cnt,sum(tt.total) as total
                from transaction_details tt
                where tt.transactiondate between '$start_day' and '$last_day' and companyid = $value->companyid
                group by 1,2,tt.companyid;");
                $totals = 0;
                foreach ($tdata as $key2 => $value2) {
                    $totals += $value2->total;
                }
                $arr_data[] = $totals;
            }


            $arr['name'] =  $company_data->company;
            $arr['data'] =  $arr_data ;
            $data_array[] = $arr;
        }
        $datasets = array(["series"=>$data_array,'cat'=>array_unique($cat_array)]);
        return response()->json( $datasets);
    }
    
    public function DailyReport(Request $request){
        $date = date_format(date_create($request->items['date']),'d M Y');
        $query = DB::connection('pgsql')->select("select * from transaction where transactiondate = '$date'");
        $data = array();
        $grandTotal = 0;
        foreach ($query as $key => $value ) {
            $sales = Transaction_details::where('transaction_id',$value->id)->get();
            $Company = Company::where('id',$value->companyid)->first();
            $total_sales = 0;
            foreach ($sales as $key => $svalue) {
                $total_sales += $svalue->total;
            }
            $arr = array();
            $arr['company'] = $Company->company;
            $arr['inv'] = $value->invoiceno;
            $arr['sales'] = $total_sales;
            $grandTotal +=$total_sales;  
            $data[] = $arr;
        }
        $datasets = array(["data"=>$data,"count"=>$grandTotal,'query'=>$query,'q2'=>"select * from transaction where transactiondate = '$date'"]);
        return response()->json($datasets);
    }
    
    public function yearly_report(Request $request)
    {
        $sd = date_format(date_create($request->items['from']),'Y');
        $td = date_format(date_create($request->items['to']),'Y');
        $data =  DB::connection('pgsql')->select("select  tt.companyid,count(tt.companyid) as cnt
        from transaction tt
         where date_part('year',tt.transactiondate) between '$sd' and '$td' group by tt.companyid;");
       
        /* $start    = new \DateTime($request->items['from']);
        $start->modify('first day of this month');
        $end      = new \DateTime($request->items['to']);
        $end->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end); */
        //echo iterator_count($period);
        
        $date1 = new \DateTime($request->items['from']);
        $date2 = new \DateTime($request->items['to']);
        $period = $date1->diff($date2);

        $data_array = array();
        $cat_array = array();
        foreach ($data as $key => $value) {
            $arr = array();
            $arr_data = array();
            $company_data = Company::where(['id'=>$value->companyid])->first();             

            for ($i=0; $i <=$period->y; $i++) { 
                $year = $sd+$i;
                $cat_array[] = $year;
                $tdata = DB::connection('pgsql')->select("select extract(year from tt.transactiondate) as yyyy,tt.companyid,count(tt.companyid) as cnt,sum(tt.total) as total
                from transaction_details tt
                where date_part('year',tt.transactiondate) between '$year' and '$year' and companyid = $value->companyid
                group by 1,tt.companyid;");
                $totals = 0;
                foreach ($tdata as $key2 => $value2) {
                    $totals += $value2->total;
                }
                $arr_data[] = $totals;
            }


            $arr['name'] =  $company_data->company;
            $arr['data'] =  $arr_data ;
            $data_array[] = $arr;
        }
        $datasets = array(["series"=>$data_array,'cat'=>array_unique($cat_array)]);
        return response()->json( $datasets);
    }
}
