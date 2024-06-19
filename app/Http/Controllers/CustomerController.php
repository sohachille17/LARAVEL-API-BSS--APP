<?php

namespace App\Http\Controllers;

use App\Model\Customers;
use App\Model\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Payment;
use App\Model\Bills;



    /**
    @Description Updating customer data
    @author SOH TAGNE ACHILLE
    @Company Bloosat */

class CustomerController extends Controller
{
    //Get all Customers;
    public function getAll(Request $request){
    // the limit is the page number times the number of information per page

    $customers = Customers::all();
   	return response()->json($customers);

    //$limit = $request->limit ? $request->limit*20 : 1000;
    //$customers = Customers::paginate($limit);
    
    return response()->json($customers);


    }


    public function create(Request $request){

        //Validations

        $rules = array(

            "username" => "required",
            "type" => "required",
            "name" => "required",
            "country" => "required",
            "city" => "required",
            "telephone1" => "required",
            "telephone2" => "required",
            "email" => "required",


        );


        $validateEmail = Customers::where('email',$rules['email'])->first() ;
        if($validateEmail) return response(["message" => "An Account with this email already exist"]);
        $validateUsername = Customers::where('username', $rules['username'])->first();
        if($validateUsername) return response(["message" => "An Account with this username already exist"]);





        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $validator->errors();
        }else{


        $customers = new Customers;
        $customers->username = $request->username;
        $customers->type = $request->type;
        $customers->name = $request->name;
        $customers->country = $request->country;
        $customers->city = $request->city;
        $customers->status = $request->status;
        $customers->active = $request->active;
        $customers->email = $request->email;
        $customers->email_2 = $request->email_2;
        $customers->generatedCustomId = random_int(1, 900);
        $customers->deleted = $request->deleted;
        $customers->region = $request->region;
        $customers->telephone1 = $request->telephone1;
        $customers->telephone2 = $request->telephone2;

        $customers->save();

        if($customers){
            return[
            "message"=>"Customer Inserted Successfully!!",
            "data"=> $customers
        ];

        }


        }


    }

    /**
    @Description Get a customer by id
    @author SOH ACHILLE
    @Company Bloosat
    */
        public function getOne($id)
        {
            $customer = Customers::find($id);
            if(!empty($customer)){
                return response()->json($customer);
            }else{
                return response()->json([
                    "message"=> "Sorry customer with $id do not exist",
                    "data" => null
                ], 404);
            }
        }


        //Update customers by $id
        public function updateCustomer(Request $request, $id)
        {

            if(Customers::where('id', $id)->exists()){

                $customer = Customers::find($id);
                $customer->username = is_null($request->username) ? $customer->username : $request->username;
                $customer->type = is_null($request->type) ? $customer->type  : $request->type;
                $customer->name = is_null($request->name) ? $customer->name : $request->name;
                $customer->country = is_null($request->country) ? $customer->country : $request->country;
                $customer->city = is_null($request->city) ? $customer->city : $request->city;
                $customer->status = is_null($request->status) ? $customer->status : $request->status;
                $customer->active = is_null($request->active ? $customer->active : $request->active);
                $customer->email = is_null($request->email) ? $customer->email : $request->email;
                $customer->email_2 = is_null($request->email_2) ? $customer->email_2 : $request->email_2;
                $customer->deleted = is_null($request->deleted ? $customer->deleted : $request->deleted);
                $customer->region = is_null($request->region ? $customer->region : $request->region);
                $customer->telephone1 = is_null($request->telephone1) ? $customer->telephone1 : $request->telephone1;
                $customer->telephone2 = is_null($request->telephone2) ? $customer->telephone2 : $request->telephone2;

                $customer->save();
                /* @Description  Saving after verifying if !!OR Not
                Data was null valid*/
                return [
                    response()->json([
                        "Message" => "Customer Updated successfully!!",
                        "data" => $customer
                    ], 201)
                ];
            }



        }

        //update status only -> one
        public function statusUpdate(Request $request, $id)
        {
            $customer = Customers::find($id);



            $customer->status = is_null($request->status) ? $customer->status : $request->status;
            $customer->save();
            return response([
                "data"=> $customer['status']
                ]);





        }

        //Get total customers from the database to set as counters
        public function getTotalCustomersCount(Request $request)

        {
            $totalCustomers = Customers::all();
            $customersCounters =  $totalCustomers->count();
            return response()->json([
                "messages" => "Total Customers",
                "count" => $customersCounters
            ]);

        }





//

        /*@Description  Delete Customers from the application while
        keeping there customers id in log factory */
            public function deleteCustomers($id){

                // if(Customers::where('id', $id)->exists()){

                //     $customer = Customers::find($id);
                //    	$customer->delete();
				// 	$subscription = Subscription::where('customerId','=',$id)->delete();
				// 	$payment = Payment::where('customerId','=',$id)->delete();
				// 	//$bills = Bills::where()->delete();

                //     return response()->json([
                //         "Message" => "Data with id $id deleted successfully",
                //         "data" => [$id,$subscription,$payment],

                //     ],202);
                // }else {

                //     return response()->json([
                //         "message" => "Sorry but Customer with $id not found",
                //         "data" => "Internal error"
                //     ], 404);
                // }

                
                $customer =  Customers::find($id);

                $customer->update(['deleted'=>"1"]);

                return $customer;



            }


            public function showToken(){
                echo csrf_token();
            }
	
	
	  

}
