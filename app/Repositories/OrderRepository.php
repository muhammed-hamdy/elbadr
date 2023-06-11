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

    public function getReport($user_id)
    {
        $orders = $this->builder::where(function($query) use($user_id) {
            return $query->where('user_id', $user_id)
            ->orWhere('receiver_id', $user_id);
        })
        ->get();
        return $orders;
    }


}
