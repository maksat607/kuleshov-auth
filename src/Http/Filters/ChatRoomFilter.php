<?php
namespace Maksatsaparbekov\KuleshovAuth\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Maksatsaparbekov\KuleshovAuth\Models\ChatRoom;
use Illuminate\Support\Facades\Schema;
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

        // If not explicitly requesting only unread, default to ordering by unread and date
        if (!$this->request->has('unread_only')) {
            $this->builder->orderByUnreadAndDate();
        }

        // Sort by priority if the priority column exists
        $this->sortByPriority();

        return $this->builder;
    }

    /**
     * Sort by priority after all other filters
     */
    protected function sortByPriority()
    {
        // Check if the priority column exists in the table
        if (Schema::hasColumn('chat_rooms', 'priority')) {
            $priorityCount = ChatRoom::where('priority', '>', 0)->count();

            \Log::info('Rooms with priority > 0: ' . $priorityCount);
            $this->builder->orderBy('priority', 'asc');
        }
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
            $this->builder->orderByUnreadAndDate();
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