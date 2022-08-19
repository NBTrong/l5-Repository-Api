<?php

namespace App\Http\Controllers;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\BlogCreateRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Repositories\BlogRepository;
use Illuminate\Http\Request;

/**
 * Class BlogsController.
 *
 * @package namespace App\Http\Controllers;
 */
class BlogsController extends Controller
{
    /**
     * @var BlogRepository
     */
    protected $repository;

    /**
     * @var BlogValidator
     */
    protected $validator;

    /**
     * BlogsController constructor.
     *
     * @param BlogRepository $repository
     * @param BlogValidator $validator
     */
    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->repository->resetCriteria();

        $filter = $request->all();
        $blogs = $this->repository->list($filter);

        return response()->json([
            'message' => 'Blogs retrieved successfully',
            'blogs' => $blogs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(BlogCreateRequest $request)
    {
        try {
            $blog = $this->repository->create($request->all());

            $response = [
                'message' => 'Blog created.',
                'data'    => $blog,
            ];

            return response()->json($response);
        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = $this->repository->find($id);

        return response()->json([
            'message' => 'Blog retrieved successfully',
            'data' => $blog,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = $this->repository->find($id);

        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(BlogUpdateRequest $request, $id)
    {
        try {
            $blog = $this->repository->update($request->all(), $id);
            $response = [
                'message' => 'Blog updated.',
                'data'    => $blog,
            ];

            return response()->json($response);
        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        return response()->json([
            'message' => 'Blog deleted.',
            'deleted' => $deleted,
        ]);
    }
}
