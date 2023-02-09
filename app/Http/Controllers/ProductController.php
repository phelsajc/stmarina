<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Products;
use DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $length = 10;
        $start = $request->start?$request->start:0;
        $val = $request->searchTerm2;
        if($val!=''||$start>0){   
            $data =  DB::connection('pgsql')->select("select * from products where product ilike '%".$val."%' LIMIT $length offset $start");
            $count =  DB::connection('pgsql')->select("select * from products where product ilike '%".$val."%' ");
        }else{
            $data =  DB::connection('pgsql')->select("select * from products LIMIT $length");
            $count =  DB::connection('pgsql')->select("select * from products");
        }
        
        $count_all_record =  DB::connection('pgsql')->select("select count(*) as count from products");

        $data_array = array();

        foreach ($data as $key => $value) {
            $arr = array();
            $arr['name'] =  $value->product;
            $arr['desc'] =  $value->description;
            $arr['qty'] =  $value->quantity;
            $arr['uom'] =  $value->uom;
            $arr['dop'] =  $value->dop;  
            $arr['price'] =  $value->price;  
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
        $p = new Products;
        $p->product = $request->name;
        $p->description = $request->desc;
        $p->quantity = $request->qty;
        $p->uom = $request->uom;
        $p->dop = $request->dop;
        $p->code = $request->code;
        $p->price = $request->price;
        $p->created_dt = date("Y-m-d H:i");
        $p->created_by = $request->qty;   
        $p->save();
        return true;
    }

    public function edit($id)
    {
        $data = Products::where(['id'=>$id])->first();
        return response()->json($data);
    }
    
    public function update(Request $request)
    {
        Products::where(['id'=>$request->id])->update([
            'product'=> $request->historyPe,
            'description'=> $request->diagnosis,
            'quantity'=> $request->pe,
            'uom'=>  $request->name,
            'dop'=> $request->historyPe,
            'code'=> $request->diagnosis,
            'updated_by'=> $request->pe,
            'updated_dt'=>  $request->name,
            'price'=>  $request->price,
        ]);
        return true;
    }

    public function Delete($id)
    {
        Products::where('id',$id)->delete();
        return true;
    }

    public function searchProduct(Request $request){
        $query = DB::connection('pgsql')->select("select * from products where product ILIKE '%$request->val%' or description ILIKE '%$request->val%'");
        $data = array();
        foreach ($query as $key => $value ) {
            $arr = array();
            $arr['id'] = $value->id;
            $arr['product'] = $value->product;
            $arr['description'] = $value->description;
            $arr['price'] =  $value->price;
            $arr['code'] = $value->code;
            $arr['qty'] = $value->quantity;
            $data[] = $arr;
        }
        return response()->json($data);
    }
}
