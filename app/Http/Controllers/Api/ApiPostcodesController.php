<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Postcode;
use App\Models\Prefecture;


class ApiPostcodesController extends Controller {

    /**
     * Return the contents of User table in tabular form
     *
     */
    public function getLocation($postcode) {

        $location_info = Postcode::where('postcode','=', $postcode)->first(); // Using the postcode, we find all the information we need
        // We need the id of the prefecture and therefore access the prefectures database
        $prefecture_id = Prefecture::pluck('id','display_name')[$location_info->prefecture];


        // We put all the needed information in an array and send it
        $location = array('prefecture' => $prefecture_id, 'city' => $location_info->city, 'local' => $location_info->local);
        return response()->json($location);
    }

}
