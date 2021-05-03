<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::latest()->get();
        // $product_count = Product::get()->count();
        $product_count = DB::table('products')->count();

        $response = [
            'message' => 'Product List',
            'count' => $product_count,
            'data' => $product,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|unique:products,name',
           'price' => 'required|numeric',
           'stock' => 'required|nullable',
           'description' => 'max:255',
           'expired_date' => 'required|date'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $product = Product::create($request->all());
            $response = [
                'message' => 'Product Created',
                'data' => $product,
            ];

            return response()->json($response, Response
            ::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'message' =>"Failed". $e->errorInfo,
                ]
                );
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,' . $id,
            'price' => 'required|numeric',
            'stock' => 'required|nullable',
            'description' => 'max:255',
            'expired_date' => 'required|date'
         ]);

         if($validator->fails())
         {
             return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
         }

         try {
            $product->update($request->all());

            $response = [
                'message' => 'Product Updated',
                'data' => $product,
            ];

            return response()->json($response, Response::HTTP_OK);
         } catch (QueryException $e) {
            return response()->json([
               'message' => "Failed". $e->errorInfo,
            ]);
         }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $response = [
            'data' => $product
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

         try {
            $product->delete();

            $response = [
                'message' => 'Product Deleted',
            ];

            return response()->json($response, Response::HTTP_OK);
         } catch (QueryException $e) {
            return response()->json([
               'message' => "Failed". $e->errorInfo,
            ]);
         }
    }
}
