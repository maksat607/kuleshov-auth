<?php

namespace Maksatsaparbekov\KuleshovAuth\Filters;

class ChatFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply all filters to the query.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if ($filter === 'date' && method_exists($this, $value)) {
                $this->$value();
            }

            if (method_exists($this, $filter) && !is_null($value)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Get all filters from the request.
     *
     * @return array
     */
    protected function filters()
    {
        return $this->request->all();
    }

    // General Filters for Group Table

    public function name($value)
    {
        $this->builder->where('name', 'like', '%' . $value . '%');
    }

    public function type($value)
    {
        $this->builder->where('type', $value);
    }

    public function code($value)
    {
        $this->builder->where('code', $value);
    }

    public function status($value)
    {
        $this->builder->where('status', $value);
    }



    // Date filters for the pivot table (delivered messages) + Eager Loading


}
