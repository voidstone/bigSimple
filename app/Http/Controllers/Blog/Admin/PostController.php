<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogBostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Repositories\BlogCategoryRepositories;
use App\Repositories\BlogPostRepositories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\BlogPost;

class PostController extends BaseController
{
    /**
     * @var BlogPostRepositories
     */
    private $blogPostRepository;


    /**
     * @var BlogCategoryRepositories
     */
    private $blogCategoryRepository;


    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepositories::class);
        $this->blogCategoryRepository = app(BlogCategoryRepositories::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new BlogPost();
        $categoryList = $this->blogPostRepository->getForComboBox();

        return view('blog.admin.posts.edit',
                compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogBostCreateRequest $request)
    {
        $data = $request->input();
        $item = (new BlogPost())->create($data);

        if($item) {
            return redirect()->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }

        $categoryList = $this->blogPostRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()
                ->withErrors(["msg" => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();

//        if(empty($data['slug'])) {
//            $data['slug'] = \Str::slug($data['title']);
//        }
//        if(empty($item->published_at) && $data['is_published']) {
//            $data['published_at'] = Carbon::now();
//        }

        $result = $item->update($data);

        if($result) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd(__Method__,  $id , \request()->all());
    }
}
