<?php
namespace App\Servicepoints\Kms;


use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Core\ModelService;


final class ServicepointService extends ModelService
{
    protected $sortable = false;
    protected $orderReverse = true;

    function __construct()
    {
        $this->modelClassName = Servicepoint::class;

        parent::__construct();
    }
}