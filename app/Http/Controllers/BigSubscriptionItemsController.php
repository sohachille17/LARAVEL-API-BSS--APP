<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\BigCustomerSubscriptionItems;


class BigSubscriptionItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           //get all
           return BigCustomerSubscriptionItems::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		// 1 verify and validate incomming data
        $sub_item = $request->validate(
            [
                'siteName'=>'required|string',
                'siteId'=>'required|string',
				'population'=>'required',
                'server_id'=>'required',
				'type'=>'string',
            ]);
		
         // 2 create Site from data
         $new_sub_item = BigCustomerSubscriptionItems::create($sub_item);


        $response = [
            'subscription_items'=>$new_sub_item,
        ];

        // 4 send back the response information
        return Response($response,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get one
        $sub = BigCustomerSubscriptionItems::find($id);

        return [
            "Site"=>$sub,
            "message"=>$sub?"":"no Site found",
            "status"=>"success"
        ];
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
        $sub =  BigCustomerSubscriptionItems::find($id);
		if(!$sub){
			return [
				"error"=>"no site with id :".$id." found!"
			];
		}

        // 2 update the product
        $sub->update($request->all());

        return $sub;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		return "pending unimplemented";
        //delete
        $sub =  BigCustomerSubscriptionItems::where('id',$id)->update(['status'=>'blocked']);
        return Response(json_encode($sub),200);
    }

    public function count(){
		$sub = BigCustomerSubscriptionItems::count();
        return $sub?$sub:0;
    }

    public function getSitesBySubscriptionId(Request $request, $id){
		$sub = BigCustomerSubscriptionItems::where('subscriptionId','=',$id)->get();
		return $subs;

    }
}
