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
        	if($company['local']!= '以下に掲載がない場合'){
        		$tabular_company['address'] = $company['city'].$company['local'].$company['street_address'];
        	}
        	else{
        		$tabular_company['address'] = $company['city'].$company['street_address'];
        	}
        	$tabular_company['updated_at'] = $company['updated_at']->format('Y-m-d H:i:s');
        	array_push($tabular_companies,$tabular_company);
        	    	
        }
        return response()->json($tabular_companies);
    }
    
    // This code is used to store temporarily pictures of failed submist request
    public function tempPicture($username) {
    	
    	
    	$files = scandir(public_path('uploads/files/temp'));
    	
    	foreach($files as $file){
		$basename = pathinfo($file)['basename'];
		// We delete unneeded files
		
		if(substr($basename,0,strlen($username)+10) == $username.'_temp_read'){
			unlink(public_path('uploads/files/temp/').$basename);
			
		}
		
		// We rename the file to mark it as read and send a response to the client
		elseif(substr($basename,0,strlen($username)) == $username){
			rename(public_path('uploads/files/temp/').$basename, public_path('uploads/files/temp/').$username.'_temp_read'.explode('_temp', $basename)[1]);
			return response()->json('keep');
		}
		
		
 	}
 	
 	return response()->json('no action');
    	  
    }
    
    // Function used in page refresh: check if there is an image in temp to upload
    public function tempRefresh($username) {


     $files = scandir(public_path('uploads/files/temp'));
     
     foreach($files as $file){
   	  $basename = pathinfo($file)['basename'];
	  // We check if there is a file inside temp belonging to the current user
   	  if(substr($basename,0,strlen($username)+10) == $username.'_temp_read'){
		  return response()->json('display');
	
	  }
    }

    return response()->json('no action');
   }

}
