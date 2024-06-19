<?php

namespace App\Http\Controllers;

use App\Model\BigCustomerSubscription;
use App\Model\BigPayment as Payment;
use App\Model\BigCustomerBill as Bills;

use App\Model\BigCustomers as Customers;
use App\Model\BigCustomerSubscriptionItems;


use App\Http\Controllers\BigCustomerBillController as BillController;

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;



class BigCustomerSubscriptionController extends Controller
{
    public  $controllersTable = 'subscriptions';
    public static function createEndDate($data){
        $time = strtotime($data);
        $newformat = date('Y-m-d',$time);

        $realData = Carbon::parse($newformat)->toDateTimeString();
        $endDateString =Carbon::parse($newformat)->addDays(31);

        return $endDateString;
    }

    public static function createStartDate($data){
        $time = strtotime($data);
        $newformat = date('Y-m-d',$time);

        $dateString = Carbon::parse($newformat)->toDateTimeString();

        return $dateString;
    }
	
	public function LOG( $request,$actionType,$actionOnTable='',$did_action_succeed='yes',$author='cron'){
		$log = array();
		$log['actionOnTable'] = $this->controllersTable;
		$log['actionType'] = $actionType;
		$log['author'] = $author;
		$log['date'] = date('m-d-y');
		$log['did_action_succeed?'] = $did_action_succeed;

		// $json_log = json_encode($log,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        // file_put_contents(dirname(__FILE__).'/logs.json',$json_log,FILE_APPEND); 

        if (!file_exists(dirname(__FILE__).'/logs.json')) {
            touch(dirname(__FILE__).'/logs.json');
        }

        if(filesize(dirname(__FILE__).'/logs.json') == 0){
            $first_record = array($log);
            $data_to_save =  $first_record;
            clearstatcache();
        }
        else{
            $data_to_save = json_decode(file_get_contents(dirname(__FILE__).'/logs.json'));
            array_push($data_to_save,$log);
        }

        if(file_put_contents(dirname(__FILE__).'/logs.json',json_encode($data_to_save,JSON_PRETTY_PRINT))){
            return "an error occured";
        }; 

        return ;
   
	}

