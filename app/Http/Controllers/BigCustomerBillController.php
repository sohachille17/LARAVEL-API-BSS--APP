<?php

namespace App\Http\Controllers;

use App\Model\BigCustomerBill;
use App\Model\BigCustomerBillItem;
use App\Model\Site;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;




class BigCustomerBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BigCustomerBill::all();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
		return DB::transaction(function() use ($request) {
			 
		//create bill by customer id you get the sites of the customer
        $reqValidData  = $request->validate(['customerId'=>'required|string','billDate'=>'required|string','billType'=>'required|string']);
		$date = $reqValidData['billDate'];
        $type = $reqValidData['billType'];
		
        $customersiteData = DB::table('sites')
                                ->select(
										 'sites.id as siteID',

										 'sites.name as siteName',
										 'sites.location as siteLocation',
										 'sites.description as siteDescription',
			
					 					 'sites.tva as tva',
										 'sites.reduction as reduction',
			
										 'big_customers.id as customerId',     
										 'big_customers.name as customerName',
										 'big_customers.email as customerEmail',
										 'big_customers.email_2 as customerEmail2',
										 'big_customers.telephone1 as customerTelephone',
										 'big_customers.telephone2 as customerTelephone2',
										 'big_customers.country as customerCountry',
										 'site_item.siteId as site_item_SiteId',
										 'site_item.*'									 
										)
                                ->join('site_item','sites.id','=','site_item.siteId')
								->join('big_customers','sites.customerId','=','big_customers.id')
                                ->where('sites.customerId','=',$reqValidData['customerId'])
			                    ->where('sites.status','=','active')
                                ->get();

		
		$sub_total = $customersiteData->sum('total');
		$tva = $customersiteData[0]->tva/100;
		$reduction = $customersiteData[0]->reduction > 0 ?$customersiteData[0]->reduction/100 : 0;
		
		$result_droit_dassise = ceil((2/100) * $sub_total);
        $droit_daccise = $tva > 0 ? $result_droit_dassise : 0;
       
        $total_sous = ceil(($droit_daccise + $sub_total));
		
		$tax = ceil($tva * $total_sous);

        $total =  $reduction?( $total_sous * $reduction) :  $total_sous;
        
        $total_ttc = ceil($tax + $total_sous);

		
        // final preparation on bill data
            $newBillData = [ 
				"billDate"=>$date,
				"customerId"=>$customersiteData[0]->customerId,
				"customerName"=>$customersiteData[0]->customerName,
				"customerEmail"=>$customersiteData[0]->customerEmail,
				"customerEmail2"=>$customersiteData[0]->customerEmail2,
				"customerTelephone"=>$customersiteData[0]->customerTelephone,
				"customerTelephone2"=>$customersiteData[0]->customerTelephone2,
				"customerCountry"=>$customersiteData[0]->customerCountry,
				"billNumber"=>"G_FAC-".rand(111,999)."-".time()."-".$customersiteData[0]->customerId,
				"sub_total"=>$sub_total,
				"type"=>$type,
				"tvaAmount"=>$tva ,
				"discount"=>$reduction ,
				"droit_dassise_calculte"=>$result_droit_dassise,
        		"droit_daccises"=>$droit_daccise,
        		"total_sous"=>$total_sous,
				"tax"=>$tax,
        		"montant_ttc"=>$total_ttc,
        		"total"=>$total,
			];

        $newBill = BigCustomerBill::create($newBillData);
		//add the bill id to the customerSiteData Object to be able to use it array_reduce scope

			
			$billItems = [];
			   foreach ($customersiteData as $data) {
						$item = [
							"name"=>$data->name,
							"price"=>$data->price,
							"quantity"=>$data->quantity,
							"total"=>$data->total,
							"bill_id"=>$newBill->id,
							"type"=>$data->type,
							"siteName"=>$data->siteName,

						];
				   
				 $new = BigCustomerBillItem::create($item);
				 array_push($billItems,$new);
		}

			

        return response()->json([
			"bill"=>$newBill,
            "billItem"=>$billItems,
            "new_bill_data"=>$newBillData,

        ], 200);
			
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
       $bill = BigCustomerBill::with(['customers','bill_item'])->find($id);


        return response()->json(
            $bill
        ,200);
    }

    public function showCustomerBills($customerId){
        
        $bill =BigCustomerBill::with(['customers'])->where('customerId','=',$customerId)->get();

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
           $bill = BigCustomerBill::find($id);



           $bill->update($request->all());
   
           return response()->json(
               $bill
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
        // return Payment::destroy($id);
        return Response([
            "message"=>"at the moment this action is not autorizeds"
        ],403);
    }

    public static function createNextBill($request)
    {
       
            return DB::transaction(function() use ($request) {
                 
            //create bill by customer id you get the sites of the customer
            $reqValidData  = $request;
            $date = $reqValidData['billDate'];
            $type = 'redevance';
            
            //var_dump($request);

            
            $customersiteData = DB::table('sites')
                                    ->select(
                                             'sites.id as siteID',
                                             'sites.name as siteName',
                                             'sites.location as siteLocation',
                                             'sites.description as siteDescription',
                
                                              'sites.tva as tva',
                                             'sites.reduction as reduction',
                
                                             'big_customers.id as customerId',     
                                             'big_customers.name as customerName',
                                             'big_customers.email as customerEmail',
                                             'big_customers.email_2 as customerEmail2',
                                             'big_customers.telephone1 as customerTelephone',
                                             'big_customers.telephone2 as customerTelephone2',
                                             'big_customers.country as customerCountry',
                                             'site_item.siteId as site_item_SiteId',
                                             'site_item.*'									 
                                            )
                                    ->join('site_item','sites.id','=','site_item.siteId')
                                    ->join('big_customers','sites.customerId','=','big_customers.id')
                                    ->where('sites.customerId','=',$reqValidData['customerId'])
                                    ->where('sites.status','=','active')
                                    ->get();
    
            //var_dump($customersiteData);

            
            $sub_total = $customersiteData->sum('total');
            $tva = $customersiteData[0]->tva/100;
            $reduction = $customersiteData[0]->reduction > 0 ?$customersiteData[0]->reduction/100 : 0;
            
            $result_droit_dassise = ceil((2/100) * $sub_total);
            $droit_daccise = $tva > 0 ? $result_droit_dassise : 0;
           
            $total_sous = ceil(($droit_daccise + $sub_total));
            
            $tax = ceil($tva * $total_sous);
    
            $total =  $reduction?( $total_sous * $reduction) :  $total_sous;
            
            $total_ttc = ceil($tax + $total_sous);
    
            
            // final preparation on bill data
                $newBillData = [ 
                    "billDate"=>$date,
                    "customerId"=>$customersiteData[0]->customerId,
                    "customerName"=>$customersiteData[0]->customerName,
                    "customerEmail"=>$customersiteData[0]->customerEmail,
                    "customerEmail2"=>$customersiteData[0]->customerEmail2,
                    "customerTelephone"=>$customersiteData[0]->customerTelephone,
                    "customerTelephone2"=>$customersiteData[0]->customerTelephone2,
                    "customerCountry"=>$customersiteData[0]->customerCountry,
                    "billNumber"=>"G_RED-".rand(111,999)."-".time()."-".$customersiteData[0]->customerId,
                    "sub_total"=>$sub_total,
                    "type"=>$type,
                    "tvaAmount"=>$tva ,
                    "discount"=>$reduction ,
                    "droit_dassise_calculte"=>$result_droit_dassise,
                    "droit_daccises"=>$droit_daccise,
                    "total_sous"=>$total_sous,
                    "tax"=>$tax,
                    "montant_ttc"=>$total_ttc,
                    "total"=>$total,
                ];
    
            $newBill = BigCustomerBill::create($newBillData);
            //add the bill id to the customerSiteData Object to be able to use it array_reduce scope
    
                
                $billItems = [];
                   foreach ($customersiteData as $data) {
                            $item = [
                                "name"=>$data->name,
                                "price"=>$data->price,
                                "quantity"=>$data->quantity,
                                "total"=>$data->total,
                                "bill_id"=>$newBill->id,
                                "type"=>$data->type,
                                "siteName"=>$data->siteName,
    
                            ];
                       
                     $new = BigCustomerBillItem::create($item);
                     array_push($billItems,$new);
            }
    
                
    
            return[
                "invoice"=>$newBill,
                "billItem"=>$billItems,
                "new_bill_data"=>$newBillData,
    
            ];
        });      
    
        


    }

}
