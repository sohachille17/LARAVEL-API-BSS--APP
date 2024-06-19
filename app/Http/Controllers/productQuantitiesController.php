<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ProductItems;
use App\Model\Bills;

class productQuantitiesController extends Controller
{
    public function createItems(Request $request, ProductItems $items)
    {

        //scope for items
        $items = new ProductItems;
        $items->bill_id = $request->bill_id;
        $items->item_name = $request->item_name;
        $items->unit_cost = $request->unit_cost;
        $items->quantity = $request->quantity;
        $items->line_total = $request->unit_cost * $request->quantity;
        $items->save();
      //  if($items->bills->discount_type == 'Amount'){
            Bills::where('id', $items->bill_id)->update(array(
                'amount'=>$items->bills->product_quantites->sum('line_total') * ((100+$items->bills->tvaAmount)/ 100),
                'balance' => $items->bills->product_quantites->sum('line_total') * ((100+$items->bills->tvaAmount)/ 100)
                - $items->bills->discount
                - $items->bills->payment->sum('payment_amount')
               
            ));
       /* }elseif($product->invoice->discount_type == 'Percent'){
            Bills::where('id',$items->bill_id)->update(array(
                'amount'=>  $items->bills->product_quantites->sum('line_total') * ((100+$items->bills->tvaAmount)/ 100),
                'balance'=> $items->bills->product_quantites->sum('line_total') * ((100+$items->bills->tvaAmount)/ 100)+
                    ( $items->bills->product_quantites->sum('line_total')
                        *  (100+$items->bills->tvaAmount) / 100)
                    -$items->bills->product_quantites->sum('line_total')
                    
            ));
        }*/

        return response()->json([
            "items" => $items
        ]);

    }
    public function getItemsById($id)
    {
        $items = ProductItems::select('product_id','product_name','unit_price','quantity','total')->where('bills_id', $id)->get();

        return response($items);

    }

    public function deleteBill($id){
        $invoiceitem = ProductItems::findOrFail($id);
        $invoiceitem->delete();
    }
}
