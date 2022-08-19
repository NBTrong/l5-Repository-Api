<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BlogRepository;
use App\Models\Blog;
use App\Validators\BlogValidator;

/**
 * Class BlogRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class BlogRepositoryEloquent extends BaseRepository implements BlogRepository
{
    protected $fieldSearchable = [
        'title' => 'ilike',
        'slug' => 'ilike',
        'content' => 'ilike',
        'description' => 'ilike',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Blog::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    static public function queryFilter($query, $filter)
    {
        if (isset($filter['title'])) {
            $query = $query->where('title', 'ilike', '%' . $filter['title'] . '%');
        }

        if (isset($filter['slug'])) {
            $query = $query->where('slug', 'ilike', '%' . $filter['slug'] . '%');
        }

        if (isset($filter['content'])) {
            $query = $query->where('content', 'ilike', '%' . $filter['content'] . '%');
        }

        if (isset($filter['description'])) {
            $query = $query->where('description', 'ilike', '%' . $filter['description'] . '%');
        }

        return $query;
    }

    public function list(array $filter = [])
    {
        $this->scopeQuery(function ($query) use ($filter) {
            return self::queryFilter($query, $filter);
        });
        return $this->get();
    }
}
