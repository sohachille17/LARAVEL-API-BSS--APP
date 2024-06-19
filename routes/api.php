<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BigCustomerController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\productQuantitiesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\BigCustomerBillController;
use App\Http\Controllers\BigCustomerBillItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SouscriptionController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\SiteItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BigCustomerSubscriptionController;
use App\Http\Controllers\BigPaymentController;


use App\User;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware'=>['auth:sanctum']],function () {
  Route::post('/logout',[AuthController::class, 'logout']);
  Route::post('/reset-password/{userId}',[AuthController::class, 'resetPassword']);     

});


Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::get('users/count',[UserController::class, 'count']);
  Route::resource('users','UserController');

});


Route::get('big-subscriptions/update-expired-subscriptions',[BigCustomerSubscriptionController::class, 'check_and_end_ongoing_subscription_if_expired']);
Route::get('big-subscriptions/update-royalty-subscriptions',[BigCustomerSubscriptionController::class, 'check_and_set_royalty_subscription_to_ongoin']);
Route::get('big-subscriptions/renew-next-subscriptions-kaf',[BigCustomerSubscriptionController::class, 'create_new_next_subscriptions_version_kaf']);


Route::get('big-subscriptions/check-unpaid-ongoing-and-suspend-kaf',[BigCustomerSubscriptionController::class, 'check_unpaid_ongoing_kaf_subscription_and_suspend']);
Route::get('big-subscriptions/soon-ending',[BigCustomerSubscriptionController::class, 'get_soon_ending_subscriptions']);
Route::get('big-subscriptions/suspension-log',[BigCustomerSubscriptionController::class, 'suspension_log']);

Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::resource('big-subscriptions','BigCustomerSubscriptionController')->middleware('restrictRole:admin,financial');
  Route::post('big-subscriptions/terminate-and-endcycle',[BigCustomerSubscriptionController::class, 'end_subscription_cycle'])->middleware('restrictRole:admin,financial');
  Route::post('big-subscriptions/toggle-consumption',[BigCustomerSubscriptionController::class, 'allow_unpaid_subscription_to_consume'])->middleware('restrictRole:admin,financial');
  Route::get('big-subscriptions/sites/{id}',[BigCustomerSubscriptionController::class, 'getSitesBySubscriptionId'])->middleware('restrictRole:admin,financial');
  Route::get('big-subscriptions/customer/{customerId}',[BigCustomerSubscriptionController::class, 'showCustomerSubscriptions']);


});




Route::get('subscriptions/update-expired-subscriptions',[SubscriptionController::class, 'check_and_end_ongoing_subscription_if_expired']);
Route::get('subscriptions/update-royalty-subscriptions',[SubscriptionController::class, 'check_and_set_royalty_subscription_to_ongoin']);
Route::get('subscriptions/check-unpaid-ongoing-and-suspend-kaf',[SubscriptionController::class, 'check_unpaid_ongoing_kaf_subscription_and_suspend']);
Route::get('subscriptions/check-unpaid-ongoing-and-suspend-iway',[SubscriptionController::class, 'check_unpaid_ongoing_iway_subscription_and_suspend']);

Route::get('subscriptions/renew-next-subscriptions-kaf',[SubscriptionController::class, 'create_new_next_subscriptions_version_kaf']);
Route::get('subscriptions/renew-next-subscriptions-iway',[SubscriptionController::class, 'create_new_next_subscriptions_version_iway']);
Route::get('subscriptions/renew-next-subscriptions-bluestar',[SubscriptionController::class, 'create_new_next_subscriptions_version_bluestar']);

Route::get('subscriptions/soon-ending',[SubscriptionController::class, 'get_soon_ending_subscriptions']);
Route::get('subscriptions/customer/{customerId}',[SubscriptionController::class, 'showCustomerSubscriptions']);
Route::get('subscriptions/suspension-log',[SubscriptionController::class, 'suspension_log']);


Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::post('subscriptions/duplicate',[SubscriptionController::class, 'findDuplicates'])->middleware('restrictRole:admin,financial');

  Route::resource('subscriptions','SubscriptionController')->middleware('restrictRole:admin,financial');
  Route::post('subscriptions/terminate-and-endcycle',[SubscriptionController::class, 'end_subscription_cycle'])->middleware('restrictRole:admin,financial');
  Route::post('subscriptions/toggle-consumption',[SubscriptionController::class, 'allow_unpaid_subscription_to_consume'])->middleware('restrictRole:admin,financial');

});



Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::get('services/products-and-services',[ServicesController::class,'getAllTypes'])->middleware('restrictRole:admin,financial');
  Route::get('services/count-all-services',[ServicesController::class,'countServices']);
  Route::get('services/count-all-products',[ServicesController::class,'countProducts']);
  Route::resource('services','ServicesController');

});


Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::get('/payments/customer/{customerId}',[PaymentController::class, 'showCustomerPayments'])->middleware('restrictRole:admin,financial');
  Route::resource('payments','PaymentController')->middleware('restrictRole:admin,financial');

});


Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::get('/big-payments/customer/{customerId}',[BigPaymentController::class, 'showCustomerPayments'])->middleware('restrictRole:admin,financial');
  Route::resource('big-payments','BigPaymentController')->middleware('restrictRole:admin,financial');

});


