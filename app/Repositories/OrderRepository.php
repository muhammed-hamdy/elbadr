<?php
namespace App\Repositories;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderRepository extends CrudRepository implements OrderRepositoryInterface
{

    public function __construct()
    {
		parent::__construct(Order::class);
    }

}
