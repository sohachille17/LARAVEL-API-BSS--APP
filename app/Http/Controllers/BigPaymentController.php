<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\BigPayment as Payment;
use App\Model\BigCustomerBill as Bills;
use App\Model\BigCustomerSubscription as Subscription;
use App\Model\BigCustomers as Customers;
use Illuminate\Support\Facades\DB;



class BigPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Payment::all();
        // return DB::table('oos_bloo')
        // ->select('*')
        // ->join('oos_bloo','users.id','=','big_payments.registeredBy')
        // ->get();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        

        // return $request->all();
        // error_log($request);
        // return ["test mode watch server console"];
        $feilds = $request->validate(
            [
                'customerId'=>'required|string',
                'payementDate'=>'required|string',
                'siteName'=>'required|string',
                'billReference'=>'required|string',
                'amount'=>'required',
                'paymentMethod'=>'required|string',
                'payementAttachment1'=>'required|mimes:jpeg,jpg,png,doc,docs,pdf,docx',
                'payementAttachment2',
                'transactionNumber1'=>'required|string',
                'transactionNumber2',
                'registeredBy'=>'required'
                // 'comment'=>'required'

            ]);


            // verifications 
            $customerPayingTheBill = Customers::select()->where('id','=',$feilds['customerId'])->get()->first();
            
            // error_log("customer :".$customerPayingTheBill);

            return DB::transaction(function() use ($request,$customerPayingTheBill,$feilds) {

            if(!$customerPayingTheBill){
                return response()->json([
                    "error" => "The customer you are trying to pay for does not exist",
                    "customer" => $feilds['customerId']
                ],404);
            }



            // verifications 
            $billToBePaid = Bills::where("billnumber", "=", $feilds['billReference'])
				->where("customerId", "=", $feilds['customerId'])
				->get()
                ->first();
            
            // error_log("bill :".$billToBePaid." bill reference : ".$feilds['billReference']);

            // return $billToBePaid;

            if(!$billToBePaid){
                return response()->json([
                    "error" => "The customer's bill you are trying to pay does not exist",
                    "billReference" => $feilds['billReference']
                ],404);
            }
			
			
            if($billToBePaid->isPayed == 1){
                return response()->json([
                    "error" => "The customers bill you are trying to pay has already been paid",
                    "billReference" => $billToBePaid
                ],406);
            }
			


            // check if the bill beign paid has a running subscription
            $currentBillSubscription = Subscription::where("billReference","=",$billToBePaid['billNumber'])->get()->first();


            // return [$currentBillSubscription,$billToBePaid['billNumber'],$billToBePaid];
            
            $file = $request->file("payementAttachment1");
            $fileName = 'paymentAttachment-'.$feilds['customerId'].
                        '-'.date('Y-m-d').
                        "-".time().
                        ".".$file->getClientOriginalExtension();


            // $destinationPath = "paymentAttachments/customers/customer-".$request->customerId;
            $destinationPath = "bigPaymentAttachments/";


            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if ($file->move($destinationPath,$fileName)){
                $feilds['payementAttachment1'] = $fileName;
                $feilds['amount'] = (float)$feilds['amount'];

            }else{
                return Response(["message"=>"An error occured when registering the file you uploaded"],401);
            }
                
        // 2 create payment from data and update the corresponding feilds
        $payment = Payment::create($feilds);

        if(!$payment){
            return response()->json([
                "error" => "The creation of the billpayment was unsuccessfull for unknown reason please try again",
                "billToBepaid" => $billToBePaid
            ],500);
        }

        $billToBePaid->isPayed = 1;
        $billToBePaid->save();

        if($currentBillSubscription){
            $currentBillSubscription->paymentStatus = 'paid';
            $currentBillSubscription->save();
        }
				
		//print_r([$payment,$billToBePaid,$currentBillSubscription]);
		//throw new Exception('TypeError');
        
		return  response()->json([
        "payment"=>$payment,
        "message"=>"sucessfully created",
        "updated files"=>[
            "bill"=>$billToBePaid,
            "subscription"=>$currentBillSubscription
        ]
    ],200);

    });

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment =  DB::table('big_payments')
        ->select(
             'big_payments.id',
             'customerId',
             'payementDate',
             'siteName',
             'paymentMethod',
             'payementAttachment1',
             'payementAttachment2',
             'transactionNumber1',
             'transactionNumber2',
             'comment',
             'amount',
             'registeredBy'
            ,'big_payments.billReference',
            'users.name as registeredBy',
            'amount',
            'big_customers.username',
            'big_customers.created_at',

            )
        ->join('big_customers','big_payments.customerId','=','big_customers.id')
        ->join('users','big_payments.registeredBy','=','users.id')
        ->where('big_payments.id','=',$id)
        ->get()
        ->first();

        if(!$payment){
            return response()->json([
                "error"=>"payment doesnt exist"
            ],404);
        }

        return response()->json(
            $payment
        ,200);
    }

    public function showCustomerPayments($customerId){
        /*
SELECT * FROM big_payments
INNER JOIN customers ON big_payments.customerId=customers.id
        */

        return DB::table('big_payments')
        ->select(
             'big_payments.id',
             'customerId',
             'payementDate',
             'siteName',
             'paymentMethod',
             'payementAttachment1',
             'payementAttachment2',
             'transactionNumber1',
             'transactionNumber2',
             'comment',
             'amount',
             'registeredBy'
            ,'big_payments.billReference',
            'users.name as registeredBy',
            'amount',
            )
        ->join('big_customers','big_payments.customerId','=','big_customers.id')
        ->join('users','big_payments.registeredBy','=','users.id')
        ->where('big_customers.id','=',$customerId)
        ->get();
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
           $payment =  Payment::find($id);
        
           // 2 update the product 
           $payment->update($request->all());
   
           return $payment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // return Payment::destroy($id);
        return Response([
            "message"=>"at the moment this action is not autorizeds"
        ],403);
    }
}