Route::get('big-customer-bill/{id}', [BigCustomerBillController::class, 'show']);

Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::resource('big-customer-bill','BigCustomerBillController')->middleware('restrictRole:admin,financial');
  Route::get('/big-customer-bill/customer/{customerId}',[BigCustomerBillController::class, 'showCustomerBills'])->middleware('restrictRole:admin,financial');

});

Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::resource('big-customer-bill-item','BigCustomerBillItemController')->middleware('restrictRole:admin,financial');

});



Route::get('/allBills', [BillController::class, 'getAllBill']);
Route::post('bills/create', [BillController::class, 'createBill']);
Route::get('bill/{id}', [BillController::class, 'getOneBill']);
Route::get('bill/edit/{id}', [BillController::class, 'edit']);
Route::get('bill/delete/{id}', [BillController::class, 'deleteInv']);
Route::get('billCount', [BillController::class, 'getTotalBill']);
Route::get('bill/show_bill/{id}', [BillController::class, 'show_billing']);
Route::post('bill/update/{id}', [BillController::class, 'update__billing']);
Route::get('bill/details/{id}', [productQuantitiesController::class,'getItemsById']);
Route::get('bill/customers/{id}', [BillController::class, 'getCustomerBillById']);

// Special care should be given to the next route
Route::delete('bill/delete/{id}',[productQuantitiesController::class, 'deleteBill']);


Route::post('category/create', [CategoryController::class, 'createCategory']);
Route::get('category', [CategoryController::class, 'getAllCategory']);
Route::get('category/{id}', [CategoryController::class,'getCategoryId']);
Route::put('category/update/{id}',[CategoryController::class, 'updateCategory']);
Route::get('category/delete/{id}',[CategoryController::class, 'deleteCategory']);


Route::group(['middleware'=>['auth:sanctum']],function () {

Route::post('product/create', [ProductController::class, 'addProduct'])->middleware('restrictRole:admin,financial');
Route::get('product', [ProductController::class, 'getAllProduct'])->middleware('restrictRole:admin,financial');
Route::get('product/{id}', [ProductController::class, 'getProductById'])->middleware('restrictRole:admin,financial');
Route::get('product/delete/{id}', [ProductController::class, 'deleteService'])->middleware('restrictRole:admin,financial');
Route::put('product/update/{id}', [ProductController::class, 'updateProduct'])->middleware('restrictRole:admin,financial');
//Route::get('product/count', [ProductController, 'getProductCount']);

});


  Route::get('customers',[CustomerController::class,'getAll']);
  Route::put('customers/status/{id}', [CustomerController::class, 'statusUpdate']);
  Route::get('customers/{id}',[CustomerController::class,'getOne']);
  Route::post('customers/create', [CustomerController::class,'create']);
  Route::put('customers/update/{id}', [CustomerController::class,'updateCustomer']);
  Route::delete('customers/delete/{id}', [CustomerController::class,'deleteCustomers']);
  Route::get('customersCount', [CustomerController::class, 'getTotalCustomersCount']);


Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::get('big-customers',[BigCustomerController::class,'getAll']);
  Route::put('big-customers/status/{id}', [BigCustomerController::class, 'statusUpdate']);
  Route::get('big-customers/{id}',[BigCustomerController::class,'getOne']);
  Route::post('big-customers/create', [BigCustomerController::class,'create']);
  Route::put('big-customers/update/{id}', [BigCustomerController::class,'updateCustomer']);
  Route::delete('big-customers/delete/{id}', [BigCustomerController::class,'deleteCustomers']);
  Route::get('big-customersCount', [BigCustomerController::class, 'getTotalCustomersCount']);

});


Route::group(['middleware'=>['auth:sanctum']],function () {

  Route::get('sites/customer/{id}',[SitesController::class, 'getSitesByCustomersId'])->middleware('restrictRole:admin,financial');
  Route::resource('sites','SitesController')->middleware('restrictRole:admin,financial');

});

Route::group(['middleware'=>['auth:sanctum']],function () {
  Route::get('site-items/site/{id}',[SiteItemController::class, 'getSiteItemsBySiteId'])->middleware('restrictRole:admin,financial');
  Route::resource('site-items','SiteItemController')->middleware('restrictRole:admin,financial');

});



