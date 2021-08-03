<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;

class ApiUsersController extends Controller {

    /**
     * Return the contents of Company table in tabular form
     *
     */
    public function getCompaniesTabular() {
        $users = Company::orderBy('id', 'desc')->get();
        return response()->json($users);
    }

}
