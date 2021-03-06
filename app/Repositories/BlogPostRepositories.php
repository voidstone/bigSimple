<?php


namespace App\Repositories;


use App\Models\BlogPost as Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogPostRepositories extends CoreRepository
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
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(): LengthAwarePaginator
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id'
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
//            ->with(['category','user'])
                ->with([
                    'category' => function($query) {
                    $query->select(['id', 'title']);
                    },
                'user:id,name',
            ])
            ->paginate(25);


        return $result;
    }


    /**
     * @param $id
     * @return Model
     */
    public function getEdit($id): Model
    {
        return $this->startConditions()->find($id);
    }


    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) as id_title',
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        return $result;

    }

}
