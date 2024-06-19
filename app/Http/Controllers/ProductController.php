<?php

namespace App\Http\Controllers;
use App\Model\Category;
use App\Model\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {

        $rules = array(
            'category_name'=>'required|string',
            'capacity'=>'required',
            'amount'=>'required',
            'code'=>'required',
            'description'=>'required',
            // "registeredBy"=>'required'
        );


        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $validator->errors();

        }else{

            $data = Service::create([
                "category_name" => $request->category_name,
                "name"=>$request->capacity,
                'registeredBy' => $request->registratorName,
                "amount"=>$request->amount,
                "code"=>$request->code,
                "description"=>$request->description,
                "type"=>"product",
            ]);
            
            if($data){
                return response()->json([
                    "message"=>"Product Created Successfully",
                    "data"=>$data
            ],200);
            }else{
                return response()->json([
                    "message"=>"An Error was encoutered when registering this product",
                    "data"=>null
                ],404);
            }





        }
        



       

    }
    
    public function getAllProduct(){

        $product = Service::selectRaw('services.*,name as capacity')->where('type','=','product')->get();

        if($product){
            return response()->json($product);
        }
        

    }

    public function updateProduct(Request $request, $id){

        if(Service::where('id', $id)->exists()){

            $product = Service::find($id);
            //$customer->username = is_null($request->username) ? $customer->username : $request->username;
            $product->name = is_null($request->capacity) ? $product->capacity : $request->capacity;
            $product->amount = is_null($request->amount) ? $product->amount : $request->amount;
            $product->code = is_null($request->code) ? $product->code : $request->code;
            $product->description = is_null($request->description) ? $product->description : $request->description;
            $product->category_name = is_null($request->category_name) ? $product->category_name : $request->category_name;

            $product->save();

            return response()->json([
                "Success"=> true,
                "Message"=> "Products updated successfully",
                "date" => $product

            ]);
        }

    }

    public function deleteService($id){

        $products = Service::destroy($id);

        return Response([
                "result"=>$products,
                "message"=>$products?"success":"could not delete"
        ],200);

        


    }

    public function getProductById($id){

        //instance of product
        $product = DB::table("oos_bloo.services")->select("services.*","name as capacity")->where("id", "=",$id)->where("id", "=",$id)->get()->first();

        if(!empty($product)){
            return response()->json($product);
        }else{
            return response()->json([
                "message"=>"Sorry but record with id $id does not exist in bss database",
                "data"=> null
            ]);
        }
    }
    
    public function getProductCount(){

    }

}
