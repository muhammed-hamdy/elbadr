<?php


namespace App\Repositories\Interfaces;

interface WalletRepositoryInterface
{
	public function createBalanceDeposit($user_id, $amount, $status = 0);
    public function createBalanceWithDraw($user_id, $status = 0);
    public function createBalanceTransfer($user_id, $receiver_id, $amount, $status = 0);
    public function confirmBalanceTransaction($transaction_id);

}
