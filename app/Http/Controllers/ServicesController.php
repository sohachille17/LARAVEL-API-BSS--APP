<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Service;
use Illuminate\Support\Facades\DB;



class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = Service::selectRaw('name as serviceName,service_capacity as serviceCapacity,services.*')->where('type','=','service')->get();
        if(!empty($service)){
            return response()->json(
                $service
            );
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validations_rules = array(
            'serviceName'=> 'required',
            'serviceCapacity' => 'required',
            'amount' => 'required',
            'code' => 'required',
            'description' => 'required',
            // 'registeredBy'=>'required'
        );

        $validator = Validator::make($request->all(), $validations_rules);

        $does_service_exist = Service::select()->where('name','=',$request->serviceName)->get();

        if(count($does_service_exist) > 0){
            return response()->json([
                "success" => false,
                "error" => "un produit similaire existe deja ",
                ],403);
        }

        if($validator->fails()){
            return $validator->errors();
        }else{
            $service = Service::create([
                //calling each request as one
                'name' => $request->serviceName,
                'service_capacity' => $request->serviceCapacity,
                'amount' => $request->amount,
                'code' => $request->code,
                'description' => $request->description,
                'registeredBy' => $request->registeredBy,
                "type"=>"service"

            ]);


            if($service){
                //returning a res
                return response()->json([
                    "success" => true,
                    "message" => $service,

                    ]);
            }else{
                return response()->json([
                    "success" => false,
                    "message" => "error",

                    ],500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = DB::table("oos_bloo.services")->select("name as serviceName","service_capacity as serviceCapacity","services.*")->where("id", "=",$id)->get()->first();
        if(!empty($service)){
            return response()->json(
               $service
            );
        }
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
        $service =  Service::find($id);

        // 2 update the product
        $name = $request->serviceName?$request->serviceName:null;
        $service->update([$request->all(),'name'=>$name]);

        return $service;
    }

        /**
     * get all resource irrespective of the type from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTypes()
    {
        $service = Service::all();
        if(!empty($service)){
            return response()->json(
                $service
            );
        }
    }

    public function countServices()
    {
        $service = Service::where('type','=','service')->count();

            return response()->json($service);

    }

    public function countProducts()
    {
        $service = Service::where('type','=','product')->count();
		return response()->json($service);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ['message'=>"delete operations are not currently authorized"];

        $products = Service::destroy($id);

        return Response([
                "result"=>$products,
                "message"=>$products?"success":"could not delete"
        ],200);
    }


}
