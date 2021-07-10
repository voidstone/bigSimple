<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/*
 * Репозиторий для работы с сущностью.
 * Может выдавать набор данных.
 * Не может создавать/изменять сущности.
 */

abstract class CoreRepository
{

    /*
     * @var Model
     */

    protected $model;


    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }


    protected function startConditions() {
        return clone $this->model;
    }

}
