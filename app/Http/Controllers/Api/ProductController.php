<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Products\PurchaseRequest;
use App\Http\Resources\OrderResource;

class ProductController extends Controller
{

    private $repository;

    /**
    *   define repository
    * @param ProductRepository $repository
    */

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $user = auth()->user();
        if($user->hasAnyPermission(['view all products', 'view products'])) {
            return response()->json([], 403);
        }
        $products = $user->hasPermissionTo('view all products') ? $this->repository->all() : $this->repository->getActive();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $user = auth()->user();
        if($user->hasAnyPermission(['view all products', 'view products'])) {
            return response()->json([], 403);
        }
        $product = $user->hasPermissionTo('view all products') ? $this->repository->getById($id) : $this->repository->getActiveById($id);


        return $product ? new ProductResource($product) : response()->json(['message' => 'not found'], 404);
    }

    public function purchase(PurchaseRequest $request)
    {
        $user = auth()->user();
        $product = $this->repository->getActiveById($request->product_id);
        // check is product exists
        if(!$product) {
            return response()->json(['message' => 'not found'], 404);
        }
        // check for stock availability
        if($product->stock_amount <= 0) {
            return response()->json(['message' => "Out of stock"], 404);
        }

        // check for enough balance
        if($user->balance < $product->price) {
            return response()->json(['message' => "you don't have enough balance"], 400);
        }

        $orderRepo = new OrderRepository();
        $order = $orderRepo->create([
            'type' => 'withdraw',
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => $product->price,
            'status' => 1,
        ]);

        return new OrderResource($order);

    }

    public function myProducts()
    {
        $user = auth()->user();
        $products = $user->products;
        return ProductResource::collection($products);
    }
}
