<?php

namespace App\Http\Traits;

trait QueryBuilderTrait
{
    public function getCompiledQueryWithBindings($query)
    {
        // Get the compiled SQL query
        $compiledQuery = $query->toSql();

        // Get the bindings
        $bindings = $query->getBindings();

        // Combine the query and bindings
        $compiledQueryWithBindings = vsprintf(str_replace('?', '%s', $compiledQuery), $bindings);
        $compiledQueryWithBindings = str_replace('`', '', $compiledQueryWithBindings);

        return $compiledQueryWithBindings;
    }
}
