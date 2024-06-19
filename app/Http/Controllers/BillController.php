<?php

namespace App\Http\Controllers;
use App\Model\Bills;
use App\Model\Customers;
use App\Model\ProductItems;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




use Illuminate\Http\Request;


    /**
    @Description Updating customer data
    @author SOH TAGNE ACHILLE
    @Company Bloosat */

class BillController extends Controller
{



    public function createBill(Request $request)
    {
        $feilds = $request->validate(
            [
                'sub_total'=>'required',
                'total'=>'required',
                'customer_id' => 'required',
                'customerName' => 'required',
                'customer_id' => 'required',
                'dateLimit' => 'required',
                'reduction_in' => 'required',
                'tax_in' => 'required',
                'billNumber'  => 'required',
                'compantName'  => 'required',
                'serviceAddress'  => 'required',
                'postalAddress'  => 'required',
                'phoneNumber'  => 'required',
                'customerEmailAddress'  => 'required',
                'websiteLink'  => 'required',
                'type'  => 'required',
                'currency'  => 'required',
                'droit_daccises'  => 'required',
                'montant_ttc'  => 'required',
                'tvaAmount'  => 'required',
                'discount'  => 'required',
                'status'  => 'required',
                'small_note'  => 'required',
                'invoice_item' => 'required'

            ]);
        $invoiceitem = $request->input("invoice_item");

        $invoiceData['sub_total'] = $request->input('sub_total');
        $invoiceData['total'] = $request->input('total');
        $invoiceData['customer_id'] = $request->input('customer_id');
        $invoiceData['customerName'] = $request->input('customerName');
        $invoiceData['dateLimit'] = $request->input('dateLimit');
        $invoiceData['reduction_in'] = $request->input('reduction_in');
        $invoiceData['tax_in'] = $request->input('tax_in');
        $invoiceData['billNumber'] = $request->input('billNumber');
        $invoiceData['compantName'] = $request->input('compantName');
        $invoiceData['serviceAddress'] = $request->input('serviceAddress');
        $invoiceData['postalAddress'] = $request->input('postalAddress');
        $invoiceData['phoneNumber'] = $request->input('phoneNumber');
        $invoiceData['customerEmailAddress'] = $request->input('customerEmailAddress');
        $invoiceData['websiteLink'] = $request->input('websiteLink');
        $invoiceData['type'] = $request->input('type');
        $invoiceData['currency'] = $request->input('currency');
        $invoiceData['droit_daccises'] = $request->input('droit_daccises');
        $invoiceData['montant_ttc'] = $request->input('montant_ttc');
        $invoiceData['tvaAmount'] = $request->input('tvaAmount');
        $invoiceData['discount'] = $request->input('discount');
        $invoiceData['status'] = $request->input('status');
        $invoiceData['small_note'] = $request->input('small_note');


        $invoice = Bills::create($invoiceData);

        foreach( $invoiceitem as $item){
            $itemdata['bills_id'] = $invoice->id;
            $itemdata['product_name'] = $item['product_name'];
            $itemdata['product_id'] = $item['product_id'];
            $itemdata['unit_price'] = $item['unit_price'];
            $itemdata['quantity'] = $item['quantity'];
            $itemdata['total'] = $item['total'];



            ProductItems::create($itemdata);
        }

        return response()->json([

            "invoiceData" => $invoiceData,
            "new_invoice" => $itemdata
        ]);


    }


    public function getAllBill(Request $request){
        // the limit is the page number times the number of information per page
        $limit = $request->limit ? $request->limit*50 : 100;
        // $bills = Bills::all();

        $bills = Bills::paginate($limit);

        return response()->json($bills);
    }
    //Get bill by id unique
    public function getOneBill($id){

        $bill = Bills::find($id);

        if($bill){
            return response()->json([
                "success"=> true,

                "data"=> $bill
            ]);
        }else{
            return response()->json([
                "success"=> false,
                "message"=> "Sorry but bill with id $id can't be found",
                "data"=> null
            ]);
        }
    }


    public static function getOneBillByReference($billReference){

        $bill = Bills::with(['invoice_item'])->where('billNumber','=',$billReference)->first();
		$response = json_decode(json_encode($bill), true);

        if($bill){
            return  $response;
        }else{
            return $bill;
        }
    }


    public function edit($id){

        $bill = Bills::with(['customers','invoice_item'])->find($id);
        return response()->json([
            "invoice" => $bill

    ]);

    }
    //



    public function update__billing(Request $request , $id)
    {

        $invoiceitem = $request["invoice_item"];


        $invoice = Bills::where('id', $id)->first();
        $invoice->sub_total = $request->sub_total;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->customerName = $request->customerName;
        $invoice->dateLimit = $request->dateLimit;
        $invoice->billNumber = $request->billNumber;
        $invoice->reduction_in = $request->reduction_in;
        $invoice->tax_in = $request->tax_in;
        $invoice->compantName = $request->compantName;
        $invoice->serviceAddress = $request->serviceAddress;
        $invoice->postalAddress = $request->postalAddress;
        $invoice->phoneNumber = $request->phoneNumber;
        $invoice->customerEmailAddress = $request->customerEmailAddress;
        $invoice->websiteLink = $request->websiteLink;
        $invoice->type = $request->type;
        $invoice->droit_daccises = $request->droit_daccises;
        $invoice->montant_ttc = $request->montant_ttc;
        $request->currency = $request->currency;
        $invoice->tvaAmount = $request->tvaAmount;
        $invoice->discount = $request->discount;
        $invoice->status = $request->status;
        $invoice->small_note = $request->small_note;


        unset($request['billNumber']);

        $invoice->update($request->all());

        $invoiceitem = $request->input("invoice_item");

        $invoice->invoice_item()->delete();

        foreach( $invoiceitem as $item){
            $itemdata['bills_id'] = $invoice->id;
            $itemdata['product_name'] = $item['product_name'];
            $itemdata['product_id'] = $item['product_id'];
            $itemdata['unit_price'] = $item['unit_price'];
            $itemdata['quantity'] = $item['quantity'];
            $itemdata['total'] = $item['total'];



            ProductItems::create($itemdata);
        }

        return response()->json([
            "update" => $invoice
        ]);



    }
    //get totalBill by id
    public function getTotalBill(Request $response){




    }


