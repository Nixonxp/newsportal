<?php

namespace App\Repositories;

use App\Models\Post as Model;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Filters\NewsPostFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Elasticsearch\Client;

class PostRepository extends CoreRepository implements PostRepositoryInterface
{
    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    private $defaultColumns = [
        'id',
        'title',
        'slug',
        'image',
        'source_image',
        'partner_news',
        'excerpt',
        'published_at',
        'category_id'
    ];

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getAllWithPaginate(int $perPage = 100): Collection|LengthAwarePaginator
    {
        $columns = [
            'id',
            'title',
            'slug',
            'image',
            'source_image',
            'partner_news',
            'excerpt',
            'is_published',
            'created_at',
            'updated_at',
            'published_at',
            'user_id',
            'category_id'
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->orderBy('id', 'asc')
            ->with('category:name')
            ->paginate($perPage);

        return $result;
    }

    public function getPostWithPaginateCategory(int $perPage = 100, int $category = null): Collection|LengthAwarePaginator
    {
        $query = $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('id', 'desc')
            ->with('category')
            ->nowPublished();

        if (isset($category)) {
            $query->category($category);
        }

        return $query->paginate($perPage);
    }

    public function getNewsPostsWithFilterPaginate($request, ?int $perPage = null): Collection|LengthAwarePaginator
    {
        $filters = (new NewsPostFilters($request));
        return $this
            ->startConditions()
            ->filter($filters)
            ->paginate($perPage);
    }

    public function getLastNewsByCategoryId(int $id, int $limit = 100): Collection
    {
        return $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('id', 'desc')
            ->with('category')
            ->category($id)
            ->limit($limit)
            ->get();
    }

    public function getLastNewsWithPaginateByCategoryId(int $id, int $perPage = 100): Collection|LengthAwarePaginator
    {
        return $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('id', 'desc')
            ->with('category')
            ->category($id)
            ->paginate($perPage);
    }

    public function getLastMainSliderPosts(int $category = null, int $limit = 10): Collection
    {
        $query = $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('id', 'desc')
            ->with('category')
            ->nowPublished()
            ->limit($limit);

        if (isset($category)) {
            $query->category($category);
        }

        return $query->get();
    }

    public function getLastPublishedNews(int $category = null, int $limit = 20): Collection
    {
        $query = $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('published_at', 'desc')
            ->with('category')
            ->nowPublished()
            ->limit($limit);

        if (isset($category)) {
            $query->category($category);
        }

        return $query->get();
    }

    public function getLastPopularPublishedNews(int $category = null, int $limit = 20): Collection
    {
        $query = $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->orderBy('published_at', 'desc')
            ->with('category')
            ->nowPublished()
            ->popular()
            ->limit($limit);

        if (isset($category)) {
            $query->category($category);
        }

        return $query->get();
    }

    public function getPublishedNewsOverPeriod(Carbon $period, int $limit = 20, int $category = null, $getWithBuilder = false): Collection|Builder
    {
        $query = $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->where('published_at', '>', $period)
            ->with('category')
            ->nowPublished()
            ->inRandomOrder()
            ->limit($limit);

        if (isset($category)) {
            $query->category($category);
        }

        if ($getWithBuilder) {
            return $query;
        }

        return $query->get();
    }

    public function getPopularRandomNewsOverPeriod(Carbon $period, int $limit = 20, int $category = null): Collection
    {
        $query = $this
            ->startConditions()
            ->select($this->defaultColumns)
            ->where('published_at', '>', $period)
            ->with('category')
            ->nowPublished()
            ->popular()
            ->inRandomOrder()
            ->limit($limit);

        if (isset($category)) {
            $query->category($category);
        }

        return $query->get();
    }

    public function search(?string $query = ''): \Illuminate\Support\Collection
    {
        $items = $this->searchOnElasticsearch($query);
        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new Model;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title^5', 'content'],
                        'query' => $query,
                    ],
                ],
            ],
        ]);
        return $items;
    }
    private function buildCollection(array $items): \Illuminate\Support\Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return Model::select('*')
            ->whereIn('id', $ids)
            ->limit(config('services.search.max_result', 50))
            ->get()
            ->sortBy(fn($post) => array_search($post->getKey(), $ids));
    }
}
