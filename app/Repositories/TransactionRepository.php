<?php
namespace App\Repositories;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class TransactionRepository extends CrudRepository implements TransactionRepositoryInterface
{

    public function __construct()
    {
		parent::__construct(Transaction::class);
    }



}
