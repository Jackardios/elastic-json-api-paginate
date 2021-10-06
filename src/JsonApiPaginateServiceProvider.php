<?php

namespace Jackardios\ElasticJsonApiPaginate;

use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerMacros();
    }

    protected function registerMacros(): void
    {
        SearchRequestBuilder::macro(config('json-api-paginate.method_name', 'jsonPaginate'), function (?int $maxResults = null, ?int $defaultSize = null): AbstractPaginator {
            $maxResults = $maxResults ?? config('json-api-paginate.max_results', 30);
            $defaultSize = $defaultSize ?? config('json-api-paginate.default_size', 30);
            $numberParameter = config('json-api-paginate.number_parameter', 'number');
            $sizeParameter = config('json-api-paginate.size_parameter', 'size');
            $paginationParameter = config('json-api-paginate.pagination_parameter', 'page');

            $size = (int) request()->input($paginationParameter.'.'.$sizeParameter, $defaultSize);

            $size = $size > $maxResults ? $maxResults : $size;

            $paginator = $this
                ->paginate($size, $paginationParameter.'.'.$numberParameter)
                ->setPageName($paginationParameter.'['.$numberParameter.']')
                ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$numberParameter));

            if (! is_null(config('json-api-paginate.base_url'))) {
                $paginator->setPath(config('json-api-paginate.base_url'));
            }

            return $paginator;
        });
    }
}
