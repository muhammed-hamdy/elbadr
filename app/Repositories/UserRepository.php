<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserRepository extends CrudRepository implements UserRepositoryInterface
{

    public function __construct()
    {
		parent::__construct(User::class);
    }

	public function hashPassword($password)
    {
        return bcrypt($password);
    }

}
