<?php


namespace App\Repositories\Interfaces;

interface CrudRepositoryInterface
{
	public function all($with = array(), $pagination = null);
	public function getById($id, $with = array());
	public function create(array $data);
	public function update($id, array $data);
	public function delete($id);
	public function selectWhere(array $where, $with = array(), $pagination = null);
}
