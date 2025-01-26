<?php
namespace Maksatsaparbekov\KuleshovAuth\Http\Filters;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;

class ChatRoomFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter) && !is_null($value)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    protected function filters()
    {
        return $this->request->all();
    }

    protected function unread_only($value)
    {
        if ($this->request->boolean('unread_only')) {
            $this->builder->orderByLatestUnreadMessage();
        } else {
            $this->builder->orderByLatestMessage();
        }
    }

    protected function sort_by_unread($value)
    {
        if ($this->request->boolean('sort_by_unread')) {
            $this->builder->orderByUnreadAndDate();
        }
    }

    protected function per_page($value)
    {
        return $this->builder;
    }

    protected function page($value)
    {
        return $this->builder;
    }
}
