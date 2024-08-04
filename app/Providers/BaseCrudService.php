<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;

class BaseCrudService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    function getAllPaginate()
    {
        return $this->model->paginate(10);
    }

    function getAll()
    {
        return $this->model->all();
    }

    function getById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->find($id);
        return $record ? $record->update($data) : null;
    }

    public function delete($id)
    {
        $record = $this->model->find($id);
        return $record ? $record->delete() : null;
    }
}
