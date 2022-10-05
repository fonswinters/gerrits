<?php


namespace App\References;


use App\Base\Service;
use App\References\Models\Reference;
use Carbon\Carbon;

final class ReferenceService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = Carbon::now()->addHour();
        $this->today = $this->today->format('Y-m-d H:i:s');

        parent::__construct();
    }

    public function getAllReferences($pagination = false, $itemsPerPage = 9)
    {
        $referencesQuery = Reference::with('translation')
            ->where('active', 1)
            ->orderBy('created_at', 'desc');

        if($pagination)
        {
            $references = $referencesQuery->paginate($itemsPerPage);
        }
        else
        {
            $references = $referencesQuery->get();
        }
        return $references;
    }


}