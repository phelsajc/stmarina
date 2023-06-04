<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Collections;
use App\Model\Company;
use DB;

class CollectiblesController extends Controller
{  
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $length = 10;
        $start = $request->start?$request->start:0;
        $val = $request->searchTerm2;
        $status = $request->status;
        $date = date_format(date_create($request->items['date']),'Y-m-d');
        /* if($val!=''||$start>0){   
            $data =  DB::connection('mysql')->select("select cb.status,cl.*,t.* from collectibles cb left join collections cl on
            cb.collection_id = cl.id left join transaction t on cb.transaction_id = t.id
            where date(t.transactiondate) = date('$date') and cb.status = '$status' ");
            $count =  DB::connection('mysql')->select("select * from collections where si_dr_no like '%".$val."%' ");
        }else{
            $data =  DB::connection('mysql')->select("select * from collections LIMIT $length");
            $count =  DB::connection('mysql')->select("select * from collections");
        } */
        
        $data =  DB::connection('mysql')->select("select cb.status,cl.*,t.* from collectibles cb left join collections cl on
        cb.collection_id = cl.id left join transaction t on cb.transaction_id = t.id
        where date(t.transactiondate) = date('$date') and cb.status = '$status' ");
        
        $count_all_record =  DB::connection('mysql')->select("select count(*) as count from collections");

        $data_array = array();

        foreach ($data as $key => $value) {
            $arr = array();
            $companydata = Company::where(['id'=>$value->companyid])->first();
            $arr['id'] =  $value->id;
            $arr['type'] =  $value->type;
            $arr['check_date'] =  $value->check_date;
            $arr['company'] =  $companydata->company;
            $arr['si_dr_no'] =  $value->si_dr_no;
            $arr['amount'] =  $value->amount;
            $arr['details'] =  $value->details;
            $arr['with_ewt_deductions'] =  $value->with_ewt_deductions;
            $arr['date_deposited'] =  $value->date_deposited;
            $arr['crno'] =  $value->crno;
            $arr['dsno'] =  $value->dsno;
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
        $p = new Collections;
        $p->type = $request->type;
        $p->check_date = date_create($request->chequeDate);
        $p->companyid = $request->company;
        $p->si_dr_no = $request->sidr;
        $p->amount = $request->amount;
        $p->details = $request->details;
        $p->with_ewt_deductions = $request->ewt;
        $p->date_deposited = date_create($request->dateDeposited);
        $p->crno = $request->crno;
        $p->dsno = $request->dsno;
        $p->created_by = $request->userid;
        $p->created_dt = date("Y-m-d");
        $p->save();         
        return true;
    }

    public function edit($id)
    {
        $data = Collections::where(['id'=>$id])->first();
        return response()->json($data);
    }
    
    public function update(Request $request)
    {
        Collections::where(['id'=>$request->id])->update([
            'type' => $request->data['type'],
            'check_date' => $request->data['chequeDate'],
            'companyid' => $request->data['company'],
            'si_dr_no' => $request->data['sidr'],
            'amount' => $request->data['amount'],
            'details' => $request->data['details'],
            'with_ewt_deductions' => $request->data['ewt'],
            'date_deposited' => date_create($request->data['dateDeposited']),
            'crno' => $request->data['crno'],
            'dsno' => $request->data['dsno'],
            'updated_by' => $request->data['userid'],
            'updated_dt' => date('Y-m-d'),
        ]);
        return true;
    }

    public function Delete($id)
    {
        Collections::where('id',$id)->delete();
        return true;
    }

    public function reports(Request $request)
    {
        date_default_timezone_set('Asia/Manila');        
        $status = $request->items['type'];
        $fdate = date_format(date_create($request->items['fdate']),'Y-m-d');  
        $tdate = date_format(date_create($request->items['tdate']),'Y-m-d');  

        $data =  DB::connection('mysql')->select("select cb.status,cb.companyid,cb.collection_id,t.total,t.terms,t.transactiondate,t.created_dt,t.invoiceno from collectibles cb left join transaction t on cb.transaction_id = t.id
        where date(t.transactiondate) between date('$fdate') and date('$tdate') and cb.status = '$status' ");

        $data_array = array();
        foreach ($data as $key => $value) {
            $arr = array();
            $companydata = Company::where(['id'=>$value->companyid])->first();
            $collection = Collections::where(['id'=>$value->collection_id])->first();
            $arr['status'] =  $value->status;
            $arr['check_date'] =  $collection?$collection->check_date: null;
            $arr['company'] =  $companydata->company;
            $arr['type'] =  $collection?$collection->type: null;
            $arr['si_dr_no'] =  $collection?$collection->si_dr_no: null;
            $arr['amount'] =  $collection?$collection->amount: 0;
            $arr['total'] =  $value->total?number_format((float)$value->total, 2, '.', ''):0;
            $arr['terms'] =  $value->terms;
            $arr['dot'] =  date_format(date_create($value->transactiondate),'F d, Y');
            $arr['paid_dt'] =  $collection?date_format(date_create($collection->created_dt),'F d, Y'):'';
            $arr['date_deposited'] =  $collection?date_format(date_create($collection->date_deposited),'F d, Y'):null;
            $arr['date_confirmed'] =  $collection?date_format(date_create($collection->date_confirmed),'F d, Y'):null;
            $arr['with_ewt_deductions'] =  $collection?$collection->with_ewt_deductions: null;
            $arr['crno'] =  $collection?$collection->crno: null;
            $arr['invoiceno'] =  $value->invoiceno;
            $arr['dsno'] =  $collection?$collection->dsno: null;
            $data_array[] = $arr;
        }
        return response()->json($data_array);
    }
}
