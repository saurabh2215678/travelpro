<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Pincode;
use App\Models\Package;
use App\Models\Country;
use Response;
use Validator;
use Session;
use DB;


class CommonController extends Controller {

	private $theme;
	public function __construct(){
		$this->theme = config('custom.themejs');
	}

	//ajax_load_cities
	public function ajaxLoadStates(Request $request){

		$response = [];
		$states = [];
		$countryId = (isset($request->country_id))?$request->country_id:0;

		$selectArr = ['id','name','country_id','status','created_at','updated_at'];

		if(is_numeric($countryId) && $countryId > 0){
			$states = DB::table('states')->select($selectArr)->where('country_id', $countryId)->where('status', 1)->orderBy('name')->get();
		}

		$options = "";
		if(!empty($states)){
			foreach($states as $state){
				$selected = '';
				/*if($state->id == $city_id){
					$selected = 'selected';
				}*/
				$options .= '<option value="'.$state->id.'" '.$selected.'>'.$state->name.'</option>';
			}
		}

		$response['success'] = true;
		$response['options'] = $options;

		return response()->json($response);

	}

	//ajax_load_cities
	public function ajaxLoadCities(Request $request){

		$response = [];
		$cities = [];

		//$options = '<option value="">--Select--</option>';

		$stateId = (isset($request->state_id))?$request->state_id:0;
		$cityId = (isset($request->city_id))?$request->city_id:0;

		/*$selectArr = ['id','name','state','state_id','gst_code','status','created_at','updated_at'];

		if(is_numeric($state_id) && $state_id > 0){
			$cities = DB::table('cities')->select($selectArr)->where('state_id', $state_id)->orderBy('name')->get();
		}

		if(!empty($cities) && count($cities) > 0){
			foreach($cities as $city){
				$selected = '';
				if($city->id == $city_id){
					$selected = 'selected';
				}
				$options .= '<option value="'.$city->id.'" '.$selected.'>'.$city->name.'</option>';
			}
		}*/

		$options = $this->getCitiesOptions($stateId, $cityId);

		$response['success'] = true;
		$response['options'] = $options;

		return response()->json($response);

	}

	//ajax_load_sub_destinations
	public function ajaxLoadSubDestinations(Request $request){

		$packageSubDestination = '';
		$response = [];
		$destinations = [];
		$destinationId = (isset($request->destination_id))?$request->destination_id:0;

		$selectArr = ['id','name','parent_id','status','created_at','updated_at'];

		if(is_numeric($destinationId) && $destinationId > 0){
			$destinations = DB::table('destinations')->select($selectArr)->where('parent_id', $destinationId)->where('status', 1)->orderBy('name')->get();
		}
		$options = "";

		if(!empty($destinations)){
			
			foreach($destinations as $destination){
				$selected = '';
				/*if($state->id == $city_id){
					$selected = 'selected';
				}*/

			$packageSubDestination = Package::where('status',1)->where('sub_destination_id',$destination->id)->first();

			if(isset($packageSubDestination) && !empty($packageSubDestination)){
				$options .= '<option value="'.$destination->id.'" '.$selected.'>'.$destination->name.'</option>';
			}
			}
		}

		$response['success'] = true;
		$response['options'] = $options;

		return response()->json($response);

	}


	private function getCitiesOptions($stateId=0, $cityId=0){

		$cities = '';

		$options = '<option value="">--Select--</option>';

		$selectArr = ['id','name','state','state_id','gst_code','status','created_at','updated_at'];

		if(is_numeric($stateId) && $stateId > 0){
			$cities = DB::table('cities')->select($selectArr)->where('state_id', $stateId)->orderBy('name')->get();
		}

		if(!empty($cities) && count($cities) > 0){
			foreach($cities as $city){
				$selected = '';
				if($city->id == $cityId){
					$selected = 'selected';
				}
				$options .= '<option value="'.$city->id.'" '.$selected.'>'.$city->name.'</option>';
			}
		}

		return $options;

	}

	
	//ajax_regenerate_captcha
	// public function ajaxRegenerateCaptcha(Request $request){

	// 	$response = [];

	// 	$response['success'] = true;

	// 	$captcha_src = captcha_src('custom');

	// 	$response['captcha_src'] = $captcha_src;

	// 	return response()->json($response);

	// }

	public function ajax_changeLanguage(Request $request){

		//prd($request->toArray());

		$response = [];

		$response['success'] = false;

		if($request->method() == 'POST'){
			$locale_lang = (isset($request->locale_lang))?$request->locale_lang:'';

			if(!empty($locale_lang)){
				session(['locale_lang'=>$locale_lang]);
				$response['success'] = true;
			}

		}

		return response()->json($response);

	}


	// ajax_set_currency
	public function ajaxSetCurrency(Request $request){

		//prd($request->toArray());

		$response = [];

		$response['success'] = false;

		if($request->method() == 'POST'){
			$currency = (isset($request->currency))?$request->currency:'';

			if(!empty($currency)){
				session(['to_currency'=>$currency]);
				$response['success'] = true;
			}

		}

		return response()->json($response);

	}

	public function newsletterSubscribe(Request $request){

		$response = [];
		$response['success'] = false;
		$message = '';

		if($request->method() == 'POST' || $request->method() == 'post'){

			$email = $request->email;

			$existEmail =  DB::table('newsletter_subscribers')->select('id')->where(['email'=>$email])->first();
			
			if(!empty($existEmail)){

				$existEmail=true;
			}
			if(!$existEmail) {
				$id = DB::table('newsletter_subscribers')->insert(['email'=>$email, 'status'=>1]);
				$response['success'] = true; 
				$response['message'] = 'Subscribed Successfully.';  
			}
			elseif($existEmail){
				$response['message'] = 'You are already subscribed our newsletter.'; 
			}
			
		} // close post
		echo json_encode($response); exit;
	}

	public function ajaxLoadCountryCode(Request $request){
		$country_codes = [];
		if(isset($request->type) && $request->type == 'country_code'){
			$CountryCodeFilePath = resource_path('js/themes/'.$this->theme.'utils/CountryCodes.json');
			if (File::exists($CountryCodeFilePath)) {
				$country_codes = json_decode(File::get($CountryCodeFilePath), true);
			}
		} else if(isset($request->type) && $request->type == 'phone_code'){
			$country_codes = Country::where('status',1)->get();
		}
		return Response::json(array('success' => true, 'options' => $country_codes), 200);
	}



	/* End of Controller */
}
