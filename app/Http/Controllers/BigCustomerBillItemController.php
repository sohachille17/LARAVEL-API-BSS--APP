<?php

namespace App\Http\Controllers;

use App\Model\BigCustomerBillItem;
use App\Model\Site;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;




class BigCustomerBillItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BigCustomerBillItem::all();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

    

        return response()->json([
			"message"=>"cannot be used on its own",
        ],200);



    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $bill = BigCustomerBillItem::find($id);

        return response()->json(
            $bill
        ,200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
           // 1 get the product first 
           $billItem = BigCustomerBillItem::find($id);

           $billItem->update($request->all());
   
           return response()->json(
            $billItem
        ,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        return Response([
            "message"=>"at the moment this action is not autorizeds"
        ],403);
    }
}
