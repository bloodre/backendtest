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
        // We check if the search was succesfull or not
        if(isset($location_info->prefecture)){
        	// We need the id of the prefecture and therefore access the prefectures database
        	$prefecture_id = Prefecture::pluck('id','display_name')[$location_info->prefecture];
        	$location = array('prefecture' => $prefecture_id, 'city' => $location_info->city, 'local' => $location_info->local);
        }
        else{
        	// We send a null response if the search has failed
        	$location = array('prefecture' => null, 'city' => null, 'local' => null);
        }
        
        
        return response()->json($location);
    }

}
