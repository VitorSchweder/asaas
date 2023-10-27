<?php
namespace App\Repositories;

interface BaseRepositoryContract
{
    public function getById(int $id);
    public function paginate(int $pageNumber);
    public function getByAttribute(string $field, string|null $attribute);
    public function store(array $data);
    public function updateById(array $data, int $id);
    public function delete(int $id);
}