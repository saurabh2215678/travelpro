<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LicenseBoxExternalAPI;

class LicenseCheckController extends Controller {

    public function activate(Request $request)
    {
        if ($request->method() == "POST") {
            $rules['license'] = 'required';
            $rules['client'] = 'required';
            $this->validate($request, $rules);

            $license = isset($request->license) ? $request->license : '';
            $client = isset($request->client) ? $request->client : '';

            //For Genrate Lic File
            $license_code = null;
            $client_name = null;

            $license_code = strip_tags(trim($license));
            $client_name = strip_tags(trim($client));

            $api = new LicenseBoxExternalAPI();

            $activate_response = $api->activate_license($license_code, $client_name);

            if (empty($activate_response)) {
                return back()->with('alert-danger', 'Server is unavailable.');
            } else {
                return redirect('/')->with('alert-success', $activate_response['message']);
            }
        }
        return view('activate');
    }

}