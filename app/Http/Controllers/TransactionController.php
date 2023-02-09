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

    public function getTransaction($id)
    {
        $data = Transaction_details::where(['transaction_id'=>$id])->get();
        return response()->json($data);
    }
   
}
