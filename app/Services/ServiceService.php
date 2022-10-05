<?php


namespace App\Services;


use App\Services\Models\Service;
use Carbon\Carbon;

class ServiceService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = Carbon::now()->addHour();
        $this->today = $this->today->format('Y-m-d H:i:s');

        parent::__construct();
    }

    public function getAllServices($pagination = false, $itemsPerPage = 9)
    {
        $servicesQuery = Service::with('translation', 'images')
            ->where('active', 1)
            ->orderBy('lft')
            ->whereHas('translation');

        if($pagination) {
            $services = $servicesQuery->paginate($itemsPerPage);
        } else {
            $services = $servicesQuery->get();
        }
        return $services;
    }


}