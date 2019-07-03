<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;
class Repository
{
    protected $model;
    
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }
    
    public function insertMultipleRows(array $records)
    {
        return $this->model->insert($records);
    }
    
    public function find($id)
    {
        return $this->model->find($id);
    }
    
    public function update($id, array $attributes)
    {
        $currentModel = $this->find($id);
        return $currentModel->update($attributes);
    }
    
    public function delete($id)
    {
        $currentModel = $this->find($id);
        return $currentModel->delete();
    }
    
    public function isFieldValueExists(array $condition)
    {
        return $this->model->where($condition)->exists();
    }
    
    public function updateOrCreate(array $whereAttributes, array $insertAttributes)
    {
        return $this->model->updateOrCreate($whereAttributes, $insertAttributes);
    }
    
    public function deleteBy(array $attributes)
    {
        return $this->model->where($attributes)->delete();
    }
}
