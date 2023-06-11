<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BalanceRequest;
use App\Http\Requests\TransferBalanceRequest;
use App\Http\Resources\OrderResource;
use App\Services\TransactionService;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class BalanceController extends Controller
{
    public function myBalance()
    {
        $user = auth()->user();

        return response()->json(['balance' => $user->balance]);
    }

    public function requestBalance(BalanceRequest $request)
    {
        $user = auth()->user();
        $order = TransactionService::createBalanceRequest($user->id, $request->amount);

        return new OrderResource($order);
    }

    public function confirmBalance($order_id)
    {
        $authUser = auth()->user();
        if(!$authUser->hasPermissionTo('recharge balance')) {
            return response()->json([
                'message' => 'unauthorized request'
            ], 403);
        }
        $repository = new OrderRepository();
        $order = $repository->getById($order_id, ['transactions']);
        TransactionService::confirmTransaction($order);
        $user = $order->user;

        return response()->json(['balance' => $user->balance]);
    }

    public function transferBalance(TransferBalanceRequest $request)
    {
        $authUser = auth()->user();
        if(!$authUser->hasPermissionTo('transfer balance')) {
            return response()->json([
                'message' => 'unauthorized request'
            ], 403);
        }
        $userRepo = new UserRepository;
        $sender = $userRepo->getById($request->user_id, ['wallet']);
        if($sender->balance < $request->amount) {
            return response()->json([
                'message' => 'balance not enough'
            ], 400);
        }
        $order = TransactionService::transferBalance(
            $request->user_id,
            $request->receiver_id,
            $request->amount,
        );

        return new OrderResource($order);
    }
}
