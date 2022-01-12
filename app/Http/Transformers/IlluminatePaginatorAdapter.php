<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\PaginatorInterface;

/**
 * A paginator adapter for illuminate/pagination.
 */
class IlluminatePaginatorAdapter implements PaginatorInterface
{
    /**
     * The paginator instance.
     *
     * @var LengthAwarePaginator
     */
    protected $paginator;

    /**
     * Create a new illuminate pagination adapter.
     *
     * @return void
     */
    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->paginator->currentPage();
    }

    /**
     * Get the last page.
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->paginator->lastPage();
    }

    /**
     * Get the total.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->paginator->total();
    }

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->paginator->count();
    }

    /**
     * Get the number per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->paginator->perPage();
    }

    /**
     * Get the url for the given page.
     *
     * @param int $page
     * @return string
     */
    public function getUrl($page)
    {
        return urldecode($this->paginator->url($page));
    }

    /**
     * Get the paginator instance.
     *
     * @return LengthAwarePaginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }
}
