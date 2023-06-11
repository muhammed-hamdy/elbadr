<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Transaction;
use App\repositories\TransactionRepository;
use App\repositories\ProductRepository;
use App\repositories\UserRepository;
use App\Services\TransactionService;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $transactionType = in_array($order->type, ['withdraw', 'transfer']) ? 'withdraw' : 'deposit';

        $userRepo = new UserRepository;
        $sender = $userRepo->getById($order->user_id);
        $balanceBefore = $sender->balance;
        $balanceAfter = $transactionType == 'withdraw' ? $balanceBefore - $order->amount : $balanceBefore + $order->amount;
        $TransactionRepository = new TransactionRepository;
        $TransactionRepository->create([
            'user_id' => $order->user_id,
            'type' => $transactionType,
            'amount' => $order->amount,
            'order_id' => $order->id,
            'status' => 1,
            'confirmed' => 0,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        if($order->type == 'transfer') {
            $receiver = $userRepo->getById($order->receiver_id);
            $balanceBefore = $receiver->balance;
            $balanceAfter = $balanceBefore + $order->amount;
            $TransactionRepository->create([
                'user_id' => $order->receiver_id,
                'type' => 'deposit',
                'amount' => $order->amount,
                'order_id' => $order->id,
                'status' => 1,
                'confirmed' => 0,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ]);
        }

        if($order->status == 1) {
            TransactionService::confirmTransaction($order);
        }

        if($order->product_id != null) {
            $productRepo = new ProductRepository();
            $product = $productRepo->getById($order->product_id);
            $product->stock_amount -= 1;
            $product->save();
        }

        $order->added_by = auth()->id();
        $order->save();
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if($order->status == 1) {
            TransactionService::confirmTransaction($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        if($order->product_id != 0) {
            $productRepo = new ProductRepository();
            $product = $productRepo->getById($order->product_id);
            $product->stock_amount += 1;
            $product->save();
        }
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
