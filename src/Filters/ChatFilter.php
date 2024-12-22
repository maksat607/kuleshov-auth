<?php

namespace Maksatsaparbekov\KuleshovAuth\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
            // Check if a method exists for the filter and the value is not null
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
    public function date($value)
    {
        if (str_starts_with($value, '-')) {
            $this->builder->with(['messages' => function ($query) {
                $query->latest('created_at'); // Ensure latest message for eager loading
            }])->orderByDesc(
                \DB::raw('(SELECT MAX(created_at) FROM chat_room_messages WHERE chat_room_messages.chat_room_id = chat_rooms.id)')
            );
        } else {
            $this->builder->with(['messages' => function ($query) {
                $query->oldest('created_at'); // Ensure earliest message for eager loading
            }])->orderBy(
                \DB::raw('(SELECT MAX(created_at) FROM chat_room_messages WHERE chat_room_messages.chat_room_id = chat_rooms.id)')
            );
        }
    }




    public function read_status($value)
    {
        $participantId = $this->request->input('participant_id');

        $this->builder->whereHas('messages', function ($query) use ($value, $participantId) {
            if ($value == 1) { // Read messages
                $query->whereHas('messageReadStatuses', function ($subQuery) use ($participantId) {
                    $subQuery->where('chat_room_participant_id', $participantId);
                });
            } elseif ($value == 0) { // Unread messages
                $query->whereDoesntHave('messageReadStatuses', function ($subQuery) use ($participantId) {
                    $subQuery->where('chat_room_participant_id', $participantId);
                });
            }
        });
    }


    // Date filters for the pivot table (delivered messages) + Eager Loading


}
