<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Product\ProductResource;
use App\Http\Requests\Seller\SellerStoreRequest;
use App\Http\Requests\Seller\SellerUpdateRequest;
use App\Http\Resources\Product\ProductCollection;

class SellerProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return ProductCollection::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerStoreRequest $request, User $seller)
    {
        // ! User $seller A user object has to be of a Seller type 
        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');    // ? Path is calculated through the FILESYSTEM drivers described inside the config/filesystem AND a random name is assigned to the uploaded file.
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(SellerUpdateRequest $request, Seller $seller, Product $product)
    {
        if ($seller->id !== $product->seller_id) {
            // TODO: Use HttpException here
            return response([
                "message" => "Forbidden.",
                "errors" => [
                    "seller" => [
                        "The product can only be updated by the original Seller."
                    ]
                ]
            ], Response::HTTP_FORBIDDEN);
        }

        $product->fill($request->only([
            'name', 'description', 'quantity'
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() == 0) {
                // TODO: Use HttpException here
                return response([
                    'message' => 'Update conflict.',
                    'errors' => [
                        'category' => [
                            'An available product must belong to atleast one Category.'
                        ]
                    ]
                ], Response::HTTP_CONFLICT);
            }
        }

        if($request->has('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store(''); 
        }

        $product->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        if ($seller->id !== $product->seller_id) {
            // TODO: Use HttpException here
            return response([
                "message" => "Forbidden.",
                "errors" => [
                    "seller" => [
                        "The product can only be deleted by the original Seller."
                    ]
                ]
            ], Response::HTTP_FORBIDDEN);
        }

        $product->delete();
        Storage::delete($product->image);   // TODO: This deleted the image even if the product is Soft-deleted. MAKE SURE NOT TO REMOVE THE IMAGE when the resource is deleted

        return new ProductResource($product);
    }
}