    public static function same_date_but_next_month($data){
        $time = strtotime($data);
        $newformat = date('Y-m-d',$time);

        $dateString = Carbon::parse($newformat)->addMonth()->toDateTimeString();

        return $dateString;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = BigCustomerSubscription::all();

        return Response([
            "data"=>$subscriptions,
            "dataLength"=>"".count($subscriptions)
        ],200);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $newSubscription = $request->validate([
                'billReference'=>'required',
                'customerId'=>'required',
                'customerName'=>'required',
                'startingDate'=>'required',
                'sites_item'=>[
                    'siteName'=>'required',
                    'siteId'=>'required',
                    'type'=>'required',
                    'population'=>'required',
                    'server_id'=>'required',
                ]
        ]);

        if(count($newSubscription['sites_item']) <= 0){
            return Response([
                "message"=> "Vous ne pouvez pas cree de souscription grand compte sans renseignier les sites du client"
            ],403);
        }

            $subscriptions = BigCustomerSubscription::select()
            ->where('customerId','=',$request['customerId'])
            ->orderBy('endingDate','desc')
            ->orderBy('created_at','desc')
            ->get();
    
            if(count($subscriptions) > 0){
                return Response([
                    "dataFound"=>$subscriptions,
                    "message"=> "Ce client possede deja une souscription  veuller editer la dernier souscription et ou la redevance en function de vos besoin"
                ],403);
            }


        return DB::transaction(function() use ($request,$newSubscription) {

            $startingDate = $request->startingDate;
            $request['subscriptionStatus'] = 'ongoing';

            $request['startingDate'] = BigCustomerSubscriptionController::createStartDate($startingDate);
            $request['endingDate'] = BigCustomerSubscriptionController::same_date_but_next_month($startingDate);
            $sites_item = $newSubscription['sites_item'];

            // before creating a subscription you need to verify if the bill(invoices related to it exists)  exists

            $bill = Bills::where('billNumber', '=', $newSubscription['billReference'])->first();


            if(!$bill){
                return Response([
                    "message"=>"La facture avec la quelle vous essayer de cree cette souscription n'existe pas"
                ],403);
            }

            // add the bill id to the data of the subscription body
            $request['billId'] = $bill->id;

            // check if the bill is paid
            if($bill->isPayed != 1 ){
                return Response([
                    "message"=>"La facture avec la quelle vous essayez de cree cette souscription est non payer veuillez charger la preuve de paiement de cette facture"
                ],403);
            }

            // $request['paymentStatus'] = 'paid';
            $request['paymentStatus'] = $bill->isPayed == 1 ?'paid' : 'unpaid';



            // verify if the bill reference is not linked to any other subscription
            $subscriptionWithSubmitedBillUniqueId = BigCustomerSubscription::where('billReference','=', $newSubscription['billReference'])->first();

            if($subscriptionWithSubmitedBillUniqueId){
                return Response([
                    "message"=> "La facture que vous avez essayer d'attacher a cette souscription appartien deja a une autre souscription"
                ],403);
            }


            $initialSubscription = BigCustomerSubscription::create($request->all());
            // return ['Test script says' => 'Everything is working ',"requestData"=>$request->all()];


            if($initialSubscription){
            // create the next subscription after the current subscription and the next bill is created one is okay
            $royaltyBillParams = [
                "customerId"=>$initialSubscription['customerId'],
                "billDate"=>BigCustomerSubscriptionController::same_date_but_next_month($bill['billDate']),
            ];

            $nextSubscriptionBill = BillController::createNextBill($royaltyBillParams);

            error_log("=======> first: ".$initialSubscription['id']);

            if($nextSubscriptionBill){
                // get all information from previous request
                $nextSubscription = $request->all();

                $nextSubscription['billReference'] = $nextSubscriptionBill['invoice']->billNumber;
                $nextSubscription['startingDate'] = Carbon::parse($request->endingDate);
                $nextSubscription['nextTo'] = $initialSubscription['id'];
                $nextSubscription['billId'] =  $nextSubscriptionBill['invoice']->id;
                
                $nextSubscription['endingDate'] = BigCustomerSubscriptionController::same_date_but_next_month($nextSubscription['startingDate']);

                unset($nextSubscription['paymentStatus']);
                unset($nextSubscription['subscriptionStatus']);

                error_log("=======> nextTo: ".$nextSubscription['nextTo']);


                $newNextSubscription = BigCustomerSubscription::create($nextSubscription);
            }

            $sub_sites = [];
            $sub_sites['init_sub'] = [];
            $sub_sites['royal_sub'] = [];

            foreach ($sites_item as $site){
                $subscription_site_data = [
                    'subscriptionId'=>$initialSubscription->id,
                    'siteName'=>$site['siteName'],
                    'siteId'=>$site['siteId'],
                    'population'=>$site['population'],
                    'server_id'=>$site['serverId'],
                    'serialNumber'=>$site['serialNumber'],
                    'type'=>$site['type'], 
                ];

                $initial_subscription_sites =  BigCustomerSubscriptionItems::create($subscription_site_data);
                array_push($sub_sites['init_sub'],$initial_subscription_sites);


                $royalty_subscription_site_data = [
                    'subscriptionId'=>$newNextSubscription->id,
                    'siteName'=>$site['siteName'],
                    'siteId'=>$site['siteId'],
                    'population'=>$site['population'],
                    'server_id'=>$site['serverId'],
                    'serialNumber'=>$site['serialNumber'],
                    'type'=>$site['type'],
                ];
                $royalty_subscription_sites =  BigCustomerSubscriptionItems::create($royalty_subscription_site_data);
                array_push($sub_sites['royal_sub'],$royalty_subscription_site_data);

            }

            return Response([
                "subscription"=>$initialSubscription,
                "nextSubscription"=>$newNextSubscription,
                "sub_sites" => $sub_sites,
            ],200);

            }

            return Response([
                "message"=>"Une Erreur est survenue l'ors de la creation de la facture."
            ],404);
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

        return BigCustomerSubscription::find($id);

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
        // 1 get the BigCustomerSubscription first
        $Subscription =  BigCustomerSubscription::find($id);
        // 2 update the BigCustomerSubscription

        $Subscription->update($request->all());

        return $Subscription;
    }

    public function allow_unpaid_subscription_to_consume(Request $request)
    {
		$requestData = $request->validate([
			"subscriptionID"=>'required',
		]);
		
        // 1 get the BigCustomerSubscription first
        $subscription =  BigCustomerSubscription::find($requestData['subscriptionID']);
        // 2 update the Subscription
		
        $data = [
            "can_operate_unpaid"=>$subscription->can_operate_unpaid === "false"?"true":"false",
            "who_set_can_operate_unpaid"=>$request->registratorName
        ];
		
        $subscription->update($data);
        
        return $subscription;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ["not possible at the moment"];
        return BigCustomerSubscription::destroy($id);
    }

    public function showCustomerSubscriptions(Request $request,$customerId){
	
        $subscriptions = BigCustomerSubscription::select()
        ->where('customerId','=',$customerId)
		->orderBy('endingDate','desc')
		->orderBy('created_at','desc')

        ->get();

		$customer_ongoing = $subscriptions->get(1);
        $population = $customer_ongoing?$customer_ongoing->populationSouscription:null;
        $subscriptionId = $customer_ongoing?$customer_ongoing->subscriptionId:null;


        return response()->json([
            "customers_subscription"=>$subscriptions,
        ],200);

    }

    public function getSitesBySubscriptionId(Request $request, $id){
		$sites = BigCustomerSubscriptionItems::where('subscriptionId','=',$id)->get();

        foreach($sites as $site){
            $site['oss_status'] = "none";

            if($site->type === 'KAF'){

                $token = BigCustomerSubscriptionController::connect_to_telsat();
                if(!$token){
                    return response()->json([
                        "error"=>"could obtain token from telnet",
                    ],404);
                }
    
                $site->oss_status = BigCustomerSubscriptionController::get_customer_info_on_oss($site->population,$site->server_id,$token);

            }

            
            if($site->type === 'IWAY'){
                $site->oss_status = BigCustomerSubscriptionController::get_customer_info_iway_echotel($site->server_id);
            }

            if($site->type === 'BLOOSTAR'){

            }

        }

 

		return $sites;

    }

    public function check_and_end_ongoing_subscription_if_expired(Request $request){

        $today = date("Y-m-d");

        return DB::transaction(function() use ($request,$today){
        // 1 get the Subscription first
        // 2 update the Subscription

            $subscriptions =  BigCustomerSubscription::where([
                ['endingDate','<',$today],
                ['subscriptionStatus','!=','finished'],

            ])
			->update(['subscriptionStatus'=>'finished']);


            if(!$subscriptions){
                return response()->json([
                    "error"=>"Nothing to update , No expired subscriptions",
                    "data"=>$subscriptions
                ],404);
            }

            return response()->json([
                "message"=>"expired subscriptions status changed from ongoing to finished",
                "data"=>$subscriptions
            ],200);

        });


    }


    public function check_and_set_royalty_subscription_to_ongoin(Request $request){

        // get all the subscriptions id on which you are royalties and set them to ongoing

        return DB::transaction(function() use ($request){

            $subscription_ids = [];
            $today = date("Y-m-d");
            $today_plus_30 = date('Y-m-d', strtotime($today . ' +30 days'));
            $updatedSubscriptions = [];

            // 1 get the BigCustomerSubscription first
            $subscriptions =  BigCustomerSubscription::select('*')
			->whereRaw("
			subscriptionStatus='royalty' AND
			startingDate < CURDATE() AND
			(
			MONTH(startingDate) = MONTH(CURDATE()) OR
			MONTH(endingDate) = MONTH(CURDATE())
			)
			")
			->get();

            //return $subscriptions;

            // remove all the id,s and form a simple array of ids
            foreach($subscriptions as $index => $subscription){
                $subscription_ids[$index] = $subscription->id;
            }

            $updatedSubscriptions = BigCustomerSubscription::whereIn('id',$subscription_ids)->update(['subscriptionStatus' => 'ongoing']);

            if(!$updatedSubscriptions){
                return response()->json([
                    "message"=>"No royalties update to ongoing ",
                    "data"=>[
                        "updatedDocuments"=>$updatedSubscriptions,
                        "updatedDocumentsIds"=>$subscription_ids
                        ]
                ],404);
            }

            return response()->json([
                "message"=>"royalties subscriptions status changed from royalties to ongoing",
                "data"=>[
                    "updatedDocuments"=>$updatedSubscriptions,
                    "updatedIds"=>$subscription_ids
                    ]
            ],200);

        });


    }

    public function create_new_next_subscriptions_version_kaf(Request $request){
        // 4 v2 create next subscription for kaf customers
        
        return DB::transaction(function() use ($request) {

            $newNextSubscriptions = [];
            $customers = Customers::where('status','=',1)->get();
			//1 D active

            $errorCustomers =[];


            foreach($customers as $index => $customer){
                try {
                    //code...
            
                    // lets get the last subscription for this user
                    $customers_last_subscription = BigCustomerSubscription::select('*')
                                            ->where('customerId','=',$customer->id)
                                            ->orderBy("startingDate","asc")
                                            ->get()
                                            ->last();

                    // var_dump($customers_last_subscription);
                    
                    $subscriptionItems = BigCustomerSubscriptionItems::select('*')->where('subscriptionId','=',$customers_last_subscription['id'])->get();

                    // print_r("number of items : ".count($subscriptionItems));


                    if(!$customers_last_subscription){
                        continue;
                    }

                    if(!$customers_last_subscription['subscriptionStatus'] == 'terminated'){
                        continue;
                    }

                    $subscriptionStatus = $customers_last_subscription['subscriptionStatus'];


                    // return var_dump($subscriptionStatus);
                    // if this subscription is the ongoing then we have to create a royalty for it
                    
                    
                    if($subscriptionStatus == 'ongoing' || $subscriptionStatus == 'finished'){
                        // create a new bill from this subscription and get it's bill id
                        $royaltyBillParams = [
                            "customerId"=>$customers_last_subscription['customerId'],
                            "billDate"=>BigCustomerSubscriptionController::same_date_but_next_month($customers_last_subscription['startingDate']),
                        ];

                        $newBill= BillController::createNextBill($royaltyBillParams);


                        if(!$newBill){
                            $error = [
                                "message"=>"bill not found creating next subscription bill",
                                "bill_not_found"=>$customers_last_subscription['billReference']
                            ];
                            array_push($errorCustomers,["customer"=>$customer,"hisError"=>$error]);
                            continue;
                        }

                        // create a new royalty subscription for this customer with the bill id you just obtained

                        $nextSubscription = json_decode($customers_last_subscription,true);
                        unset($nextSubscription['id']);
                        unset($nextSubscription['paymentStatus']);
                        unset($nextSubscription['subscriptionStatus']);
                        $nextSubscription['billReference'] = $newBill['invoice']->billNumber;
                        $nextSubscription['billId'] =  $newBill['invoice']->id;
                        $nextSubscription['startingDate'] = Carbon::parse($customers_last_subscription->endingDate);
                        $nextSubscription['nextTo'] = $customers_last_subscription->id;
                        $nextSubscription['endingDate'] = BigCustomerSubscriptionController::same_date_but_next_month($nextSubscription['startingDate']);

                        $newSubscription = BigCustomerSubscription::create($nextSubscription);

                        
              

                        if(!$newSubscription){
                            return response()->json([
                                "message"=>"an error occured when creating next subscription"
                            ],500);
                        }

                        $sub_sites = [];
                        

                        foreach ($subscriptionItems as $site){

                            $subscription_site_data = [
                                'subscriptionId'=>$newSubscription->id,
                                'siteName'=>$site['siteName'],
                                'siteId'=>$site['siteId'],
                                'population'=>$site['population'],
                                'server_id'=>$site['server_id'],
                                'serialNumber'=>$site['serialNumber'],
                                'type'=>$site['type'], 
                            ];
                            
                            $initial_subscription_sites =  BigCustomerSubscriptionItems::create($subscription_site_data);
                            array_push($sub_sites,$initial_subscription_sites);
                        }

                        error_log("This subscription {$customers_last_subscription->id} new subscription will be nextTo: ".$nextSubscription['nextTo']);

                        // add the new subscription to result array
                        $newNextSubscriptions[$index] = $newSubscription;

                    }

                } catch (\Throwable $th) {
                    array_push($errorCustomers,["customer"=>$customer,"hisError"=>$th]);
                    continue;

                }
            }


            if(!$newNextSubscriptions){
                return response()->json([
                    "message"=>"big customer",
                    "error"=>"Nothing to update",
                    "data"=>$newNextSubscriptions,
                    "array of subscriptions who needed a next element in cycle"=>$newNextSubscriptions,
                    "newNextSubscription"=>$newNextSubscriptions,
                    "errors"=>$errorCustomers
                ],200);
            }

            return response()->json([
                "message"=>"royalties subscriptions status changed from royalties to ongoing",
                "data"=>$newNextSubscriptions,
                "array up subscriptions who needed a next element in cycle"=>$newNextSubscriptions,
                "newNextSubscription"=>$newNextSubscriptions,
                "items"=>$sub_sites
            ],200);



        });

    }


    public function end_subscription_cycle(Request $request){
                
        $reqData = $request->validate([
            'subscriptionId'=>'required',
        ]);

        $Subscription =  BigCustomerSubscription::find($reqData['subscriptionId']);

        $Subscription->update(['subscriptionStatus' => 'terminated']);
        return $Subscription;
    
}

    public function get_soon_ending_subscriptions(Request $request){
        $days_left_to_end =  $request->days? $request->days : 7;

        // var_dump($days_left_to_end);
        $condition1 = "DATEDIFF(endingDate,curdate()) = ".$days_left_to_end;

        $subscriptions = BigCustomerSubscription::select('*')
        ->leftJoin('big_customers','big_customers_subscriptions.customerId','=','big_customers.id')
        ->where([['subscriptionStatus','=','ongoing'],
                 ['paymentStatus','=','paid']
        ])
        ->whereRaw($condition1)
        ->get();

		// $mail = MailController::sendMail();
        foreach($subscriptions as $subscription){

            try {
                $bill = Bills::select()->where('billNumber','=',$subscription->billReference)->get()->first();
                // print_r($bill);
                MailController::sendMail($subscription->email,$subscription->endingDate,$bill,$days_left_to_end,$type="grand-comptes");
            } catch (\Throwable $th) {
            //    print_r($th->getMessage());
                print_r('an error has occured in the subscription mailer function');
            }


        }

        return $subscriptions;
    }


    public  function connect_to_telsat(){

   	    $new_client_secret = 'ey_8Q~2a2U_-maQ8cCT8YtrvFOMkwqeoEl3GKdBN';
		$old_client_secret = 'YiXvj2uC2-~GM3L277vA50qi2.KC.-fglS';

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api-knt.skylogic.com/ext/api/v1/whs/oauth2/token',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 3600,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=d9e9acc9-5243-4fee-bfce-4298ff9837d8&client_secret=ey_8Q~2a2U_-maQ8cCT8YtrvFOMkwqeoEl3GKdBN",
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/x-www-form-urlencoded",
			),
			CURLOPT_CAINFO => 'curl_certif/cacert.pem'
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$result=json_decode($response) ;

        try {
            return $result->access_token;

        } catch (\Throwable $th) {
            return "could not get token from kaf";
        }




    }

    public  function suspend_telsat_subscription($population,$subscriptionId,$token){

        $curl = curl_init();

        $data=["status"=>"suspended"];
        $data_string = json_encode($data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL =>
		  "https://api-knt.skylogic.com/ext/api/v1/whs/services/{$population}/subscriptions/{$subscriptionId}/actions/change_status",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$data_string,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token,
            "Content-Type:application/json",
            "Accept: application/json"
        ),
		));

		$response = curl_exec($curl);

		curl_close($curl);


		return  json_decode($response);

    }

