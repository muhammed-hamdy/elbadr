<?php
namespace App\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductRepository extends CrudRepository implements ProductRepositoryInterface
{

    public function __construct()
    {
		parent::__construct(Product::class);
    }

    public function getActive()
    {
        return $this->selectWhere(['status' => 1]);
    }

    public function getActiveById($id)
    {
        return $this->getActive()->where('id', $id)->first();

    }

}