    // get calculated reciept
    public function calculateBillReciept(Rrquest $request){



    }


    public function show($id)
    {
        //
        $bills = Bills::findorfail($id);

        return response()->json([
            "bill" => $bills
        ]);


    }

    public function show_billing($id){

        $bill = Bills::with(['customers','invoice_item'])->find($id);

        return response()->json([
            "invoice" => $bill
        ]);
    }

    public function deleteInv($id){
        $bill = Bills::findOrFail($id);
        $bill->invoice_item()->delete();
        $bill->delete();
    }


    public function getCustomerBillById(Request $request,$id)
    {
        $type = $request->type? $request->type : null;

        // $cus__bill = Bills::join('subscriptions', 'bills.id','=','subscriptions.billId')
        //             ->where('customer_id', $id)
        //             ->select('*','billReference as billNumber')
        //             ->get();
        //     if (!is_null($type)){
        //         $cus__bill = $cus__bill->where('subscriptionType','=',$type);
        //     }

        $cus__bill = Bills::where('customer_id', $id)
                    ->get();
        return response( $cus__bill);



    }

    public static function getservices(array $items, $excludeTypeOfProduct="product"){

        $filtered = array_filter($items, function ($item) use ($excludeTypeOfProduct) {
            return $item['type'] != $excludeTypeOfProduct;
        });

        // return array_map(function ($u) { return $u['name']; }, $filtered);
        return $filtered;
    }



    public static function createNextBill($reference)
    {
        $modelBill = Bills::select('*')->where('billNumber','=',$reference)->get()->first();
		
		if (!$modelBill) return NULL;

        /*
        get all the products that of type service from the model bill which to create the royalty bill
        below is the is the coresponding sql query converted to eloquent

        SELECT *
        FROM oos_bloo.services
        INNER JOIN  oos_bloo.invoice_item ON  oos_bloo.invoice_item.product_id =  oos_bloo.services.id
        WHERE type = 'service';
        */



        $invoiceitem = DB::table('services')
        ->select('*')
        ->join('invoice_item','invoice_item.product_id','=','services.id')
        ->where('type','=','service')
        ->where('bills_id','=',$modelBill->id)
        ->get();


        $tva = $modelBill['tvaAmount']/100;
        $reduction = $modelBill['reduction_in'] > 0 ?$modelBill['reduction_in']/100 : 0;

        $sub_total = $invoiceitem->sum('total');

        $result_droit_dassise = (2/100) * $sub_total;
        $droit_daccise = $tva > 0 ?$result_droit_dassise:0;

        $total_sous = $droit_daccise + $sub_total;

        $tax = $tva * $total_sous;
        $total_ttc = $tax + $total_sous;
        $total =  $reduction?( $sub_total + $droit_daccise ) * $reduction : ( $sub_total + $droit_daccise );



        $invoiceData['sub_total'] = $sub_total;
        $invoiceData['total'] = $total;

        $invoiceData['customer_id'] = $modelBill['customer_id'];
        $invoiceData['customerName'] = $modelBill['customerName'];
        $invoiceData['dateLimit'] = date('Y-m-d', strtotime($modelBill['dateLimit']. ' + 29 days'));
        $invoiceData['reduction_in'] = $reduction;
        $invoiceData['tax_in'] = $tax;
        $invoiceData['billNumber'] = 'RED-'.rand(111,999).'-'.time().'-'.$modelBill['customer_id'];
        $invoiceData['compantName'] = $modelBill['compantName'];
        $invoiceData['serviceAddress'] = $modelBill['serviceAddress'];
        $invoiceData['postalAddress'] = $modelBill['postalAddress'];
        $invoiceData['phoneNumber'] = $modelBill['phoneNumber'];
        $invoiceData['customerEmailAddress'] = $modelBill['customerEmailAddress'];
        $invoiceData['websiteLink'] = $modelBill['websiteLink'];
        $invoiceData['type'] = 'redevance';
        $invoiceData['currency'] = $modelBill['currency'];
        $invoiceData['droit_daccises'] = $droit_daccise;
        $invoiceData['montant_ttc'] = $total_ttc;
        $invoiceData['tvaAmount'] = $modelBill['tvaAmount'];
        $invoiceData['discount'] = $modelBill['discount'];
        $invoiceData['status'] = 0;
        $invoiceData['small_note'] = $modelBill['small_note'];


        $invoice_id = Bills::create($invoiceData);


        foreach( $invoiceitem as $item){

            $rdata = json_encode($item);
            $dc = json_decode($rdata,true);

            $data = [
                'bills_id'=>$invoice_id->id,
                'product_name'=>$dc['product_name'],
                'product_id'=>$dc['product_id'],
                'unit_price'=>$dc['unit_price'],
                'quantity'=> $dc['quantity'],
                'total'=> $dc['total']
            ];

            ProductItems::create($data);

        }

        $invoiceData['id'] = $invoice_id->id;

        return [
               "invoice" => json_decode(json_encode($invoiceData)),
            "newInvoiceItems" => $invoiceitem,
        ];


    }



}
