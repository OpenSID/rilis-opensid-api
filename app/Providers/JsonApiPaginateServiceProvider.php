<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerMacro();
    }

    protected function registerMacro()
    {
        Builder::macro(config('json-api-paginate.method_name'), function (?int $maxResults = null, ?int $defaultSize = null) {
            $maxResults = $maxResults ?? config('json-api-paginate.max_results');
            $defaultSize = $defaultSize ?? config('json-api-paginate.default_size');
            $numberParameter = config('json-api-paginate.number_parameter');
            $sizeParameter = config('json-api-paginate.size_parameter');
            $paginationParameter = config('json-api-paginate.pagination_parameter');
            $paginationMethod = config('json-api-paginate.use_simple_pagination') ? 'simplePaginate' : 'paginate';

            $size = (int) Request::input($paginationParameter . '.' . $sizeParameter, $defaultSize);

            $size = $size > $maxResults ? $maxResults : $size;

            $paginator = $this
                ->{$paginationMethod}($size, ['*'], $paginationParameter . '.' . $numberParameter)
                ->setPageName($paginationParameter . '[' . $numberParameter . ']')
                ->appends(Arr::except(Request::input(), $paginationParameter . '.' . $numberParameter));

            if (! is_null(config('json-api-paginate.base_url'))) {
                $paginator->setPath(config('json-api-paginate.base_url'));
            }

            return $paginator;
        });
    }
}
