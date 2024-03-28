<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    //not used anymore
    protected function checkAndUpdatePermission($ability, $model, $message = null) {
        if(Gate::denies($ability, $model)) {
            abort(403, 'You are not authorized to '. $message);
        }
    }
}
