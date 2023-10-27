<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryContract
{
    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    public function paginate(int $pageNumber = 5)
    {
        return $this->model->latest()->paginate($pageNumber);
    }

    public function getByAttribute(string $field, string|null $attribute)
    {
        return $this->model->where($field, $attribute)->get();
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function updateById(array $data, int $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete(int $id)
    {
        return $this->model->where('id', $id)
            ->delete();
    }
}