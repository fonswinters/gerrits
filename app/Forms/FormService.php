<?php

namespace App\Forms;


use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

final class FormService
{
    /**
     * Origin from where the request was done
     * f.e. 'contact','offer'
     *
     * @var
     */
    protected $origin;

    /**
     * Set the origin of the form request
     *
     * @param $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * Store the request in the database
     *
     * @param FormRequest $request
     */
    public function storeRequest(FormRequest $request)
    {
        $storeRequest = $request->duplicate();
        $storeRequest = $storeRequest->except('_token', '_willie');

        foreach ($storeRequest as $name => $value) {
            if ( ! \Schema::hasColumn('requests', $name)) {
                \Schema::table('requests', function ($table) use ($name) {
                    $table->text($name)->nullable();
                });
            }
        }

        $dbRequest = new \App\Forms\Models\Request();
        $dbRequest->origin = $this->origin;
        $dbRequest->created_at = Carbon::now()->toDateTimeString();
        $dbRequest->updated_at = Carbon::now()->toDateTimeString();
        foreach ($storeRequest as $name => $value) {
            if(!is_scalar($value)) continue;
            $dbRequest->$name = $value;
        }
        $dbRequest->save();
    }
}

