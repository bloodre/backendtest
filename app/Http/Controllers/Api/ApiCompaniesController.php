<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Prefecture;

class ApiCompaniesController extends Controller {

    /**
     * Return the contents of Company table in tabular form
     *
     */
    public function getCompaniesTabular() {
        $companies = Company::orderBy('id', 'desc')->get();
        $prefecture_id_convert = Prefecture::pluck('display_name','id');
        
        // We create a new array to fit the desired design of the table
        $tabular_companies = array();
        foreach($companies as $company){
        	$tabular_company = array();
        	$tabular_company['id']= $company['id'];
        	$tabular_company['name']= $company['name'];
        	$tabular_company['email']= $company['email'];
        	$tabular_company['postcode']= $company['postcode'];
        	$tabular_company['prefecture']= $prefecture_id_convert[$company['prefecture_id']];
        	$tabular_company['address'] = $company['city'].$company['local'].$company['street_address'];
        	$tabular_company['updated_at'] = $company['updated_at']->format('Y-m-d H:i:s');
        	array_push($tabular_companies,$tabular_company);
        	    	
        }
        return response()->json($tabular_companies);
    }

}
