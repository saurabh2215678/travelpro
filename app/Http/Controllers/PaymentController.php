<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use App\Models\Order;
use App\Models\Country;
use DB;
use Validator;

class PaymentController extends Controller {

    public function payOnline(Request $request) {

        $data = [];
        $method = $request->method();

        if($method == 'POST') {
            $action = $request->action ?? '';
            $userId = Auth::user()->id ?? 0;
            $is_agent = Auth::user()->is_agent??'';
            $approved_agent = Auth::user()->approved_agent??'';
            $agent_id = 0;
            if($is_agent == 1 && $approved_agent == 1){
                $agent_id = Auth::user()->id??'';
            }

            if($action == 'wallet') {
                $order_type = 7;
                $nicknames = [];
                $nicknames['amount'] = 'Amount';
                $rules = [];
                $rules['amount'] = 'required|numeric|min:1';

                $name =  Auth::user()->name??'';
                $email = Auth::user()->email??'';
                $phone = Auth::user()->phone??'';
                $country_code = Auth::user()->country_code??91;

                $user =  Auth::user()??[];
                if($user && $user->id) {
                    if($is_agent == 1 && $approved_agent == 1) {
                        $name = $user->agentInfo->company_name??$name;
                    }
                }
            } else {
                $order_type = 2;

                $nicknames = [];
                $nicknames['amount'] = 'Amount';
                $nicknames['name'] = 'Name';
                $nicknames['address'] = 'Address';
                $nicknames['description'] = 'Description';
                $nicknames['email'] = 'Email';
                $nicknames['country'] = 'Country';
                $nicknames['phone'] = 'Phone';
                $nicknames['state'] = 'State';
                $nicknames['city'] = 'City';
                $nicknames['zip_code'] = 'Postal Code';

                $rules = [];
                $rules['amount'] = 'required|numeric|min:1';
                $rules['name'] = 'required';
                $rules['address'] = 'required';
                $rules['description'] = 'required';
                $rules['email'] = 'required|email';
                $rules['country'] = 'required';
                $rules['state'] = 'nullable|regex:/^[\pL\s\-]+$/u';
                $rules['city'] = 'nullable|regex:/^[\pL\s\-]+$/u';

                if($request->country_code) {
                    $country_code = $request->country_code??91;
                } else if($request->country) {
                    $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
                }
                if(empty($phone)) {
                    if(!empty($country_code) && $country_code != 91) {
                        $rules['phone'] = 'regex:/^\d{4,12}$/';
                    } else if($request->phone){
                        $rules['phone'] = 'digits:10';
                    } else {
                        $rules['phone'] = 'required';
                    }
                }
                $zip_code = $request->zip_code??'';
                if($zip_code) {
                    if(!empty($country_code) && $country_code != 91) {
                        $rules['zip_code'] = 'max:10|regex:/^[\s\w-]*$/';
                    } else {
                        $rules['zip_code'] = 'digits:6';
                    }
                }

                $name = $request->name??'';
                $email = $request->email??'';
                $phone = $request->phone??'';
            }

            $validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['numeric'] = ':attribute must be a number.';

            $this->validate($request, $rules, $validation_msg, $nicknames);

            try {
                $country = $request->country ?? '';
                if(!empty($country) && is_numeric($country)) {
                    $country = CustomHelper::GetCountry($country,'name');
                }

                $order_no = CustomHelper::genrateOrderNoId($userId);

                $order = new Order;
                $order->order_no = $order_no;
                $order->order_type = $order_type;
                $order->name = $name;
                $order->user_id = $userId;
                $order->agent_id = $agent_id;
                $order->email = $email;

                $order->phone = $phone;
                $order->country_code = $country_code??'';
                $order->address1 = $request->address ?? '';  
                $order->city = $request->city ?? '';
                $order->state = $request->state ?? '';
                $order->country = $country ?? '';
                $order->zip_code = $request->zip_code ?? '';
                $order->payment_status = 0;
                $order->comment =  $request->description ?? '';
                $order->payment_response = NULL;
                $order->discount_type = $request->discount_type??'';
                $order->discount = $request->discount??0;

                $total_amount = $request->amount ?? 0;
                if($action == 'wallet') {
                    $fees = 0;
                    $WALLET_PAYMENT_GATEWAY_CHARGE = CustomHelper::WebsiteSettings('WALLET_PAYMENT_GATEWAY_CHARGE');
                    if($request->amount > 0){
                        $fees = ($WALLET_PAYMENT_GATEWAY_CHARGE / 100) * $request->amount;
                        $total_amount = $fees + $request->amount;
                        $order->fees = $fees ?? 0;
                    }
                }
                $order->sub_total_amount = $request->amount ?? 0;
                $order->total_amount = $total_amount ?? 0;
                $order->trip_date = null;
                $isSaved = $order->save();

                if($isSaved) {
                    return redirect('pay_now/'.$order_no);
                } else {
                    $message = 'Something went wrong, please try again.';
                    return back()->with('alert-danger', $message);
                }
            } catch (\Exception $e) {
                $response['message'] = $e->getMessage();
                $error = $e->getMessage();
                $message = 'Something went wrong, please try again.'.$error;
                return back()->with('alert-danger', $message); 
            }
        }
        $data['countries'] = Country::where('status',1)->get();
        $data['meta_title'] = 'Online Payment - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Online Payment';
        return view(config('custom.theme').'.pay-online', $data);
    }
    /* end of controller */
}