<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepositories;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{

    /*
     * @var BlogCategoryRepositories
     */

    private $blogCategoryRepositories;

    public function __construct () {
        parent::__construct();
        $this->blogCategoryRepositories = app(BlogCategoryRepositories::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogCategoryRepositories->getAllWithPaginate(5);

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $item = new BlogCategory();
        $categoryList = $this->blogCategoryRepositories->getForComboBox();

        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
//        if (empty($data['slug'])) {
//            $data['slug'] = Str::slug($data['title']);
//        }

//        $item = new BlogCategory($data);
//        $item->save();

        $item = (new BlogCategory())->create($data);

        if ($item) {
            return redirect()->route('blog.admin.categories.edit', [$item->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $item = $this->blogCategoryRepositories->getEdit($id);
        if($item === null) {
            abort(404);
        }
        $categoryList = $this->blogCategoryRepositories->getForComboBox();

        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(BlogCategoryUpdateRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $item = $this->blogCategoryRepositories->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись id[{$id}} не найдена"])
                ->withInput();
        }

        $data = $request->all();

//        if (empty($data['slug'])) {
//            $data['slug'] = Str::slug($data['title']);
//        }
        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }

}
