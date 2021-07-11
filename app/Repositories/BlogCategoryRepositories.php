<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;


class BlogCategoryRepositories extends CoreRepository
{

    /*
     * @return string
     *
     *
     */

    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * Получить модель для редактирования в админке
     *
     * $param int $id
     *
     * @return Model
     */


    public function getEdit($id): Model
    {
        return $this->startConditions()->find($id);
    }

    /*
     *
     * Получить список категорий для вывода в выпадающем списке
     *
     * @return Collection
     */

    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) as id_title',
        ]);

//        $result[] = $this->startConditions()->all();
//
//        $result[] = $this
//            ->startConditions()
//            ->select('blog_categories.*',
//            \DB::raw('CONCAT (id, ". ", title) as title'))
//            ->toBase()
//            ->get();

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        return $result;

    }


    /**
     * @param null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null) {
        $columns = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->paginate($perPage);

        return $result;

    }


}
