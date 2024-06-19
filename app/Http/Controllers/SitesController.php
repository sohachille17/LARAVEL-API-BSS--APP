<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Site;


class SitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           //get all
           return Site::all();
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
        $site = $request->validate(
            [
                'name'=>'required|string',
                'location'=>'required|string',
				'registeredBy'=>'required',
                'registratorName'=>'required',
				'description'=>'string',
                'customerId'=>'required'

            ]);
		
         // 2 create Site from data
         $site = Site::create($site);


        $response = [
            'Site'=>$site,
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
        $Site = Site::find($id);

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
        // 1 get the product first
        $Site =  Site::find($id);
		if(!$Site){
			return [
				"error"=>"no site with id :".$id." found!"
			];
		}

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
		return "pending unimplemented";
        //delete
        $Site =  Site::where('id',$id)->update(['status'=>'blocked']);
        return Response(json_encode($Site),200);
    }

    public function count(){
		$numSites = Site::count();
        return $numSites?$numSites:0;
    }

    public function getSitesByCustomersId(Request $request, $id){
		$sites = Site::where('customerId','=',$id)->get();
		return $sites;

    }
}
