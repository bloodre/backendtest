<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Company;
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
		'email' => 'required|string|max:100',
		'prefecture_id' => 'required|int',
		'phone' => 'string|min:6|max:255',
		'postcode' => 'required|string|min:7|max:8',
		'city' => 'required|string|min:0|max:255',
		'local' => 'required|string|min:6|max:255',
		'street_address' => 'string|min:0|max:255',
		'business_hour' => 'string|min:0|max:255',
		'regular_holiday' => 'string|min:0|max:255',
		'image' => 'required|string|min:0|max:255',
		'fax' => 'string|min:6|max:255',
		'url' => 'string|min:6|max:255',
		'license_number' => 'string|min:6|max:255'
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
        $company->form_action = $this->getRoute() . '.create';
        $company->page_title = 'Company Add Page';
        $company->page_type = 'create';
        return view('backend.companies.form', [
            'company' => $company
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

        // Validate input, indicate this is 'create' function
        $this->validator($newCompany, 'create')->validate();

        try {
            $company = Company::create($newCompany);
            if ($company) {
                // Create is successful, back to list
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
        // Add page type here to indicate that the form.blade.php is in 'edit' mode
        $company->page_type = 'edit';
        return view('backend.companies.form', [
            'company' => $company
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
        try {
            $currentCompany = Company::find($request->get('id'));
            if ($currentCompany) {               
                $this->validator($newCompany, 'update')->validate();
                }

                // Update company
                $currentCompany->update($newCompany);
                // If update is successful
                return redirect()->route($this->getRoute())->with('success', Config::get('const.SUCCESS_UPDATE_MESSAGE'));
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
            
            // Delete user
            $company->delete();
            
            // If delete is successful
            return redirect()->route($this->getRoute())->with('success', Config::get('const.SUCCESS_DELETE_MESSAGE'));
    
        } catch (Exception $e) {
            // If delete is failed
            return redirect()->route($this->getRoute())->with('error', Config::get('const.FAILED_DELETE_MESSAGE'));
        }
    }

}
