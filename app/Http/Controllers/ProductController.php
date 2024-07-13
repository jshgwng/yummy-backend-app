<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function save(Request $request)
    {
        try {
            $request->validate(['name' => 'required', 'price' => 'required', 'quantity' => 'required']);

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->imageUrl = $request->imageUrl;
            $product->save();

            return response(
                [
                    'status' => true,
                    'message' => 'Product added.',
                    'data' => $product
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'message' => 'An error occured.',
                    'data' => $th->getMessage()
                ]
            );
        }
    }

    public function allProducts(Request $request)
    {
        $all_products = Product::all();

        return response(
            [
                'status' => true,
                'message' => 'Products fetched.',
                'data' => $all_products
            ]
        );
    }

    public function getProduct(Request $request, $id)
    {
        $product = Product::find($id);

        return response(
            [
                'status' => true,
                'message' => 'Product fetched.',
                'data' => $product
            ]
        );
    }

    public function update(Request $request, $id)
    {
        try {
            // $request->validate(['name' => 'required', 'price' => 'required', 'quantity' => 'required']);

            $product =  Product::find($id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->imageUrl = $request->imageUrl;
            $product->save();

            return response(
                [
                    'status' => true,
                    'message' => 'success.',
                    'data' => $product
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'message' => 'An error occured.',
                    'data' => $th->getMessage()
                ]
            );
        }
    }

    public function updateInventory(Request $request)
    {
        try {
            $product = Product::find($request->id);
            if ($request->quantity > 0) {
                $product->quantity += $request->quantity;
                $product->save();
            }

            if ($request->quantity < 0) {
                if ($product->quantity > 0 && ($product->quantity - $request->quantity) > 0) {
                    $product->quantity += $request->quantity;
                    $product->save();
                }
            }
            return response(
                [
                    'status' => true,
                    'message' => 'success.',
                    'data' => $product
                ]
            );
        } catch (\Throwable $th) {
            return response(
                [
                    'status' => false,
                    'message' => 'An error occured.',
                    'data' => $th->getMessage()
                ]
            );
        }
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);
        $product->delete();

        return response(
            [
                'status' => true,
                'message' => 'deleted.'
            ]
        );
    }
}
