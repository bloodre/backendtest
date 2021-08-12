<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Prefecture;
use App\Models\User;
use Config;

class CompanyController extends Controller {

    /**
     * Get named route
     *
     */
    private function getRoute() {
        return 'companies';
    }


    /**
     * Validator for companies
     *
     * @return \Illuminate\Http\Response
     */
    protected function validator(array $data, $type) {
        return Validator::make($data, [
                'name'=> 'required|string|max:255|unique:companies,name,' . $data['id'],
		'email' => 'required|email',
		'prefecture_id' => 'required|int',
		'phone' => 'nullable|string|min:0|max:15',
		'postcode' => 'required|string|min:7|max:8',
		'city' => 'required|string|min:0|max:255',
		'local' => 'required|string|min:0|max:255',
		'street_address' => 'nullable|string|min:0|max:255',
		'business_hour' => 'nullable|string|min:0|max:255',
		'regular_holiday' => 'nullable|string|min:0|max:255',
		'image' => 'required|string|min:0|max:255',
		'fax' => 'nullable|string|min:0|max:255',
		'url' => 'nullable|string|min:0|max:255',
		'license_number' => 'nullable|string|min:0|max:255'
        ]);
    }

    public function index() {
        return view('backend.companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $company = new Company();
        $prefecture_list = Prefecture::pluck('display_name','id'); // This list is used to create the dropdown select form
        $company->form_action = $this->getRoute() . '.create';
        $company->page_title = 'Company Add Page';
        $company->page_type = 'create';
        $company->prefecture_id = 1; //default selection
        return view('backend.companies.form', [
            'company' => $company, 'prefecture_list' =>$prefecture_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $newCompany = $request->all();  
	$username = Auth::user()->username; // Used for handling failed submit requests
        
        // We check if a new picture has been uploaded or not
        if(isset($newCompany['image'])){ 
        	$request->validate(['image' => 'required|mimes:jpeg,jpg,png',]);
        	$file = $request->file('image');
        	
        	$newCompany['image'] = $file->getClientOriginalName();
        	
        	// We save the picture in the temp folder in case the request fails
        	$file->move(public_path('uploads/files/temp'),$username.'_temp'.$newCompany['image']);
        } // If it has not been uploaded, we check if there is a file in the temp folder from a failed request
        else {
		$files = scandir(public_path('uploads/files/temp'));
		foreach ($files as $temp_file){
			$basename = pathinfo($temp_file)['basename'];
			if(substr($basename,0,strlen($username)+10) == $username.'_temp_read'){
				// We reset the name of the file so that it may not be deleted if the request fails
				rename(public_path('uploads/files/temp/').$basename, 
				public_path('uploads/files/temp/').$username.'_temp'.explode('_temp_read', $basename)[1]);
				
				// We retrieve the original filename from the picture in temp
				$newCompany['image'] = explode('_temp_read', $basename)[1];
				
			}
		}
        }
        
    
        
        // Validate input, indicate this is 'create' function
        $this->validator($newCompany, 'create')->validate();

        try {
            $company = Company::create($newCompany);
            if ($company) {
                // Upload image and back to the list

		rename(public_path('uploads/files/temp/').$username.'_temp'.$newCompany['image'], 
		public_path('uploads/files/').'company_'.$company->id.'.'.pathinfo($newCompany['image'])['extension']);

		return redirect()->route($this->getRoute())->with('success', Config::get('const.SUCCESS_CREATE_MESSAGE'));
            } else {
                // Create is failed
                return redirect()->route($this->getRoute())->with('error', Config::get('const.FAILED_CREATE_MESSAGE'));
            }
        } catch (Exception $e) {
            // Create is failed
            return redirect()->route($this->getRoute())->with('error', Config::get('const.FAILED_CREATE_MESSAGE'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $company = Company::find($id);
        $company->form_action = $this->getRoute() . '.update';
        $company->page_title = 'Company Edit Page';
        $prefecture_list = Prefecture::pluck('display_name','id'); // Once again, we want this list for the dropdown select form
        // Add page type here to indicate that the form.blade.php is in 'edit' mode
        $company->page_type = 'edit';
        return view('backend.companies.form', [
            'company' => $company,'prefecture_list' =>$prefecture_list
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $newCompany = $request->all();
        $newPicture = False;
        $username = Auth::user()->username;
        
        // We check if a new picture has been uploaded or not
        if(isset($newCompany['image'])){ 
        	$request->validate(['image' => 'required|mimes:jpeg,jpg,png',]);
        	$newPicture = True;
        	$file = $request->file('image');
        	
        	$newCompany['image'] = $file->getClientOriginalName();
        	// We move the picture to the temp folder
        	$file->move(public_path('uploads/files/temp'),$username.'_temp'.$newCompany['image']);
        }
        // We check if there is a picture in temp
        else {
		$files = scandir(public_path('uploads/files/temp'));
		foreach ($files as $temp_file){
			$basename = pathinfo($temp_file)['basename'];
			if(substr($basename,0,strlen($username)+10) == $username.'_temp_read'){
				// We reset the name of the file so that it may not be deleted if the request fails
				rename(public_path('uploads/files/temp/').$basename, 
				public_path('uploads/files/temp/').$username.'_temp'.explode('_temp_read', $basename)[1]);
				
				// We retrieve the original filename from the picture in temp
				$newCompany['image'] = explode('_temp_read', $basename)[1];
				$newPicture = True;
				
			}
		}
        }
        
        try {
            $currentCompany = Company::find($request->get('id'));
            if ($currentCompany) {
            	// If no picture has been uploaded, the picture name is therefore the same
            	if(!$newPicture){
            		$newCompany['image'] = $currentCompany['image'];
            	}               
                $this->validator($newCompany, 'update')->validate();

		// Delete previous picture and upload new one (if there is the need to)
		if($newPicture){
			unlink(public_path('uploads/files/').'company_'.$currentCompany->id.'.'.pathinfo($currentCompany['image'])['extension']);
			rename(public_path('uploads/files/temp/').$username.'_temp'.$newCompany['image'], 
			public_path('uploads/files/').'company_'.$currentCompany->id.'.'.pathinfo($newCompany['image'])['extension']);
		}

                // Update company
                $currentCompany->update($newCompany);
                // If update is successful
                return redirect()->route($this->getRoute())->with('success', Config::get('const.SUCCESS_UPDATE_MESSAGE'));
            }
        } catch (Exception $e) {
            // If update is failed
            return redirect()->route($this->getRoute())->with('error', Config::get('const.FAILED_UPDATE_MESSAGE'));
        }
    }

    public function delete(Request $request) {
        try {
            // Get company by id
            $company = Company::find($request->get('id'));
            // If to-delete company is not the one currently logged in, proceed with delete attempt
            

            // Delete company picture
            unlink(public_path('uploads/files/').'company_'.$company->id.'.'.pathinfo($company->image)['extension']);

            // Delete company
            $company->delete();
            
            // If delete is successful
            return redirect()->route($this->getRoute())->with('success', Config::get('const.SUCCESS_DELETE_MESSAGE'));
    
        } catch (Exception $e) {
            // If delete is failed
            return redirect()->route($this->getRoute())->with('error', Config::get('const.FAILED_DELETE_MESSAGE'));
        }
    }
    

}