    function get_customer_info_on_oss($population,$subscriptionId,$token){
        // oss is telsat's service



        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-knt.skylogic.com/ext/api/v1/whs/services/".$population."/subscriptions/".$subscriptionId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 3600,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token,
        "Content-Type:application/json",
        "Accept: application/json"
        )

        ));



       $response = curl_exec($curl);
       $err = curl_error($curl);

       curl_close($curl);

       if ($err) {
       echo "cURL Error #:" . $err;
       } else {


       $result=json_decode($response,true);

       return $result;

       }


    }

    public function suspension_log(Request $request){
        $type = $request->type?$request->type:'KAF';
        
        try {
            if (!file_exists(dirname(__FILE__).'/logs.json')) {
                return response()->json([
                    "data"=>NULL,
                    'message'=>'no logs at the moment',
                ],200);
            }

            $subscriptions =  json_decode(file_get_contents(dirname(__FILE__).'/logs.json'));

            return response()->json([
                "data"=>$subscriptions,
            ],200);

        } catch (\Throwable $th) {

            $subscriptions =  ['error'=>'could not get logs'];            
            return response()->json([
                "data"=>$subscriptions,
                'message'=>'failed',
            ],404);
            
        }
		
	
    }

    public function check_unpaid_ongoing_kaf_subscription_and_suspend(Request $request){
		
		return DB::transaction(function() use ($request){

            $today = date("Y-m-d");
            $today_plus_30 = date('Y-m-d', strtotime($today . ' +30 days'));
            $month = date('m');
            $blocked_subscription_ids = [];
			$blocked_successfully_bss = [];
            $token = BigCustomerSubscriptionController::connect_to_telsat();
			
			
            if(!$token){
                return response()->json([
                    "error"=>"could obtain token from telnet",
                ],404);
            }

            // 1 get all the Subscription that are ongoing and unpaid
            $subscriptions =  BigCustomerSubscription::select('*')->where([
                ['subscriptionStatus','=','ongoing'],
                // ['billReference','LIKE','RED%'],
                ['paymentStatus','=','unpaid'],
				
				//new condition below unselects subscriptions with said property true
				['can_operate_unpaid','=','false'],
				//['suspentionStatus','!=','suspended'],
                // ['subscriptionType','=','KAF'],
                // ['startingDate','<','CURRENT_DATE']
                ])
				//->whereMonth('endingDate', '<=',$month)
                ->get();
			
            // loop throgh the subscriptions and try to block them register
            // the ids of the one's you blocked successfully and those you could not block
            foreach($subscriptions as $index => $subscription){
                // try to suspend
                $response = BigCustomerSubscriptionController::suspend_telsat_subscription($subscription['populationSouscription'],
                                                                      $subscription['subscriptionId'],
                                                                      $token);

                $blocked_subscription_ids[$index] = ["id"=>$subscription->id,
                                                     "customerName"=>$subscription->customerName,
													 "response"=>$response,
													 "population"=>$subscription['populationSouscription'],
													 "server_id"=>$subscription['subscriptionId'],
													//  "token"=>$token
													];
				$blocked_successfully_bss[$index] = $subscription->id;
                // var_dump($response);
                // if($response['success'] && ($response['message'] !== "BigCustomerSubscription already in status suspended. Nothing to do."))
                // {
                 $this->LOG($request,$actionType='Suspended subscription of ['.$subscription->customerName.'] with customer id ['.$subscription->customerId.']'.' and subscription id ['.$subscription->id.']');
                // }
            }



			$bss_updated =BigCustomerSubscription::whereIn('id',$blocked_successfully_bss)->update(['suspentionStatus' => 'suspended']);


            

            if(!$subscriptions){
                return response()->json([
                    "error"=>"could not terminate any subscriptions",
                    "data"=>$subscriptions,
					"bss"=>$bss_updated
                ],404);
            }

            
            return response()->json([
                "message"=>"The unpaid but ungoing subscriptions status changed from ongoing to terminated",
                //"subscriptions"=>$subscriptions,
                "suspended"=>$blocked_subscription_ids,
				"bss"=>$bss_updated
            ],200);

        });

    }


