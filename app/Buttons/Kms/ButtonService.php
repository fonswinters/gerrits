<?php
namespace App\Buttons\Kms;


use App\Buttons\Models\Button;
use Komma\KMS\Core\ModelService;

final class ButtonService extends ModelService
{
    protected $sortable = false;
    protected $orderReverse = true;

    function __construct()
    {
        $this->modelClassName = Button::class;

        parent::__construct();
    }
}