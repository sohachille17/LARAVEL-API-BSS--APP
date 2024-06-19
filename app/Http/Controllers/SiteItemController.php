<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\SiteItem;
use Illuminate\Support\Facades\Validator;


class SiteItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           //get all
           return SiteItem::all();
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
        $rules = 
            [
                'name'=>'required|string',
                'price'=>'required|string',
				'registeredBy'=>'required',
                'registratorName'=>'required',
				'type'=>'string',
                'productId'=>'required|string',
				'quantity'=>'required',
				'siteId'=>'required',
				'total'=>'required'

            ];
		

		
		$validation = Validator::make($request->all(), $rules);
        if($validation->fails()){
            return  Response($validation->errors(),403);
        }
		
		
		$siteData = $request->validate(
            [
                'name'=>'required|string',
                'price'=>'required|string',
				'registeredBy'=>'required',
                'registratorName'=>'required',
				'type'=>'string',
                'productId'=>'required|string',
				'quantity'=>'required',
				'siteId'=>'required',
				'total'=>'required'

            ]);

		// 2 create Site from data
         $newData = SiteItem::create($siteData);


        $response = [
            'data'=>$newData,
        ];

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
        $Site = SiteItem::find($id);

        return [
            "Site"=>$Site,
            "message"=>$Site?"":"no Site found",
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
        //update
        // 1 get the product first
        $Site =  SiteItem::find($id);

        // 2 update the product
        $Site->update($request->all());

        return $Site;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete
        $Site =  Site::where('id',$id)->update(['status'=>'blocked']);
        return Response(json_encode($Site),200);
    }

    public function count(){
		$numSites = SiteItem::count();
        return $numSites?$numSites:0;
    }

    public function getSiteItemsBySiteId($id){
		$numSites = SiteItem::where('siteId','=',$id)->get();
        return $numSites;
    }
}
