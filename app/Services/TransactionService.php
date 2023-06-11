<?php
namespace App\Services;

use App\Repositories\WalletRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;

class TransactionService
{
    public static function confirmTransaction($order)
    {
        $transactions = $order->transactions->where('confirmed', 0);
        $walletRepo = new WalletRepository;

        foreach($transactions as $transaction) {

            $wallet = $walletRepo->firstOrCreate($transaction->user_id);

            if($transaction->type == 'deposit') {
                $wallet->amount += $transaction->amount;
            } else {
                $wallet->amount -= $transaction->amount;
            }
            $wallet->save();
            $transaction->confirmed = 1;
            $transaction->save();
        }
    }

    public static function createBalanceRequest($user_id, $amount)
    {
        $orderRepo = new OrderRepository;
        $order = $orderRepo->create([
            'user_id' => $user_id,
            'type' => 'deposit',
            'amount' => $amount,
            'status' => 0,
        ]);

        return $order;
    }

    public static function transferBalance($user_id, $receiver_id, $amount)
    {
        $orderRepo = new OrderRepository;
        $order = $orderRepo->create([
            'user_id' => $user_id,
            'receiver_id' => $receiver_id,
            'type' => 'transfer',
            'amount' => $amount,
            'status' => 1,

        ]);

        return $order;
    }
}
