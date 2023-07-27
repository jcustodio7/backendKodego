<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Http\Resources\ProductResources;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = ProductModel::all();
        $response = [
            'code' => 200, 
            'message' => 'Successfuly retrieval of services!',
            'products' => ProductResources::collection($products)];

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // $input=$request->all();
        // $product = ProductModel::create($input);
        // $response = [
        //     'code' => 200, 
        //     'message' => 'Product successfully created!',
        //     'service' => new ProductResources($product)];

        // return $response;    

        $product = new ProductModel;
        $product->product_name=$request->input('product_name');
        $product->product_price=$request->input('product_price');
        $product->product_description=$request->input('product_description');
        $product->file_path=$request->file('file_path')->store('products');
        $product->save();
        $response = [
            'message' => 'Product successfully added',
            'product' => new ProductResources($product)
        ];
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $product = ProductModel::findOrFail($id);
        $response = [
            'code' => 200, 
            'message' => 'Product successfully shown!',
            'products' => new ProductResources($product)];

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // $input=$request->all();
        $product = ProductModel::findOrFail($id);
        $product->product_name=$request->input('product_name');
        $product->product_price=$request->input('product_price');
        $product->product_description=$request->input('product_description');
        if ($request->file('file_path')) {
            $product->file_path=$request->file('file_path')->store('products');

        }
        $product->save();
        $response = [
            'code' => 200, 
            'message' => 'Product successfully updated!',
            'products' => new ProductResources($product)];

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = ProductModel::findOrFail($id);
        $product->delete();
        $response = [
            'code' => 200, 
            'message' => 'Product successfully deleted!',
            'products' => new ProductResources($product)];

        return $response;
    }
}

