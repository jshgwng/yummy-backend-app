<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SaleController extends Controller
{

    public function fetchSales(Request $request)
    {
        $sales = Sale::with(['saleDetails', 'saleDetails.product'])->get();
        return response(
            [
                'status' => true,
                'message' => 'success.',
                'data' => $sales
            ]
        );
    }
    public function processSale(Request $request)
    {
        try {
            $request->validate(['items' => 'required', 'total_amount' => 'required', 'payment_mode' => 'required']);

            $sale = new Sale();
            $sale->total_amount = $request->total_amount;
            $sale->payment_mode = $request->payment_mode;
            $sale->save();

            foreach ($request->items as $key => $value) {
                $sale_detail = new SaleDetail();
                $sale_detail->sale_id = $sale->id;
                $sale_detail->product_id = $value['product_id'];
                $sale_detail->quantity = $value['quantity'];
                $sale_detail->unit_price = $value['unit_price'];
                $sale_detail->save();

                $this->updateInventory($sale_detail->product_id, $sale_detail->quantity);
            }

            $registered_sale = Sale::with(['saleDetails', 'saleDetails.product'])->where('id', $sale->id)->first();


            return response(
                [
                    'status' => true,
                    'message' => 'success.',
                    'data' => $registered_sale
                ]
            );
        } catch (\Throwable $th) {

            return response(
                [
                    'status' => false,
                    'message' => 'failed.',
                    'error' => $th->getMessage()
                ]
            );
        }
    }

    public function updateInventory($product_id, $quantity)
    {
        $product = Product::find($product_id);
        if ($product->quantity > 0 && ($product->quantity - $quantity) > 0) {
            $product->quantity -= $quantity;
            $product->save();
        } else {
            throw new \Exception("Error Processing Request", 1);
        }
    }
}
