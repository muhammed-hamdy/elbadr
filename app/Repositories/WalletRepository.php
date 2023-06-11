<?php
namespace App\Repositories;

use App\Repositories\Interfaces\WalletRepositoryInterface;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;

class WalletRepository extends CrudRepository implements WalletRepositoryInterface
{

    public function __construct()
    {
		parent::__construct(Wallet::class);
    }

    public function firstOrCreate($user_id)
    {
        return $this->builder::firstOrCreate([
            'user_id' => $user_id,
        ]);
    }

    // public function createBalanceDeposit($user_id, $amount,  $status = 0)
    // {
    //     $orderRepository = new OrderRepository;
    //     $order = $orderRepository->create([
    //         'type' => 'recharge_balance',
    //         'user_id' => $user_id,
    //         'status' => $status,
    //         'amount' => $amount
    //     ]);

    //     $transactionRepository = new TransactionRepository;
    //     $transaction = $transactionRepository->create([
    //         'type' => 'deposit',
    //         'user_id' => $user_id,
    //         'status' => $status,
    //         'amount' => $amount,
    //         'order_id' => $order->id,
    //     ]);

    //     return $order;
    // }

    // public function createBalanceTransfer($user_id, $receiver_id, $amount, $status = 0)
    // {
    //     $orderRepository = new OrderRepository;
    //     $order = $orderRepository->create([
    //         'type' => 'recharge_balance',
    //         'user_id' => $user_id,
    //         'receiver_id' => $receiver_id,
    //         'status' => $status,
    //         'amount' => $amount
    //     ]);

    //     $transactionRepository = new TransactionRepository;
    //     $transactionDeposit = $transactionRepository->create([
    //         'type' => 'deposit',
    //         'user_id' => $receiver_id,
    //         'status' => $status,
    //         'amount' => $amount,
    //         'order_id' => $order->id,
    //     ]);

    //     $transactionWithDraw = $transactionRepository->create([
    //         'type' => 'withdraw',
    //         'user_id' => $user_id,
    //         'status' => $status,
    //         'amount' => $amount,
    //         'order_id' => $order->id,
    //     ]);

    //     return $order;
    // }

    // public function createBalanceWithDraw($user_id, $status = 0);
    // public function confirmBalanceTransaction($transaction_id);

}