// -------------------------------------------------------------------------------------------------------------------------------------------------
//         Iway suspension

    function suspend_iway_subscription($id_site){

        $token="bloosat_api_user:zGYTyVh7"; // Usrname:password for API authentification


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://billing2.iwayafrica.com/aodist/services/ka/sites/".$id_site."/suspend",



        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 3600,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Basic ".(base64_encode($token)),
          "Cache-Control: no-cache",
            "Content-Type: application/xml"
        )

      ));


      $response = curl_exec($curl);
      $err = curl_error($curl);
	   $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      curl_close($curl);


        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        //echo $response;

            $array_data = json_decode(json_encode($response), true);


            //var_dump($httpcode);

            //echo  $httpcode;

            return $array_data;


        }


    }


    public function check_unpaid_ongoing_iway_subscription_and_suspend(Request $request){
        return DB::transaction(function() use ($request){

            $today = date("Y-m-d");
            $today_plus_30 = date('Y-m-d', strtotime($today . ' +30 days'));
            $month = date('m');
            $blocked_subscription_ids = [];
			$blocked_successfully_bss = [];


            // 1 get all the Subscription that are ongoing and unpaid
            $subscriptions =  BigCustomerSubscription::select('*')->where([
                ['subscriptionStatus','=','ongoing'],
                ['billReference','LIKE','RED%'],
                ['paymentStatus','=','unpaid'],
				// ['suspentionStatus','!=','suspended'],
				// ['subscriptionType','=','IWAY'],

                ])
				//->whereMonth('endingDate', '<=',$month)
                ->get();

            // loop throgh the subscriptions and try to block them register
            // the ids of the one's you blocked successfully and those you could not block
            foreach($subscriptions as $index => $subscription){
                // try to suspend
                $response = BigCustomerSubscriptionController::suspend_iway_subscription($subscription['subscriptionId']);

                $blocked_subscription_ids[$index] = ["id"=>$subscription->id,
													 "response"=>$response,
													 "population"=>$subscription['populationSouscription'],
													 "server_id"=>$subscription['subscriptionId'],
													];
				$blocked_successfully_bss[$index] = $subscription->id;
            }



			$bss_updated =BigCustomerSubscription::whereIn('id',$blocked_successfully_bss)->update(['suspentionStatus' => 'suspended']);



            if(!$subscriptions){
                return response()->json([
                    "error"=>"could not terminate any subscriptions",
                    "data"=>$subscriptions,
					"bss"=>$bss_updated
                ],404);
            }

            return response()->json([
                "message"=>"The unpaid but ungoing subscriptions status changed from ongoing to terminated",
                "subscriptions"=>$subscriptions,
                "suspended"=>$blocked_subscription_ids,
				"bss"=>$bss_updated
            ],200);

        });

    }


	function get_customer_info_iway_echotel($id_site){

        $token="bloosat_api_user:zGYTyVh7"; // Usrname:password for API authentification


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://billing2.iwayafrica.com/aodist/services/ka/sites/".$id_site."",



        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 3600,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Basic ".(base64_encode($token)),
          "Cache-Control: no-cache",
            "Content-Type: application/xml"
        )

      ));


      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);


        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        //echo $response;

            $array_data = json_decode(json_encode(simplexml_load_string($response)), true);

            return $array_data;

        }


    }









}


/*
	The oss api uses the
	subcriptionPopulation (population_code) and the subscriptionId
	------------------------------------------------
	Population              |   population_code
	------------------------------------------------
	 VNO-bloosat	        |   200047
	------------------------------------------------
	 BLOOSAT-SA             |   200045
	------------------------------------------------
	 BLOOSAT SA ADVANCED KA	|
	------------------------------------------------
*/
