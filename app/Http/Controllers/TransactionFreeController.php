<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Transaction;
use App\Model\Transaction_details;
use App\Model\TransactionFree;
use App\Model\Transaction_detailsFree;
use App\Model\Company;
use App\Model\Collectibles;
use DB;

class TransactionFreeController extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $length = 10;
        $start = $request->start ? $request->start : 0;
        $val = $request->searchTerm2;
        if ($val != '' || $start > 0) {
            $data =  DB::connection('mysql')->select("select * from transaction_free where invoiceno like '%" . $val . "%' LIMIT $length offset $start");
            $count =  DB::connection('mysql')->select("select * from transaction_free where invoiceno like '%" . $val . "%' ");
        } else {
            $data =  DB::connection('mysql')->select("select * from transaction_free LIMIT $length");
            $count =  DB::connection('mysql')->select("select * from transaction_free");
        }

        $count_all_record =  DB::connection('mysql')->select("select count(*) as count from transaction_free");

        $data_array = array();

        foreach ($data as $key => $value) {
            $arr = array();
            $Transaction_data = Transaction::where(['invoiceno' => $value->invoiceno])->first();
            $company_data = Company::where(['id' => $Transaction_data->companyid])->first();
            
            $arr['company'] =  $company_data->company;
            $arr['refno'] =  $Transaction_data->referenceno;
            $arr['invno'] =  $value->invoiceno;
            $arr['id'] =  $value->id;
            $arr['total'] =  $Transaction_data->total;
            $arr['tdate'] =  date_format(date_create($Transaction_data->transactiondate), "F d Y");
            $data_array[] = $arr;
        }
        $page = sizeof($count) / $length;
        $getDecimal =  explode(".", $page);
        $page_count = round(sizeof($count) / $length);
        if (sizeof($getDecimal) == 2) {
            if ($getDecimal[1] < 5) {
                $page_count = $getDecimal[0] + 1;
            }
        }
        $datasets = array(["data" => $data_array, "count" => $page_count, "showing" => "Showing " . (($start + 10) - 9) . " to " . ($start + 10 > $count_all_record[0]->count ? $count_all_record[0]->count : $start + 10) . " of " . $count_all_record[0]->count, "patient" => $data_array]);
        return response()->json($datasets);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $p = new TransactionFree;
        $p->invoiceno = $request->head['invoiceno'];
        //$p->terms = $request->head['terms'];
        //$p->companyid = $request->head['companyid'];
        $p->created_by = $request->user;
        $p->created_dt = date('Y-m-d');
        $p->status = 1;
        //$p->transactiondate = date_create($request->head['dot']);
        $p->save();

        $getTotal = 0;
        foreach ($request->items as $val) {
            $d = new Transaction_detailsFree;
            $d->transaction_id = $p->id;
            $d->product_id = $val['id'];
            $d->product = $val['product'];
            $d->qty = $val['qty'];
            //$d->companyid = $request->head['companyid'];
            //$d->transactiondate = date_create($request->head['dot']);
            //$d->total = $val['total'];
            //$d->price = $val['price'];
            //$getTotal += $val['total'];
            $d->save();
        }
        /* TransactionFree::where(['id' => $p->id])->update([
            //'referenceno' => "TR" . sprintf("%04d", $p->id),
            //'total' => $getTotal,
        ]); */
        return response()->json($p->id);
    }

    public function update(Request $request)
    {
        TransactionFree::where(['id' => $request->id])->update([
            'invoiceno' => $request->data['invoiceno'],
            'companyid' => $request->data['companyid'],
            'transactiondate' => $request->data['dot'],
            'updated_by' => 1,
            'updated_dt' =>  date('Y-m-d'),
        ]);
        return true;
    }

    public function getTransaction($id)
    {
        $data = Transaction_detailsFree::where(['transaction_id' => $id])->get();
        return response()->json($data);
    }

    public function getTransactionHeader($id)
    {
        $data = TransactionFree::where(['id' => $id])->first();
        return response()->json($data);
    }

    public function report(Request $request)
    {
        $sd = date_format(date_create($request->items['from']), 'Y-m-d');
        $td = date_format(date_create($request->items['to']), 'Y-m-d');
        $data =  DB::connection('mysql')->select("select  tt.companyid,count(tt.companyid) as cnt
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
            $company_data = Company::where(['id' => $value->companyid])->first();

            foreach ($period as $dt) {
                $cat_array[] = $dt->format("M");
                $start_day = $dt->format("Y-m-d");
                $last_day = date('Y-m-t', strtotime($start_day));
                $q = "select  MONTH(tt.transactiondate) as mon,YEAR(tt.transactiondate) as yyyy,tt.companyid,count(tt.companyid) as cnt,sum(tt.total) as total from transaction_details tt where (date(tt.transactiondate) between date('$start_day') and date('$last_day')) and companyid = $value->companyid group by 1,2,tt.companyid;";
                $tdata = DB::connection('mysql')->select($q);
                $totals = 0;
                foreach ($tdata as $key2 => $value2) {
                    $totals += $value2->total;
                }
                $arr_data[] = $totals . ' ' . $start_day . ' ' . $last_day;
            }
            $arr['name'] =  $company_data->company;
            $arr['data'] =  $arr_data;
            $data_array[] = $arr;
        }
        $datasets = array(["series" => $data_array, 'cat' => array_unique($cat_array)]);
        return response()->json($datasets);
    }

    public function DailyReport(Request $request)
    {
        $date = date_format(date_create($request->items['date']), 'Y-m-d');
        $query = DB::connection('mysql')->select("select * from transaction where date(transactiondate) = date('$date')");
        $data = array();
        $grandTotal = 0;
        foreach ($query as $key => $value) {
            $sales = Transaction_details::where('transaction_id', $value->id)->get();
            $Company = Company::where('id', $value->companyid)->first();
            $total_sales = 0;
            $get_qty = 0;
            $get_price = 0;
            foreach ($sales as $key => $svalue) {
                $get_qty = $svalue->qty;
                $get_price = $svalue->price;
                $total_sales += $svalue->total;
            }
            $arr = array();
            $arr['company'] = $Company->company;
            $arr['inv'] = $value->invoiceno;
            $arr['sales'] = $total_sales;
            $arr['qty'] = $get_qty; //$sales->qty;
            $arr['price'] = $get_price; //$sales->price;
            $grandTotal += $total_sales;
            $data[] = $arr;
            $get_qty = 0;
            $get_price = 0;
        }
        $datasets = array(["data" => $data, "count" => $grandTotal, 'query' => $query, 'q2' => "select * from transaction where transactiondate = '$date'"]);
        return response()->json($datasets);
    }

    public function yearly_report(Request $request)
    {
        $sd = date_format(date_create($request->items['from']), 'Y');
        $td = date_format(date_create($request->items['to']), 'Y');
        $data =  DB::connection('mysql')->select("select  tt.companyid,count(tt.companyid) as cnt
        from transaction tt
         where YEAR(tt.transactiondate) between '$sd' and '$td' group by tt.companyid;");

        $date1 = new \DateTime($request->items['from']);
        $date2 = new \DateTime($request->items['to']);
        $period = $date1->diff($date2);

        $data_array = array();
        $cat_array = array();
        foreach ($data as $key => $value) {
            $arr = array();
            $arr_data = array();
            $company_data = Company::where(['id' => $value->companyid])->first();

            for ($i = 0; $i <= $period->y; $i++) {
                $year = $sd + $i;
                $cat_array[] = $year;
                $tdata = DB::connection('mysql')->select("select YEAR(tt.transactiondate) as yyyy,tt.companyid,count(tt.companyid) as cnt,sum(tt.total) as total
                from transaction_details tt
                where YEAR(tt.transactiondate) between '$year' and '$year' and companyid = $value->companyid
                group by 1,tt.companyid;");
                $totals = 0;
                foreach ($tdata as $key2 => $value2) {
                    $totals += $value2->total;
                }
                $arr_data[] = $totals;
            }

            $arr['name'] =  $company_data->company;
            $arr['data'] =  $arr_data;
            $data_array[] = $arr;
        }
        $datasets = array(["series" => $data_array, 'cat' => array_unique($cat_array)]);
        return response()->json($datasets);
    }
}
