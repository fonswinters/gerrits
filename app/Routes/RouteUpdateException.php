<?php declare(strict_types=1);

namespace App\Routes;


use Illuminate\Database\Eloquent\Model;
use Throwable;

class RouteUpdateException extends \RuntimeException
{
    const PARENT_HAS_NO_TRANSLATIONS = 1;

    private ?Model $model;
    private array $payload;

    public function __construct($message = "", $code = 0, Throwable $previous = null, Model $model = null, array $payload = [])
    {
        $this->model = $model;
        $this->payload = $payload;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}