<?php
namespace App\Repositories;

use App\Repositories\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CrudRepository implements CrudRepositoryInterface
{

    protected $builder;

	public function __construct($builder)
	{
		$this->builder = $builder;
	}

    public function all($with = array(), $pagination = null)
    {
        $data = $this->builder::when(!empty($with), function($query) use($with) {
            return $query->with($with);
        });

        return isset($pagination) ? $data->paginate($pagination) : $data->get();
    }

	public function getById($id, $with = array())
    {
        $data = $this->builder::when(!empty($with), function($query) use($with){
            return $query->with($with);
        })
        ->find($id);

        return $data;
    }

	public function create(array $data)
    {
        return $this->builder::create($data);
    }

	public function update($id, array $data)
    {
        $record = $this->getById($id);
        if($record == null) {
            return false;
        }
        $record->update($data);

        return $record;
    }

	public function delete($id)
    {
        try {
			$this->builder::destroy($id);
			return true;
		}
		catch (\Exception $e) {
			return false;
		}
    }

	public function selectWhere(array $where, $with = array(), $pagination = null)
    {
        $data = $this->builder::when(!empty($with), function($query) use($with) {
            return $query->with($with);
        })
        ->when(!empty($where), function($query) use($where) {
            return $query->where($where);
        });

        return isset($pagination) ? $data->paginate($pagination) : $data->get();

    }

}
