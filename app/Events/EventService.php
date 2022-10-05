<?php


namespace App\Events;


use App\Base\Service;
use App\Events\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;

final class EventService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = now()->endOfDay();
        $this->today = $this->today->format('Y-m-d H:i:s');
        parent::__construct();
    }

    /**
     * Base query for get event from DB
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function baseEventQuery()
    {
        return Event::with('translation', 'images')
            ->where('active', 1)
            ->where('datetime_start', '>=', $this->today)
            ->orderBy('datetime_start','asc')
            ->orderBy('created_at', 'asc')
            ->whereHas('translation');
    }

    /**
     * get all events
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function getAllEvents()
    {
        return $this->baseEventQuery()->get();
    }

    /**
     * Get the next events after give $event
     *
     * @param  Event  $event
     * @return Collection|\Illuminate\Support\Collection
     */
    public function getNextEvents(Event $event)
    {
        return $this->baseEventQuery()
            ->where('events.id', '!=', $event->id)
            ->where('datetime_start', '>=', $this->today)
            ->take(4)
            ->get();
    }

}