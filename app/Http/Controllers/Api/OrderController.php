<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{
    private $repository;

    /**
    *   define repository
    * @param OrderRepository $repository
    */

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $user = auth()->user();
        if(!$user->hasPermissionTo('view all orders')) {
            return response()->json([
                'message' => 'unauthorized request'
            ], 403);
        }

        $orders = $this->repository->all(['product', 'user']);

        return OrderResource::collection($orders);
    }

    public function myOrders()
    {
        $user = auth()->user();
        $orders = $user->orders;

        return OrderResource::collection($orders);
    }
}
