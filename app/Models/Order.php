<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Facades\Auth;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\PaymentGateway;
use App\Models\OrderPayments;
use App\Models\UserWallet;
use App\Models\OrderAmendments;
use App\Models\PackageInventory;
use App\Models\CabRouteInventory;
use App\Models\CabsInventory;
use App\Models\AccommodationInventory;
use App\Models\AirlineRouteInventory;
use App\Helpers\CustomHelper;
use App\Helpers\FlightHelper;
use Razorpay\Api\Api;
use Storage;
use DB;
use PDF;
use File;

class Order extends Model {

    protected $table = 'orders';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function User() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

   public function OrderServiceVoucher() {
        return $this->hasOne('App\Models\OrderServiceVoucher', 'order_id');
    }
    /**
     * @return BelongsTo
     */
    public function Package(): BelongsTo
    {
        return $this->belongsTo('App\Models\Package', 'package_id')->with('packageDestination');
    }
    public function userData() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function supplierData() {
        return $this->belongsTo('App\Models\User', 'supplier_id');
    }

    public function PackagePrice() {
        return $this->belongsTo('App\Models\PackagePriceInfo', 'package_type');
    }
    /**
     * @return BelongsTo
     */
    public function Country(): BelongsTo
    {
        return $this->belongsTo('App\Models\Country', 'country');
    }

    public function OrderTraveller() {
        return $this->hasMany('App\Models\OrderTraveller', 'order_id')->orderBy('serial_no','asc');
    }

    public function agentInfo(){
        return $this->hasOne('App\Models\UserAgentInfo', 'user_id','agent_id');
    }

    public function supplierInfo(){
        return $this->hasOne('App\Models\UserAgentInfo', 'user_id','supplier_id');
    }
    
    public function orderAmendments() {
        return $this->hasMany('App\Models\OrderAmendments', 'order_id');
    }

    public function pendingOrderAmendments() {
        return $this->hasMany('App\Models\OrderAmendments', 'order_id')->where('status','0')->count();
    }

    public static function processPayment($order_id, $payment_method='') {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $record_name = '';
        $order = Order::find($order_id);
        if($order) {
            $order_no = $order->order_no??'';
            $order_type = $order->order_type??'1';
            $pay_type = $order->pay_type??'total_price';
            $total_amount = $order->total_amount??0;
            $amount = $total_amount;
            
            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
            $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');

            $order_type_arr = config('custom.order_type');
            $order_type_name = $order_type_arr[$order_type].' Booking'??'Booking';
            $email_subject = $order_type_name." Enquiry | ".$SYSTEM_TITLE;

            $package = $order->Package??[];
            $packagePrice = $order->PackagePrice??[];
            $userObject = $order->User??[];
            $email_data = [];
            $email_data['order'] = $order;
            $email_data['package'] = $package;
            $email_data['packagePrice'] = $packagePrice;
            $email_data['userObject'] = $userObject;
            if($order_type==3) { //Flight

                $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                    if($storage->exists($path.$logo)){
                        $logoSrc = asset('/storage/'.$path.$logo);
                    }
                }

                $common_logo = '';
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                 '{company_logo}' => $logoSrc
             );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);

                $contact_details = '';
                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $SYSTEM_TITLE
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);

                $form_values = [];
                $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
                $form_values['{header}'] = $common_logo??'';
                $form_values['{logo}'] = $logoSrc??'';
                $form_values['{contact_details}'] = $contact_details??'';
                $form_values['{e_date}'] = date("l jS \of F Y h:i A");
                $form_values['{name}'] = $order->name;
                $form_values['{email}'] = $order->email;
                $form_values['{phone}'] = $order->phone;
                $form_values['{city}'] = $order->city;
                if($order->trip_date) {
                    $form_values['{date}'] = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                } else {
                    $form_values['{date}'] = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                }
                $form_values['{total_amount}'] = CustomHelper::getPrice($order->total_amount);

                $itineraries = '';
                if($order->flight_details) {
                    $flight_details = json_decode($order->flight_details);
                    $itineraries = $flight_details->itineraries??'';
                }
                $form_values['{itineraries}'] = $itineraries;
                $record_name = 'Flight booking';
                $email_template = EmailTemplate::where('slug','flight-booking-email')->first();
                $search_arr = array_keys($form_values)??[];
                $replace_arr = array_values($form_values)??[];
                $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
                $search_arr = array_keys($form_values)??[];
                $replace_arr = array_values($form_values)??[];
                $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
                //Do not send email before payment for flight
                $email_content = '';
            } elseif($order_type==2) { //Pay Online
                $record_name = 'Pay online';
                $form_values = [];
                $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
                $form_values['{name}'] = $order->name;
                $form_values['{email}'] = $order->email;
                $form_values['{phone}'] = $order->phone;
                $form_values['{city}'] = $order->city;
                $form_values['{date}'] = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                $form_values['{total_amount}'] = CustomHelper::getPrice($order->total_amount);

                $email_template = EmailTemplate::where('slug','pay-online-email-to-customer')->first();
                $search_arr = array_keys($form_values)??[];
                $replace_arr = array_values($form_values)??[];
                $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
                $search_arr = array_keys($form_values)??[];
                $replace_arr = array_values($form_values)??[];
                $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
            } else { //Package
                $email_content = view('emails.book_now_email',$email_data)->render();
                $record_name = 'Book now';
            }
            $REPLYTO = $ADMIN_EMAIL;
            $to_email = $order->email;
            $cc_email = '';
            $bcc_email_arr = [];
            if($order->inventory_id) {
                $supplier_email = $order->supplierData->email??'';
                if($supplier_email) {
                    $bcc_email_arr[] = $supplier_email;
                }
            }
            if($email_template->email_type == 'customer' && $email_template->bcc_admin == 1){
                $bcc_email_arr[] = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
            }
            $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';
            $params = [];
            $params['to'] = $to_email;
            $params['cc'] = $cc_email;
            $params['bcc'] = $bcc_email;
            $params['subject'] = $email_subject;
            $params['module_name'] = $record_name;
			$params['record_id'] = $order->id ?? 0;
            if(!empty($email_content)) {
                $params['email_content'] = $email_content;
                $isSent = CustomHelper::sendCommonMail($params);
            }
            
            if($amount == 0) {
                $payment_method = 'bank_payment';
                $order->method = $payment_method;
                $order->payment_status = 0; //Pending
                $order->save();
                $order_data = Self::offline_mail($order->id);
                session(['order_id'=>$order->id]);
                $response['success'] = true;
                $response['redirect_url'] = url('payment/thankyou');
            } elseif($payment_method == 'tpsl') {
                $payment = PaymentGateway::where(['value'=>$payment_method,'status'=>1])->first();
                $pay_type = 'total_price';
                $order_payments = new OrderPayments;
                $order_payments->payment_method = CustomHelper::getPaymentGatewayName($payment_method);
                $order_payments->order_id = $order->id;
                $order_payments->order_no = $order->order_no;
                $order_payments->user_id = $order->user_id;
                $order_payments->amount = $amount ?? 0;
                $order_payments->payment_type = $pay_type;
                $order_payments->status = 0;
                $order_payments->save();
                $last_payment_id = $order_payments->id ?? NULL;

                $order->pay_type = $pay_type;
                $order->last_payment_id = $last_payment_id;

                $name = $order->name??'';
                $email = $order->email??'';
                $phone = '';
                if($order->phone) {
                    $country_code = $order->country_code ?? '91';
                    $phone = $country_code.$order->phone;
                }
                $payment_gateway = PaymentGateway::where(['value'=>'tpsl','status'=>1])->first();
                $tpsl_type = $payment_gateway->perameter_1;
                $merchantCode = $payment_gateway->perameter_2; 
                $key = $payment_gateway->perameter_3; 
                $iv = $payment_gateway->perameter_4; 
                $schemeCode = $payment_gateway->perameter_5;
                $order->method = $payment_method;
                $order->save();

                // For Test account of tpsl
                if($tpsl_type=='test') {
                    $amount = '1.00';
                }
                
                include public_path("/tpsl_payment_gatway/TransactionRequestBean.php");
                $webServiceLocator = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';
               
                //Setting all values here
                $transactionRequestBean = new \TransactionRequestBean();
                $transactionRequestBean->merchantCode = $merchantCode;
                $transactionRequestBean->ITC = $email;
                $transactionRequestBean->customerName = $name;
                $transactionRequestBean->requestType = 'T';
                $transactionRequestBean->merchantTxnRefNumber = $order_no;
                $transactionRequestBean->amount = $amount;
                $transactionRequestBean->currencyCode ='INR';
                $transactionRequestBean->returnURL = route('response_tpsl');
                $transactionRequestBean->shoppingCartDetails = $schemeCode.'_1.0_0.0';
                $transactionRequestBean->TPSLTxnID = rand(1, 10000);
                $transactionRequestBean->mobileNumber = $phone;
                $transactionRequestBean->txnDate = date('Y-m-d');
                $transactionRequestBean->bankCode = '470';
                $transactionRequestBean->custId = '19872627';
                $transactionRequestBean->key = $key;
                $transactionRequestBean->iv = $iv;
                $transactionRequestBean->accountNo = '';
                $transactionRequestBean->webServiceLocator = $webServiceLocator;
                $transactionRequestBean->timeOut = 30;
                // prd($transactionRequestBean);

                $responseDetails = $transactionRequestBean->getTransactionToken();
                $responseDetails = (array)$responseDetails;
                if($responseDetails && isset($responseDetails[0])) {
                    $redirect_url = $responseDetails[0];
                } else {
                    $redirect_url = url('/');
                }
                // prd($redirect_url);
                // echo "<script>window.location = '" . $redirect_url . "'</script>";
                // ob_flush();
                $response['success'] = true;
                $response['redirect_url'] = $redirect_url;
            } elseif($payment_method == 'razorpay') {
                prd('processPayment.razorpay');

                $payment = PaymentGateway::where(['value'=>$payment_method,'status'=>1])->first();
                $pay_type = 'total_price';
                $order_payments = new OrderPayments;
                $order_payments->payment_method = CustomHelper::getPaymentGatewayName($payment_method);
                $order_payments->order_id = $order->id;
                $order_payments->order_no = $order->order_no;
                $order_payments->user_id = $order->user_id;
                $order_payments->amount = $amount ?? 0;
                $order_payments->payment_type = $pay_type;
                $order_payments->status = 0;
                $order_payments->save();
                $last_payment_id = $order_payments->id ?? NULL;

                $order->pay_type = $pay_type;
                $order->last_payment_id = $last_payment_id;

                $order->method = $payment_method;
                $order->save();
                session(['order_id'=>$order->id]);
                $razorpay_amount = $amount * 100;
                $notes = [];
                $notes['order_no'] = $order->order_no;
                // prd($payment->toArray());
                $razorpay_order_data = array(
                    'receipt' => $order->order_no,
                    'amount' => $razorpay_amount,
                    'currency' => 'INR',
                    'notes' => $notes,
                    // 'transfers' => $accounts_arr,
                    // 'notes'=> array('key1'=> 'value3','key2'=> 'value2')
                );
                // prd($razorpay_order_data);
                /*if(!empty($accounts_arr) && count($accounts_arr) > 0) {
                    $razorpay_order_data['transfers'] = $accounts_arr;
                }*/
                $RAZORPAY_KEY = $payment->perameter_1;
                $RAZORPAY_SECRET = $payment->perameter_2;
                // pr($RAZORPAY_KEY);
                // prd($RAZORPAY_SECRET);
                $api = new Api($RAZORPAY_KEY, $RAZORPAY_SECRET);
                $pg_order_resp = $api->order->create($razorpay_order_data);
                // prd($pg_order_resp);
                /*Razorpay\Api\Order Object
                (
                    [attributes:protected] => Array
                        (
                            [id] => order_MfH9yse2ByacI1
                            [entity] => order
                            [amount] => 350000
                            [amount_paid] => 0
                            [amount_due] => 350000
                            [currency] => INR
                            [receipt] => AKGND4067195
                            [offer_id] => 
                            [status] => created
                            [attempts] => 0
                            [notes] => Razorpay\Api\Order Object
                                (
                                    [attributes:protected] => Array
                                        (
                                        )

                                )

                            [created_at] => 1695362682
                        )

                )*/

                if(isset($pg_order_resp->id)) {
                    $pg_order_id = $pg_order_resp->id;
                    $order_payments->pg_order_id = $pg_order_id;
                    $order_payments->pg_response = json_encode($pg_order_resp->toArray());
                    $order_payments->save();
                }
            } elseif($payment_method == 'bank_payment') {
                $order->method = $payment_method;
                $order->payment_status = 0; //Pending
                $order->save();

                $order_data = Self::offline_mail($order->id);
                session(['order_id'=>$order->id]);
                // return redirect('payment/thankyou');
                $response['success'] = true;
                $response['redirect_url'] = url('payment/thankyou');
            } else if($payment_method == 'wallet') {
                $userId = Auth::user()->id??'';
                if($userId) {
                    $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                    if($old_balance >= $amount){
                        $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);
                        $wallet_comment = 'Paid for flight booking';
                        $balance = $old_balance - $amount ;
                        $walletData = array(
                            'user_id' => $userId,
                            'type' => 'debit',
                            'amount' => -$amount??0,
                            'fees' => $order->fees??0,
                            'payment_method' => 'Order',
                            'balance' => $balance,
                            'for_date' => date('Y-m-d H:i:s'),
                            'txn_id' => $order->order_no,
                            'comment' => $wallet_comment,
                        );
                        $isSavedWallet = UserWallet::create($walletData);
                        if($isSavedWallet) {
                            $order_payments = new OrderPayments;
                            $order_payments->payment_method = $payment_method_name;
                            $order_payments->order_id = $order->id;
                            $order_payments->order_no = $order->order_no;
                            $order_payments->user_id = $order->user_id;
                            $order_payments->amount = $amount??0;
                            $order_payments->payment_type = $order->pay_type;
                            $order_payments->pg_payment_status = 1; //Paid
                            $order_payments->save();
                            $last_payment_id = $order_payments->id??NULL;

                            $order->last_payment_id = $last_payment_id;
                            $order->method = $payment_method;
                            $order->payment_status = 1;
                            $order->save();
                            $response['success'] = true;
                            $response['payment_method'] = $payment_method;
                            $response['redirect_url'] = url('payment/thankyou');
                        } else {
                            $response['message'] = 'Error occurred while wallet payment';
                        }
                    } else {
                        $response['message'] = 'Wallet amount is less than order amount.';
                    }
                } else {
                    $response['message'] = 'User is not logged in to use wallet.';
                }
            } else if($payment_method == 'hold') {
                $response['success'] = true;
            }
        } else {
            $response['message'] = 'Order not found.';
        }
        return $response;
    }

    public static function processPaymentSuccess($order_id) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($order_id) {
            $order = Order::find($order_id);
            if($order && $order->payment_status == 1) {
                $update_inventory = true;
                $order_type = $order->order_type??1;
                if($order->order_type==3) { // Flight Booking
                    $resp = Order::bookFlight($order_id,'confirm-book');
                    if($resp['success']==true) {
                        // Success Flight Booking
                        $orderNo = sha1($order->id);
                        $resp = Self::sendBookingEmail($orderNo);
                        $response['success'] = true;
                        $response['redirect_url'] = url('payment/thankyou');
                    } else {
                        // Error Flight Booking
                        // $message = json_encode($resp);
                        // prd($message);
                        $message = $resp['errors'][0]['message']??($resp['message']??'');
                        $wallet_comment = 'Booking payment reversed for flight booking failed. <br />Reason: '.$message;
                        Order::refundOrderPayment($order->id,['wallet_comment'=>$wallet_comment]);

                        $booking_status = 'FAILED';
                        if(stripos($message, 'Duplicate Booking') !== false) {
                            $booking_status = 'DUPLICATE';
                        }
                        $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                        $order->status = $booking_status;
                        $order->save();
                        CustomHelper::captureSentryMessage('Payment Success, But Flight Booking Failed, Payment Reversed='.$message);
                        $response['message'] = $message;
                        $response['redirect_url'] = url('payment/failed');
                    }
                } else if($order_type == 7) { //Wallet Recharge
                    $update_inventory = false;
                    $user_id = $order->user_id;
                    $old_balance = UserWallet::where('user_id',$user_id)->sum('amount');
                    $balance = $old_balance + $order->sub_total_amount;
                    $walletData  = array(
                        'user_id' =>$user_id,
                        'type' => 'credit',
                        'amount' => $order->sub_total_amount??0,
                        'fees' => $order->fees??0,
                        'payment_method' => CustomHelper::getPaymentGatewayName($order->method),
                        'balance' => $balance,
                        'for_date' => date('Y-m-d H:i:s'),
                        'txn_id' => $order->order_no,
                        'comment' => 'Wallet Recharged by '.CustomHelper::getPaymentGatewayName($order->method),
                    );
                    $isSavedWallet = UserWallet::create($walletData);

                    $order->orders_status = CustomHelper::getOrderStatus('SUCCESS');
                    $order->status = 'SUCCESS';
                    $order->save();

                    if(stripos($order->order_no, '-') !== false) {                        
                        $order_no_arr = explode('-', $order->order_no);
                        if(isset($order_no_arr[1])) {
                            $last_payment_id = $order_no_arr[1]??0;
                            if($last_payment_id) {
                                $order_payments = OrderPayments::find($last_payment_id);
                                if($order_payments && $order_payments->id) {
                                    $orderData = $order_payments->orderData??[];
                                    if($orderData && $orderData->id) {
                                        $total_amount = $orderData->total_amount;
                                        $partial_amount = $orderData->partial_amount;
                                        $payment_due = $total_amount - $partial_amount;
                                        if($payment_due > 0 && $payment_due == $order_payments->amount) {
                                            $order_payments->status = 1;
                                            $order_payments->pg_payment_status = 1;
                                            $isSaved = $order_payments->save();
                                            if($isSaved) {
                                                $orderData->partial_amount = $orderData->partial_amount + $order_payments->amount;
                                                $isSaved = $orderData->save();
                                                if($isSaved) {
                                                    $old_balance = UserWallet::where('user_id',$user_id)->sum('amount');
                                                    $amount = $order_payments->amount;
                                                    $type = 'debit';
                                                    $for_date = $orderData->created_at;
                                                    $comment = 'Wallet debited for payment due for order no: '.$orderData->order_no;
                                                    $payment_method = 'wallet';
                                                    $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);
                                                    $balance = 0;
                                                    $balance = $old_balance - $amount;

                                                    $req_data = array(
                                                        'user_id' => $user_id,
                                                        'txn_id' => $orderData->order_no,
                                                        'type' => $type ?? null,
                                                        'amount' => -$amount,
                                                        'balance' => $balance,
                                                        'comment' => $comment,
                                                        'for_date' => $for_date,
                                                        'payment_method' => 'Order',
                                                    );
                                                    $isSaved = UserWallet::create($req_data);
                                                    if($isSaved) {

                                                    }
                                                }
                                            }
                                        }
                                    }                                    
                                }
                            }
                        }
                    }

                    $orderNo = sha1($order->id);
                    $resp = Self::sendBookingEmail($orderNo);
                    $response['success'] = true;
                    $response['redirect_url'] = url('payment/thankyou');
                } else {
                    $orderNo = sha1($order->id);
                    $resp = Self::sendBookingEmail($orderNo);
                    $response['success'] = true;
                    $response['redirect_url'] = url('payment/thankyou');
                }
                if($update_inventory) {
                    $inventory_update = Self::inventoryUpdate($order);
                }
            }
        }
        return $response;
    }

    public static function inventoryUpdate($order,$is_revort=0) {
        if($order->order_type == 1) {
            $booking_details = json_decode($order->booking_details);
            $user_id = $order->user_id;
            $package_id   = $order->package_id;
            $price_id = $order->package_type;
            $inventory = $order->inventory;
            $trip_date = CustomHelper::DateFormat( $order->trip_date, 'Y-m-d');
            $PackageInventory = CustomHelper::AvailblePackageInventory($package_id,$price_id,$trip_date);
            $inventory_id = $PackageInventory->id;

            if($inventory > 0) {
                $old_booked_inventory = $PackageInventory->booked ?? 0 ;
                
                if($is_revort == 1){
                $booked_inventory  = $old_booked_inventory - $inventory;
                }else{
                $booked_inventory  = $old_booked_inventory + $inventory;
                }
                $package_inventory = array('booked' => $booked_inventory, );
                $isUpdateinventory = PackageInventory::where('id',$inventory_id)->update($package_inventory);
            }

        } elseif($order->order_type == 3) {
            $booking_details = json_decode($order->booking_details);
            $user_id = $order->user_id;
            $supplier_id = $order->supplier_id;
            $inventory_id = $order->inventory_id;
            $inventory = $order->inventory;
            if($inventory_id) {
                $inventoryData = AirlineRouteInventory::where('id',$inventory_id)->where('user_id',$supplier_id)->first();
                if($inventoryData && $inventory) {
                    if($is_revort) {
                        $new_inventory = $inventoryData->inventory + $inventory;
                    } else {
                        $new_inventory = $inventoryData->inventory - $inventory;
                    }
                    $inventoryData->inventory = $new_inventory;
                    $inventoryData->save();
                }
            }
        } elseif($order->order_type == 6) {
            $booking_details = json_decode($order->booking_details);
            $user_id = $order->user_id;
            $package_id   = $order->package_id;
            $price_id = $order->package_type;
            $inventory = $order->inventory;
            $slot_id = $booking_details->slot_id ?? '';
            $trip_date = CustomHelper::DateFormat( $order->trip_date, 'Y-m-d');

            $PackageInventory = CustomHelper::AvailblePackageInventory($package_id,$price_id,$trip_date,$slot_id);
            $inventory_id = $PackageInventory->id;
            if($inventory > 0) {
                $old_booked_inventory = $PackageInventory->booked ?? 0 ;
                if($is_revort == 1){
                    $booked_inventory  = $old_booked_inventory - $inventory;
                }else{
                    $booked_inventory  = $old_booked_inventory + $inventory;
                }
                $package_inventory = array('booked' => $booked_inventory, );
                $isUpdateinventory = PackageInventory::where('id',$inventory_id)->update($package_inventory);
            }
        } elseif($order->order_type == 4) {
            $user_id = $order->user_id;
            if($order->booking_details) {
                $booking_details = json_decode($order->booking_details);

                if(config('custom.CAB_VERSION')==2) {
                    $price_id = $booking_details->price_id??'';
                    if($price_id) {
                        $cabsCityPrice = CabsCityPrice::find($price_id);
                        if($cabsCityPrice) {
                            $city_id = $cabsCityPrice->city_id;
                            $cab_id = $cabsCityPrice->cab_id;
                            $trip_date = CustomHelper::DateFormat($order->trip_date, 'Y-m-d');
                            $inventory = $order->inventory;
                            $cabsInventory = CabsInventory::where('city_id',$city_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
                            if($cabsInventory) {
                                $old_booked_inventory = $CabInventory->booked??0;
                                if($is_revort == 1){
                                    $booked_inventory  = $old_booked_inventory - $inventory;
                                } else {
                                    $booked_inventory  = $old_booked_inventory + $inventory;
                                }
                                $cabsInventory->booked = $booked_inventory;
                                $cabsInventory->save();
                            }
                        }
                    }
                } else {
                    $cab_id = $booking_details->cab_id;
                    $cab_route_id = $booking_details->cab_route_id;
                    $cab_route_group_id = $booking_details->cab_route_group_id;
                    $price_id = $order->package_type;
                    $trip_date = CustomHelper::DateFormat( $order->trip_date, 'Y-m-d');
                    $CabInventory = CustomHelper::AvailbleCabInventory($cab_route_group_id,$cab_id,$trip_date);
                    $inventory_id = $CabInventory->id;
                    $inventory = $order->inventory;
                    $old_booked_inventory = $CabInventory->booked ?? 0 ;
                    if($is_revort == 1){
                        $booked_inventory  = $old_booked_inventory - $inventory;
                    }else{
                        $booked_inventory  = $old_booked_inventory + $inventory;
                    }
                    $cab_inventory = array('booked' => $booked_inventory, );
                    $isUpdateinventory = CabRouteInventory::where('id',$inventory_id)->update($cab_inventory);
                }                
            }
        } elseif($order->order_type == 8) {
             //Rental-booking
            $user_id = $order->user_id;
            $inventory = $order->inventory;
            if($order->booking_details){
                $booking_details = json_decode($order->booking_details);
                $bike_id   = $booking_details->bike_id;
                $city_id   = $booking_details->city_id;
                $pickupDate = $booking_details->pickupDate;
                $dropDate = $booking_details->dropDate;
                $period = CustomHelper::DateRange($pickupDate,$dropDate);
                $date_arr = [];
                foreach ($period as $key => $value) {
                    $date_arr[] = $value->format('Y-m-d');
                }
                $inventory_data =BikeCityInventory::where('bike_id',$bike_id)->where('city_id',$city_id)->whereIn('trip_date',$date_arr)->get();
                foreach ($inventory_data as $key => $row) {
                 $BikeCityInventory = BikeCityInventory::find($row->id);
                 $old_booked_inventory = $BikeCityInventory->booked ?? 0 ;
                 if($is_revort == 1){
                    $booked_inventory  = $old_booked_inventory - $inventory;
                }else{
                    $booked_inventory  = $old_booked_inventory + $inventory;
                }
                 $BikeCityInventory->booked = $booked_inventory;
                 $BikeCityInventory->save();

             }
         }

     } elseif($order->order_type == 5) {
            $booking_details = json_decode($order->booking_details);
            $inventory_id = $booking_details->inventory_id ?? 0;
            $checkout = $booking_details->checkout ? CustomHelper::DateFormat($booking_details->checkout,'Y-m-d','d/m/Y') : '';
            $checkin = $booking_details->checkin ? CustomHelper::DateFormat($booking_details->checkin,'Y-m-d','d/m/Y') : '';

            $inventory_row = AccommodationInventory::find($inventory_id);
            $room_id = $inventory_row->room_id;
            $plan_id = $inventory_row->plan_id;
            $accommodation_id = $inventory_row->accommodation_id;
            $inventory = $order->inventory ;

            $query = AccommodationInventory::where('status',1);
            $query->where('room_id',$room_id);
            if($plan_id) {
                $query->where('plan_id',(int)$plan_id);
            }
            $date_arr = [];
            if($checkin && $checkout) {
                $checkout_date = date('Y-m-d', strtotime($checkout. ' - 1 days'));
                $period = CustomHelper::DateRange($checkin,$checkout_date);
                foreach ($period as $key => $value) {
                    $date_arr[] = $value->format('Y-m-d');
                }
                $query->whereIn('date',$date_arr);
            } else {
                $date_arr[] = $checkin;
                $query->whereIn('date',$date_arr);
            }
            $inventory_data = $query->orderBy('date','ASC')->get();
            foreach ($inventory_data as $key => $row) {
                $AccommodationInventory = AccommodationInventory::find($row->id);
                $old_booked_inventory = $AccommodationInventory->booked ?? 0 ;
                if($is_revort == 1){
                    $booked_inventory  = $old_booked_inventory - $inventory;
                }else{
                    $booked_inventory  = $old_booked_inventory + $inventory;
                }
                $AccommodationInventory->booked = $booked_inventory;
                $AccommodationInventory->save();
            }
        }
    }

    public static function sendBookingEmail($orderNo) {
        if(!empty($orderNo)) {
            $data = [];
            $sms_template_slug = '';
            $order = Order::where(DB::raw('sha1(id)'), $orderNo)->first();
            $order_id = $order->id;
            if(!empty($order) &&  $order->payment_status == 1) {
                $order_type = $order->order_type??'1';
                $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);

                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo && $userAgentInfo->count() > 0){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';

                        $userData = $userAgentInfo->User ?? '';
                        $agent_phone = '';
                        $agent_email = '';
                        if($userData->phone) {
                            $country_code = $userData->country_code ?? '91';
                            $agent_phone = '+'.$country_code.'-'.$userData->phone;
                        }
                        $agent_email = !empty($userData->email)?$userData->email:'';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = asset('/storage/'.$path.$agentLogo);
                        }
                    }
                }

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                     '{company_logo}' => $logoSrc
                 );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

              $contact_details = '';
              if(!empty($agent_id)){
                $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                $b2b_email_params = array(
                    '{agent_phone}' => $agent_phone,
                    '{agent_email}' => $agent_email
                );
                $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
               /* $contact_details .= 
                '<tr>
                 <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                 <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

                 <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
                 </td>
                 </tr>';*/
             }else{

                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $SYSTEM_TITLE
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                /*$contact_details .= '<tr>
                <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
                <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

                <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$SYSTEM_TITLE.'. All Rights Reserved.</p>
                </td>
                </tr>';*/
            }

                $content = '';
                $record_name = '';
                $form_values = [];
                $form_values['{header}'] = $common_logo??'';
                $form_values['{logo}'] = $logoSrc??'';
                $form_values['{contact_details}'] = $contact_details??'';
                $form_values['{e_date}'] = date("l jS \of F Y h:i A");
                $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
                $form_values['{name}'] = $order->name;
                $form_values['{email}'] = $order->email;
                $phone = '';
                if($order->phone) {
                    $phone_country = $order->phone_country ?? '91';
                    $phone = '+'.$phone_country.'-'.$order->phone;
                }
                $form_values['{phone}'] = $phone;

                if($order_type == 2) { // Pay-online
                    $record_name = 'Booking Pay Online';
                    $template_slug = 'pay-online-success-transaction';
                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $form_values['{transaction_id}'] = !empty($payment_details) ? $payment_details->txn_id : "";
                    $form_values['{sub_total_amount}'] = !empty($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : "";
                    $form_values['{payment_date}'] = $payment_details->payment_date??'';
                    $form_values['{total_amount}'] = !empty($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : "";
                    // $form_values['{method}'] = ($order->method == 1) ? "PAYPAL":"BANK TRANSFER";
                    $form_values['{method}'] = CustomHelper::getPaymentGatewayName($order->method);
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{phone_number}'] = $phone;
                    $form_values['{package_name}'] = $payment_details->itemname ?? '';
                    $form_values['{payment_status}'] = $p_status;
                    $form_values['{payer_name}'] = $order->name??'';
                    $form_values['{comment}'] = $order->comment??'';
                    $form_values['{payment_acc_payer_name}'] = $order->name??'';
                    $form_values['{payer_email}'] = $order->email??'';
                    $form_values['{paypal_id}'] = '';
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{date}'] = date("l jS \of F Y h:i A");

                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

                    //Save Orders Status
                    $order->orders_status = CustomHelper::getOrderStatus('SUCCESS');
                    $order->status = 'SUCCESS';
                    $order->save();

                } elseif($order_type == 3) { //Flight
                    $record_name = 'Booking Flight';
                    $sms_template_slug = 'flight-booking-sms';
                    $params = [];
                    $params['order'] = $order;

                    if($order->booking_details) {
                        $params['booking_details'] = json_decode($order->booking_details, true);
                    }
                    $itineraries = view('emails._flight_booking_email',$params)->render();

                    $seo_tags = CustomHelper::getSeoModules('flight');

                    $manager_email = $seo_tags->admin_email??'';

                    $form_values['{city}'] = $order->city;
                    if($order->trip_date) {
                        $form_values['{date}'] = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $form_values['{date}'] = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $form_values['{total_amount}'] = CustomHelper::getPrice($order->total_amount);
                    $form_values['{itineraries}'] = $itineraries;
                    $form_values['{booking_id}'] = $order->order_no??'';

                    $email_template = EmailTemplate::where('slug','flight-booking-email')->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();
                    $sms_content_arr = [];
                    $booking_details = json_decode($order->booking_details, true);
                    if(isset($booking_details['itemInfos']['AIR']['tripInfos']) && !empty($booking_details['itemInfos']['AIR']['tripInfos'])) {
                        foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo) {
                            $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                            $dep_code = $tripInfo['sI'][0]['da']['code']??'';
                            $arr_code = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
                            $sector = $dep_code.'-'.$arr_code;
                            $dep = $tripInfo['sI'][0]['dt']??'';
                            $arr = $tripInfo['sI'][count($tripInfo['sI'])-1]['at']??'';
                            $flight = $tripInfo['sI'][0]['fD']['aI']['name']??'';
                            $pnr = $travellerInfos[0]['pnrDetails'][$sector]??'';
                            $customer = ($travellerInfos[0]['fN'].' '.$travellerInfos[0]['lN'])?? '';
                            if(count($travellerInfos) > 1) {
                                $customer = $customer . ' +'.(count($travellerInfos)-1).' ';
                            }

                            $sms_params = array(
                                '{#var1#}' => $sector,
                                '{#var2#}' => $dep,
                                '{#var3#}' => $arr,
                                '{#var4#}' => $flight,
                                '{#var5#}' => $pnr,
                                '{#var6#}' => $customer,
                            );

                            $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                            $content = strtr($sms_content, $sms_params);
                            // $content = "Flight - Your ticket details for Sector : ".$sector." Dep:".$dep." Arr: ".$arr." Flight: ".$flight." PNR: ".$pnr." Customer: ".$customer." overlandescape.com";
                            $sms_content_arr[] = $content;
                        }
                    }
                    // prd($sms_content_arr);

                    $pdf = PDF::loadView("emails._flight_booking_pdf", $params);

                    $path = 'temp/';
                    $pdf_name = $order->order_no.'.pdf';
                    if (!File::exists(public_path("storage/" . $path))) {
                        File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
                    }
                    $pdf->save(public_path("storage/" . $path).$pdf_name);
                    $attachments = public_path("storage/".$path).$pdf_name;
                } else if($order_type == 8) { // Rental
                    $total_amount = $order->total_amount??0;
                    $paid_amount = $order->partial_amount??0;
                    if(!empty($agent_id)) {
                        $total_amount = $order->sub_total_amount??0;
                        $paid_amount = $order->partial_amount??0;
                        if($order->total_amount == $order->partial_amount) {
                            $paid_amount = $total_amount;
                        }
                    }
                    $total_amount = CustomHelper::getPrice($total_amount);
                    $paid_amount = CustomHelper::getPrice($paid_amount);

                    $trip_date = '';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');

                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $record_name = 'Rental Booking';
                    $template_slug = 'common-order-paid-email';
                    $sms_template_slug = 'rental-booking-sms';
                    $booking_details = $order->booking_details ?? '';
                    $booking_details_arr = json_decode($booking_details);

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;
                    $form_values['{trip_date}'] =  CustomHelper::DateFormat($booking_details_arr->pickupDate,'d/m/Y')??'';
                    $form_values['{booking_for}'] = 'Rental';
                    $form_values['{booking_id}']  = $order->order_no??'';
                    $form_values['{order_no}'] = $order->order_no??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    $form_values['{package}']  = $booking_details_arr->bike_name??'';
                    if($drop_address){
                      $drop_address = '/'.$drop_address;
                    }
                    $form_values['{drop_address}'] = $drop_address??'';
                    $form_values['{duration}'] = $booking_details_arr->duration ?? '';
                    $form_values['{persons}'] = '';
                    $form_values['{start_time}'] = CustomHelper::DateFormat($booking_details_arr->pickupDate,'d/m/Y h:i A');
                    $form_values['{end_time}'] = CustomHelper::DateFormat($booking_details_arr->dropDate,'d/m/Y h:i A');
                    $form_values['{total_amount}'] = $total_amount;
                    $form_values['{paid_amount}'] = $paid_amount;
                    $bike_name = $booking_details_arr->bike_name??'';
                     if($bike_name){
                      $bike_name = '/'.$bike_name;
                    }
                    $form_values['{cab}'] = $bike_name??'';
                    $form_values['{pickup_address}'] = '';
                    $city_name = $booking_details_arr->city_name??'';
                    if($city_name){
                      $city_name = '/'.$city_name;
                    }
                    $form_values['{city}'] = $city_name??'';
                    $form_values['{destination}'] = $booking_details_arr->location_name??'';
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{address}'] = '';
                    $form_values['{trip_type}'] = '';
                    $form_values['{payment_status}'] = $p_status??'';
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';

                    $pickup_address = $booking_details_arr->location_name??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    $origin = $booking_details_arr->city_name??'';
                    $destination = $booking_details_arr->destination??'';
                    $order_no = $order->order_no??'';
                    $customer_name = $order->name??'';
                    $drop_destination_address = $drop_address."( ".$destination." )";

                    $trip_date_time = '';
                    if($order->trip_date) {
                        $trip_date_time = date('h:i A d M', strtotime($order->trip_date));
                        $trip_time = date('d M', strtotime($order->trip_date));
                    } else {
                        $trip_date_time = date('h:i A d M', strtotime($order->created_at));
                        $trip_time = date('d M', strtotime($order->created_at));
                    }
                    $sms_params = array(
                        '{#var1#}' => $origin,
                        '{#var2#}' => $pickup_address,
                        '{#var3#}' => $trip_date_time,
                        '{#var4#}' => $order_no, //drop_destination_address
                        '{#var5#}' => $customer_name, //trip_time
                    );
                    // $content = "Cab - Your cab details for Pickup: ".$origin." from ".$pickup_address." Time:".$trip_date_time."; Drop: ".$drop_destination_address." on ".$trip_time." Booking ID: ".$order_no." Customer: ".$customer_name." - overlandescape.com";
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }

                    $seo_tags = CustomHelper::getSeoModules('cab');
                    $manager_email = $seo_tags->admin_email??'';
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();                    
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);
                } else if($order_type == 4) { // Cab
                    $total_amount = $order->total_amount??0;
                    $paid_amount = $order->partial_amount??0;
                    if(!empty($agent_id)) {
                        $total_amount = $order->sub_total_amount??0;
                        $paid_amount = $order->partial_amount??0;
                        if($order->total_amount == $order->partial_amount) {
                            $paid_amount = $total_amount;
                        }
                    }
                    $total_amount = CustomHelper::getPrice($total_amount);
                    $paid_amount = CustomHelper::getPrice($paid_amount);

                    $trip_date = '';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');

                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $record_name = 'Booking Cab';
                    $template_slug = 'common-order-paid-email';
                    $sms_template_slug = 'cab-booking-sms';
                    $booking_details = $order->booking_details ?? '';
                    $booking_details_arr = json_decode($booking_details);

                    $paxinfo = '';
                    if($booking_details_arr->adult || $booking_details_arr->children) {
                        $paxinfo .= 'Total Pax:';
                        if($booking_details_arr->adult && $booking_details_arr->children) {
                            $paxinfo .= $booking_details_arr->adult.' Adults, '.$booking_details_arr->children.' Child';
                        } else if($booking_details_arr->adult) {
                            $paxinfo .= $booking_details_arr->adult.' Adults';
                        } else if($booking_details_arr->children) {
                            $paxinfo .= $booking_details_arr->children.' Child';
                        }
                    }

                    $addons = '';
                    if(isset($booking_details_arr->selected_addons) && !empty($booking_details_arr->selected_addons)) {
                        foreach($booking_details_arr->selected_addons as $addon) {
                            $addon_str = $addon->name.': Qty:'.$addon->qty;
                            if(isset($addon->daily_rental)) {
                                $addon_str .= ' Days:'.$addon->days;
                            }
                            $addons .= $addon_str.' <br />';
                        }
                    }

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;
                    $form_values['{trip_date}'] = $trip_date;
                    $form_values['{booking_for}'] = 'Cab';
                    $form_values['{booking_id}']  = $order->order_no??'';
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{package}']  = $booking_details_arr->origin.' to '.$booking_details_arr->destination;
                    $form_values['{duration}'] = $booking_details_arr->duration??'';
                    $form_values['{persons}'] = '';
                    $form_values['{start_time}'] = '';
                    $form_values['{end_time}'] = '';
                    $form_values['{total_amount}'] = $total_amount;
                    $form_values['{paid_amount}'] = $paid_amount;
                    $cab_name = $booking_details_arr->cab_name??'';
                    if($cab_name){
                      $cab_name = '/'.$cab_name;
                    }
                    $form_values['{cab}'] = $cab_name??'';
                    $form_values['{service_city}'] = $booking_details_arr->origin??'';
                    $form_values['{destination}'] = $booking_details_arr->destination??'';
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email??'';
                    $city = $order->city??'';
                    if($city){
                       $city = '/'.$city;
                    }
                    $form_values['{city}'] = $city??'';
                    $pickup_address = $booking_details_arr->pickup_address??'';
                    if($pickup_address){
                        $pickup_address = '/'.$pickup_address;
                    }
                    $form_values['{pickup_address}'] = $pickup_address??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    if($drop_address){
                        $drop_address = '/'.$drop_address;
                    }
                    $form_values['{drop_address}'] = $drop_address??'';
                    $form_values['{address}'] = $order->address1??'';
                    $form_values['{trip_type}'] = $booking_details_arr->trip_type??'';
                    $form_values['{paxInfo}'] = $paxinfo;
                    $form_values['{addOns}'] = $addons;
                    $form_values['{payment_status}'] = $p_status??'';
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';

                    $pickup_address = $booking_details_arr->pickup_address??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    $origin = $booking_details_arr->origin??'';
                    $destination = $booking_details_arr->destination??'';
                    $order_no = $order->order_no??'';
                    $customer_name = $order->name??'';
                    $drop_destination_address = $drop_address."( ".$destination." )";

                    $trip_date_time = '';
                    if($order->trip_date) {
                        $trip_date_time = date('h:i A d M', strtotime($order->trip_date));
                        $trip_time = date('d M', strtotime($order->trip_date));
                    } else {
                        $trip_date_time = date('h:i A d M', strtotime($order->created_at));
                        $trip_time = date('d M', strtotime($order->created_at));
                    }

                    $sms_params = array(
                        '{#var1#}' => $origin,
                        '{#var2#}' => $pickup_address,
                        '{#var3#}' => $trip_date_time,
                        '{#var4#}' => $drop_destination_address,
                        '{#var5#}' => $trip_time??'',
                        '{#var6#}' => $order_no,
                        '{#var7#}' => $customer_name,
                    );
                    // $content = "Cab - Your cab details for Pickup: ".$origin." from ".$pickup_address." Time:".$trip_date_time."; Drop: ".$drop_destination_address." on ".$trip_time." Booking ID: ".$order_no." Customer: ".$customer_name." - overlandescape.com";
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                if(is_array($val)) {
                                    $val = json_encode($val);
                                }
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }

                    $seo_tags = CustomHelper::getSeoModules('cab');

                    $manager_email = $seo_tags->admin_email??'';

                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();                    
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);
                } else if($order_type == 5) { // Hotel
                    $total_amount = $order->total_amount??0;
                    $paid_amount = $order->partial_amount??0;
                    if(!empty($agent_id)) {
                        $total_amount = $order->sub_total_amount??0;
                        $paid_amount = $order->partial_amount??0;
                        if($order->total_amount == $order->partial_amount) {
                            $paid_amount = $total_amount;
                        }
                    }
                    $total_amount = CustomHelper::getPrice($total_amount);
                    $paid_amount = CustomHelper::getPrice($paid_amount);

                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        $accommodation_id = $booking_details->hotel_id?? 0;
                        $accommodation_data = Accommodation::find($accommodation_id);
                        $booking_mode = $accommodation_data->booking_mode?? 1;
                        if($booking_mode === 0 && ($order->total_amount == $order->partial_amount)){
                                //vocher create for automatic hotel 
                            $order_no = $order->order_no?? '';
                            CustomHelper::createVoucher($order_no);
                            
                            //Order Status=====
                            // $order->orders_status = CustomHelper::getOrderStatus('Processing');
                            $order->orders_status = CustomHelper::getOrderStatus('voucher sent');
                            $order->status = 'Processing';
                            $order->save();
                            //======
                        }
                    }

                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');

                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $record_name = 'Booking Hotel';
                    $template_slug = 'common-order-paid-email';
                    $sms_template_slug = 'hotel-booking-sms';
                    $booking_details = $order->booking_details ?? '';
                    $booking_details_arr = json_decode($booking_details);
                    $city = $booking_details_arr->destination_name;

                    $adult = !empty($booking_details_arr->adult) ? $booking_details_arr->adult:'';
                    $child = !empty($booking_details_arr->child) ? $booking_details_arr->child :'';

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;

                    $form_values['{booking_for}'] = 'Hotel';
                    $form_values['{booking_id}']  = $order->order_no??'';
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{total_amount}'] = $total_amount;
                    $form_values['{paid_amount}'] = $paid_amount;
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{city}'] = $city??'';
                    $form_values['{address}'] = $order->address1??'';
                    $form_values['{trip_type}'] = $booking_details_arr->trip_type??'';
                    $form_values['{payment_status}'] = $p_status??'';
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';

                    $form_values['{package}'] = $booking_details_arr->hotel_name ?? '';
                    if($child){
                        $child = '/'.$child." Child";
                    }
                    $form_values['{persons}'] = $adult.' '.'Adult'.' '.$child;
                    $form_values['{pickup_address}'] = '';
                    $form_values['{drop_address}'] = '';
                    $form_values['{cab}'] = '';
                    

                    $order_no = isset($order->order_no) ? $order->order_no : '';
                    $checkin =  !empty($booking_details_arr->checkin) ? CustomHelper::DateFormat($booking_details_arr->checkin,'d-M-Y','d/m/Y') : '';
                    $checkout =  !empty($booking_details_arr->checkout) ? CustomHelper::DateFormat($booking_details_arr->checkout,'d-M-Y','d/m/Y') : '';
                    $hotel_name = isset($booking_details_arr->hotel_name) ? $booking_details_arr->hotel_name : '';
                    $guest_name = isset($order->name) ? $order->name : '';
                    $adult = isset($booking_details_arr->adult) ? $booking_details_arr->adult-1 : '';
                    $child = isset($booking_details_arr->child) ? $booking_details_arr->child : 0;
                    if(isset($adult) && $adult >= 1){
                        $extra_guests = $guest_name." + ".$adult+$child;
                    } else {
                        $extra_guests = $guest_name;
                    }
                    $sms_params = array(
                        '{#var1#}' => $order_no,
                        '{#var2#}' => $checkin,
                        '{#var3#}' => $checkout,
                        '{#var4#}' => $hotel_name,
                        '{#var5#}' => $extra_guests,
                    );
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }

                    $trip_date = '';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    }else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $form_values['{trip_date}'] = $trip_date;

                    $hotel_id = $booking_details->hotel_id??'';
                    $hotel_info = Accommodation::find($hotel_id);
                    $checkin_time = $hotel_info->checkin_time ?? '';
                    $checkout_time = $hotel_info->checkout_time ?? '';
                    $contact_email = $hotel_info->contact_email ?? '';

                    $seo_tags = CustomHelper::getSeoModules('hotel_listing');

                    $manager_email = $seo_tags->admin_email??'';

                    //duration
                    $checkin_show = strtotime($checkout);
                    $checkout_show =  strtotime($checkin);
                    $days_between = ceil(abs($checkout_show - $checkin_show) / 86400);
                    $form_values['{duration}'] = $days_between.' Days';

                    $form_values['{start_time}'] = !empty($checkin_time)?date('h:i A',strtotime($checkin_time)):'';
                    $form_values['{end_time}'] = !empty($checkout_time)?date('h:i A',strtotime($checkout_time)):'';                     

                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();

                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);

                } else if($order_type == 7) { //Wallet
                    $record_name = 'Wallet Recharged';
                    $template_slug = 'wallet-email';
                    $sms_template_slug = 'wallet-sms-update';
                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $form_values['{transaction_id}'] = !empty($payment_details) ? $payment_details->txn_id : "";
                    $form_values['{sub_total_amount}'] = !empty($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : "";
                    $form_values['{payment_date}'] = $payment_details->payment_date??'';
                    $form_values['{total_amount}'] = !empty($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : "";
                    // $form_values['{method}'] = ($order->method == 1) ? "PAYPAL":"BANK TRANSFER";
                    $form_values['{method}'] = CustomHelper::getPaymentGatewayName($order->method);
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{phone_number}'] = $phone;
                    $form_values['{package_name}'] = $payment_details->itemname ?? '';
                    $form_values['{payment_status}'] = $p_status;
                    $form_values['{payer_name}'] = $order->name??'';
                    $form_values['{comment}'] = $order->comment??'';
                    $form_values['{payment_acc_payer_name}'] = $order->name??'';
                    $form_values['{payer_email}'] = $order->email??'';
                    $form_values['{paypal_id}'] = '';
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{date}'] = date("l jS \of F Y h:i A");

                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();

                    $actual_amt = $order->sub_total_amount??0;
                    $userBalance = UserWallet::where('user_id', $order->user_id)->sum('amount');
                    
                    $sms_params = array(
                        '{#var1#}' => CustomHelper::getPrice($actual_amt)??0,
                        '{#var2#}' => date("d-M-Y h:i A"),
                        '{#var3#}' => CustomHelper::getPrice($userBalance)??0,
                    );

                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);

                    //Save Orders Status
                    $order->orders_status = CustomHelper::getOrderStatus('SUCCESS');
                    $order->status = 'SUCCESS';
                    $order->save();

                }  else { //Package and activity payment
                    $total_amount = $order->total_amount??0;
                    $paid_amount = $order->partial_amount??0;

                    if(!empty($agent_id)) {
                        $total_amount = $order->sub_total_amount??0;
                        $paid_amount = $order->partial_amount??0;
                        if($order->total_amount == $order->partial_amount) {
                            $paid_amount = $total_amount;
                        }
                    }
                    $total_amount = CustomHelper::getPrice($total_amount);
                    $paid_amount = CustomHelper::getPrice($paid_amount);
                    $trip_date = '';
                    $template_slug = 'common-order-paid-email';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }
                    $booking_for = (isset($order->Package->is_activity) && $order->Package->is_activity == 1) ? "Activity" : "Package";
                    $record_name = $booking_for.' '.'Booking';

                    $booking_data = $order->booking_details ?? '';
                    $booking_details = json_decode($booking_data);
                    if($order->Package->is_activity == 1){
                        $duration = !empty($booking_details->duration) ? $booking_details->duration : "";
                        $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax : "";
                        $form_values['{persons}'] = $total_pax;
                        $date_val = !empty($booking_details->start_time) ? date('d-M-Y', strtotime($booking_details->start_time)):'';
                        $time_val = !empty($booking_details->start_time) ? date('h:i A', strtotime($booking_details->start_time)):'';
                        $end_time = !empty($booking_details->end_time)?date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';
                        $order_package_name = !empty($order->package_name) ? CustomHelper::wordsLimit($order->package_name, 22) : '';
                        $sms_template_slug = 'activity-booking-sms';
                        $sms_params = array(
                            '{#var1#}' => $order_package_name??'',
                            '{#var2#}' => $order->order_no??0,
                            '{#var3#}' => $date_val??'',
                            '{#var4#}' => $time_val??'',
                            '{#var5#}' => $duration??'',
                            '{#var6#}' => $total_pax??'',
                        );

                        $seo_tags = CustomHelper::getSeoModules('activity_listing');
                        $manager_email = $seo_tags->admin_email??'';

                        $package_id = $order->package_id?? 0;
                        $package_data = Package::find($package_id);
                        $contact_email = $package_data->contact_email?? '';

                        $booking_mode = $package_data->booking_mode?? 1;
                        if($booking_mode === 0 && ($order->total_amount == $order->partial_amount)){
                        //vocher create for automatic Activity 
                            $order_no = $order->order_no?? '';
                            CustomHelper::createVoucher($order_no);
                        //Order Status=====
                            // $order->orders_status = CustomHelper::getOrderStatus('Processing');
                            /*$order->orders_status = CustomHelper::getOrderStatus('voucher sent');
                            $order->status = 'Processing';
                            $order->save();*/
                         //======
                        }
                    }else{
                        $duration = (isset($order->Package->package_duration) && !empty($order->Package->package_duration)) ? $order->Package->package_duration : "";
                        $start_time = '';
                        $end_time = '';
                        $destination = (isset($order->Package->packageDestination->name) && !empty($order->Package->packageDestination->name)) ? $order->Package->packageDestination->name : "";

                        $contact_email_data = $order->Package??'';
                        $contact_email = $contact_email_data->contact_email??'';

                        $seo_tags = CustomHelper::getSeoModules('package_listing');
                        $manager_email = $seo_tags->admin_email??'';

                        $start_time_val = !empty($booking_details->start_time) ? date('d-M-Y h:i A', strtotime($booking_details->start_time)): date('d-M-Y h:i A', strtotime($order->trip_date));
                        $end_time_val = !empty($booking_details->end_time) ? date('d-M-Y h:i A',strtotime($booking_details->end_time)):'N/A';
                        $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax-1 : "";
                        $order_package_name = !empty($order->package_name) ? CustomHelper::wordsLimit($order->package_name, 22) : '';
                        $show_total_pax = '';
                        if($total_pax >= 1){
                            $show_total_pax = $order->name." + ".$total_pax;
                        }else{
                            $show_total_pax = $order->name ?? '';
                        }
                        $sms_template_slug = 'holiday-booking-sms';
                        $sms_params = array(
                            '{#var1#}' => $order->order_no??'',
                            '{#var2#}' => $destination??'',
                            '{#var3#}' => $start_time_val??'',
                            '{#var4#}' => $end_time_val??'',
                            '{#var5#}' => $order_package_name??'',
                            '{#var6#}' => $show_total_pax??'',
                        );

                        $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax : "";

                        $form_values['{persons}'] = $total_pax;
                    }

                    //$start_time = date('D, M dS Y h:i A',strtotime($booking_details->start_time)):'';
                    //$end_time = date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';

                    $order_address1 = (isset($order->address1) && !empty($order->address1)) ? $order->address1 : "";
                    $order_address2 = (isset($order->address2) && !empty($order->address2)) ? $order->address2 : "";
                    $order_addresses = $order_address1.", ".$order_address2;
                    $destination = (isset($order->Package->packageDestination->name) && !empty($order->Package->packageDestination->name)) ? $order->Package->packageDestination->name : "";


                    /*$total_pax_unit = CustomHelper::getTotalPaxUnit($order);
                    if($total_pax_unit > 0){
                        $total_pax_unit = '';
                    }*/

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;
                    $form_values['{trip_date}'] = $trip_date;
                    $form_values['{booking_for}'] = $booking_for;
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{package}'] = $order->package_name ?? '';
                    $form_values['{duration}'] = $duration;
                    $form_values['{total_amount}'] = $total_amount;
                    $form_values['{paid_amount}'] = $paid_amount;
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email ?? '';
                    // $form_values['{city}'] = $order->city ?? '';
                    $form_values['{city}'] = $destination ?? '';
                    $form_values['{pickup_address}'] = '';
                    $form_values['{drop_address}'] = '';
                    $form_values['{cab}'] = '';
                    $form_values['{address}'] = $order_addresses ?? '';
                    $form_values['{payment_status}'] = $p_status;
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';
                    // $form_values['{transaction_id}'] = !empty($payment_details) ? $payment_details->txn_id : "";
                    // $form_values['{sub_total_amount}'] = !empty($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : "";
                    // $form_values['{payment_date}'] = $payment_details->payment_date??'';
                    // $form_values['{total_amount}'] = !empty($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : "";
                    // // $form_values['{method}'] = ($order->method == 1) ? "PAYPAL":"BANK TRANSFER";
                    // $form_values['{method}'] = $order->method;
                    // $form_values['{order_no}'] = $order->order_no??'';
                    // $form_values['{package_name}'] = $payment_details->itemname ?? '';
                    // $form_values['{payment_status}'] = $p_status;
                    // $form_values['{payer_name}'] = $order->name??'';
                    // $form_values['{comment}'] = $order->comment??'';
                    // $form_values['{payment_acc_payer_name}'] = $order->name??'';
                    // $form_values['{payer_email}'] = $order->email??'';
                    // $form_values['{paypal_id}'] = '';
                    // $form_values['{email}'] = $order->email??'';
                    // $form_values['{date}'] = date("l jS \of F Y h:i A");
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    if($order->Package->is_activity == 1){
                    $start_time = !empty($booking_details->start_time)?date('D, M dS Y h:i A',strtotime($booking_details->start_time)):'';
                    $end_time = !empty($booking_details->end_time)?date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';
                    }
                    $form_values['{start_time}'] = $start_time;
                    $form_values['{end_time}'] = $end_time;
                    // $email_template = EmailTemplate::where('slug', 'transaction-successfull-email')->where('status', 1)->first();
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();

                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);
                }

                if(isset($sms_content_data) && !empty($sms_content_data)) {
                    if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                        $sms_content_arr = $sms_content_arr;
                    } else if(!empty($content)) {
                        $sms_content_arr = [];
                        $sms_content_arr[] = $content;
                    }
                    if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                        foreach($sms_content_arr as $content) {
                            if(!empty($content)) {
                                $content = urlencode($content);
                                $params  = [];
                                $params['phone'] = $phone??'';
                                $params['content'] = $content;
                                $params['template_id'] = $sms_content_data->template_id??'';
                                $is_sms_sent = CustomHelper::send_sms($params);
                            }
                        }
                    }
                }

                if(!empty($order->email) && !empty($email_template)) {
                    $email = $order->email;
                    $cc_email = '';
                    $bcc_email_arr = [];
                    if($order->inventory_id) {
                        $supplier_email = $order->supplierData->email??'';
                        if($supplier_email) {
                            $bcc_email_arr[] = $supplier_email;
                        }
                    }
                    $search_arr = array_keys($form_values)??[];
                    $replace_arr = array_values($form_values)??[];
                    $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
                    $search_arr = array_keys($form_values)??[];
                    $replace_arr = array_values($form_values)??[];
                    $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
                    $email_bcc_admin = $email_template->bcc_admin ?? 0;
                    $bcc_manager_email = $email_template->manager_email ?? 0;
                    $bcc_contact_email = $email_template->contact_email ?? 0;
                    $email_type = isset($email_template->email_type) ? $email_template->email_type : '';                    

                    //Dynamic send mail customer or Admin
                    if($email_type == 'admin'){
                        $email = $ADMIN_EMAIL;
                    }

                    if($email_bcc_admin == 1 && !empty($ADMIN_EMAIL)) {
                        $bcc_email_arr[] = $ADMIN_EMAIL??'';
                    }

                    if($bcc_contact_email == 1 && !empty($contact_email)) {
                        $bcc_email_arr[] = $contact_email??'';
                    }

                    if($bcc_manager_email == 1 && !empty($manager_email)) {
                        $bcc_email_arr[] = $manager_email??'';
                    }
                    $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';

                    // Email to Admin
                    $params = [];
                    $params['to'] = $email;
                    $params['reply_to'] = $ADMIN_EMAIL;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    if(isset($attachments)) {
                        $params['attachments'] = $attachments;
                    }
                    $params['module_name'] = $record_name;
                    $params['record_id'] = $order->id ?? 0;
                    $isSent = CustomHelper::sendCommonMail($params);
                }
            }
        }    
    }

    public static function bookFlight($order_id,$type='book') {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $order = Order::find($order_id);
        if($order) {
            $supplier_id = $order->supplier_id??0;
            $inventory_id = $order->inventory_id??0;
            $userId = $order->user_id;
            $name = $order->name;
            $agent_id = 0;
            $user = auth::guard('admin')->user();
            if($user && $user->id) {
                $created_type = 'backend';
                $user_id = $user->id??0;
                $user_name = $user->name??'system';
            } else {
                $created_type = 'customer';
                $user = auth()->user();
                $user_id = $user->id??0;
                if($user_id) {
                    $user_name = $user->name??'system';
                    $is_agent = $user->is_agent??0;
                    $approved_agent = $user->approved_agent??0;
                    if($is_agent == 1 && $approved_agent == 1){
                        $agent_id = $user_id;
                        $created_type = 'agent';
                    }
                } else {
                    $user_id = $userId;
                    $user_name = $name;
                }
            }

            if($order->flight_details) {
                $flight_details = json_decode($order->flight_details);
                $price_id = $flight_details->price_id??'';
                if($price_id) {
                    if(is_array($price_id)) {
                        $priceIds = $price_id;
                    } else {
                        $priceIds = explode(',', $price_id);
                    }
                    $review_slug = CustomHelper::slugify(implode(',', $priceIds));
                    $resp = CustomHelper::flightReviewDetails($review_slug, $priceIds);
                    $apiResult = json_decode($resp, true);
                    $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                    $success = !empty($apiResult)?$apiResult['status']['success']:'';
                    if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                        $tripInfos = $apiResult;
                        $bookingId = $tripInfos['bookingId']??'';

                        $booking_status = '';
                        if($bookingId && empty($inventory_id)) {
                            $reqParams = [
                                "bookingId" => $bookingId,
                                "requirePaxPricing" => true
                            ];
                            $endPoint = '/oms/v1/booking-details';
                            $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                            $apiResult = json_decode($resp, true);
                            $statusCode = $apiResult['status']['httpStatus']??'';
                            $success = $apiResult['status']['success']??'';
                            if ($statusCode == 200) {
                                $booking_status = $apiResult['order']['status']??'';
                            }
                        }

                        if($booking_status == 'SUCCESS' || $booking_status == 'PENDING') {
                            $response['message'] = 'Booking '.$booking_status.' - bookingId - '.$bookingId;
                            $response['success'] = true;
                            $response['bookingId'] = $bookingId;
                        } else {
                            $total_amount = $order->supplier_total_amount;

                            $OrderTraveller = $order->OrderTraveller??[];

                            $travellerInfo = [];
                            if(!empty($OrderTraveller)) {
                                foreach($OrderTraveller as $traveller) {
                                    $traveller_info = [
                                        "ti" => $traveller->title,
                                        "fN" => $traveller->first_name,
                                        "lN" => $traveller->last_name,
                                        "pt"  => $traveller->pax_type,
                                    ];
                                    if($traveller->dob) {
                                        $traveller_info["dob"] = $traveller->dob;
                                    }
                                    if($traveller->passport_nationality) {
                                        $traveller_info["pNat"] = $traveller->passport_nationality;
                                    }
                                    if($traveller->passport_no) {
                                        $traveller_info["pNum"] = $traveller->passport_no;
                                    }
                                    if($traveller->passport_issue_date) {
                                        $traveller_info["pid"] = $traveller->passport_issue_date;
                                    }
                                    if($traveller->passport_exp_date) {
                                        $traveller_info["eD"] = $traveller->passport_exp_date;
                                    }

                                    if($traveller->ssrBaggageInfos) {
                                        $ssrBaggageInfos = (array)json_decode($traveller->ssrBaggageInfos,true);
                                        if($ssrBaggageInfos) {
                                            $ssrBaggageInfos_arr = [];
                                            foreach($tripInfos['tripInfos'] as $tripInfo) {
                                                foreach($tripInfo['sI'] as $flightData) {
                                                    $key = $flightData['da']['code'].'-'.$flightData['aa']['code'];
                                                    if(isset($ssrBaggageInfos[$key])) {
                                                        $ssrBaggageInfos_arr[] = [
                                                            'key' => $flightData['id'],
                                                            'code' => $ssrBaggageInfos[$key]['code']
                                                        ];
                                                    }
                                                }
                                            }
                                            $traveller_info["ssrBaggageInfos"] = $ssrBaggageInfos_arr;
                                        }
                                    }

                                    if($traveller->ssrMealInfos) {
                                        $ssrMealInfos = (array)json_decode($traveller->ssrMealInfos,true);
                                        if($ssrMealInfos) {
                                            $ssrMealInfos_arr = [];
                                            foreach($tripInfos['tripInfos'] as $tripInfo) {
                                                foreach($tripInfo['sI'] as $flightData) {
                                                    $key = $flightData['da']['code'].'-'.$flightData['aa']['code'];
                                                    if(isset($ssrMealInfos[$key])) {
                                                        $ssrMealInfos_arr[] = [
                                                            'key' => $flightData['id'],
                                                            'code' => $ssrMealInfos[$key]['code']
                                                        ];
                                                    }
                                                }
                                            }
                                            $traveller_info["ssrMealInfos"] = $ssrMealInfos_arr;
                                        }
                                    }

                                    if($traveller->ssrSeatInfos) {
                                        $ssrSeatInfos = (array)json_decode($traveller->ssrSeatInfos,true);
                                        if($ssrSeatInfos) {
                                            $ssrSeatInfos_arr = [];
                                            foreach($ssrSeatInfos as $ssrSeat) {
                                                $ssrSeatInfos_arr[] = [
                                                    'key' => $ssrSeat['key'],
                                                    'code' => $ssrSeat['code']
                                                ];
                                            }
                                            $traveller_info["ssrSeatInfos"] = $ssrSeatInfos_arr;
                                        }
                                    }
                                    $travellerInfo[] = $traveller_info;
                                }
                            }

                            $emails = [$order->email];
                            $contacts = [$order->phone];

                            $isBA = $tripInfos['conditions']['isBA']??0;
                            $endPoint = '';
                            $reqParams = [];
                            if($bookingId) {
                                $reqParams["bookingId"] = $bookingId;
                            }
                            if($type == 'hold' || $isBA == 0){
                                $reqParams["travellerInfo"] = $travellerInfo;
                                $reqParams["deliveryInfo"] = [
                                    "emails" => [$emails],
                                    "contacts" => [$contacts]
                                ];

                                $gst_info = $flight_details->gst_info??[];
                                if(!empty($gst_info) && isset($gst_info->gst_number)) {
                                    $reqParams["gstInfo"] = [
                                        "gstNumber" => $gst_info->gst_number,
                                        "email" => $gst_info->gst_email,
                                        "registeredName" => $gst_info->gst_company,
                                        "mobile" => $gst_info->gst_phone,
                                        "address" => $gst_info->gst_address
                                    ];
                                }
                                $endPoint = '/oms/v1/air/book';
                            }
                            if($type == 'confirm-book' || $isBA == 0) {
                                $paymentInfos = [];
                                $paymentInfos[] = [
                                    "amount" => $total_amount
                                ];                            
                                $reqParams["paymentInfos"] = $paymentInfos;

                                if($isBA == 0) {
                                    $endPoint = '/oms/v1/air/book';
                                } else if($type == 'confirm-book') {
                                    $endPoint = '/oms/v1/air/confirm-book';
                                } else {
                                    $endPoint = '/oms/v1/air/book';
                                }
                            }

                            if($inventory_id) {
                                $statusCode = 200;
                                $bookingId = $order->order_no;
                                $message = '';                                
                            } else {
                                $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                                $apiResult = json_decode($resp, true);
                                $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                                $success = $apiResult['status']['success']??'';
                                $message = $apiResult['errors'][0]['message']??'';
                            }

                            if ($statusCode == 200 || stripos($message, 'Insufficient Balance') !== false ){
                                $response['success'] = true;
                                $response['bookingId'] = $bookingId;

                                if(stripos($message, 'Insufficient Balance') !== false) {
                                    CustomHelper::insufficientBalanceAlert($order->id);
                                }
                                $order->bookingId = $bookingId;

                                if($inventory_id) {
                                    $resp = json_encode(self::parseIIAIRBookingDetails($order));
                                    $apiResult = json_decode($resp, true);
                                    $statusCode = $apiResult['status']['httpStatus']??'';
                                    $success = $apiResult['status']['success']??'';
                                } else {
                                    $reqParams = [
                                        "bookingId" => $bookingId,
                                        "requirePaxPricing" => true
                                    ];
                                    $endPoint = '/oms/v1/booking-details';
                                    $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                                    $apiResult = json_decode($resp, true);
                                    $statusCode = $apiResult['status']['httpStatus']??'';
                                    $success = $apiResult['status']['success']??'';
                                }

                                if ($statusCode == 200) {
                                    $booking_status = $apiResult['order']['status']??'';
                                    $supplier_markup = $apiResult['order']['markup']??0;
                                    $order->supplier_markup = $supplier_markup;
                                    $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                                    $order->status = $booking_status;
                                    $order->booking_details = $resp;
                                    $order->save();

                                    if($booking_status == 'SUCCESS') {
                                        CustomHelper::applyFlightCommission($order->id);
                                        $response['message'] = 'Booking '.$booking_status.' - bookingId - '.$bookingId;

                                        if($inventory_id) {
                                            if($supplier_id) {
                                                $userId = $supplier_id;
                                                $amount = Order::getOrderSupplierPayment($order);
                                                $payment_method = 'wallet';

                                                $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                                                $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);
                                                $wallet_comment = 'Received for flight booking';
                                                $balance = $old_balance + $amount ; 
                                                $walletData = array(
                                                    'user_id' => $userId,
                                                    'type' => 'credit',
                                                    'amount' => $amount??0,
                                                    'fees' => $order->fees??0,
                                                    'payment_method' => 'Order',
                                                    'balance' => $balance,
                                                    'for_date' => date('Y-m-d H:i:s'),
                                                    'txn_id' => $order->order_no,
                                                    'comment' => $wallet_comment,
                                                );
                                                $isSavedWallet = UserWallet::create($walletData);
                                                if($isSavedWallet) {
                                                    $order_payments = new OrderPayments;
                                                    $order_payments->payment_method = $payment_method_name;
                                                    $order_payments->order_id = $order->id;
                                                    $order_payments->order_no = $order->order_no;
                                                    $order_payments->user_id = $order->user_id;
                                                    $order_payments->amount = $amount??0;
                                                    $order_payments->payment_type = 'Supplier Payment Credited - '.CustomHelper::showUserName($userId);
                                                    $order_payments->pg_payment_status = 1;
                                                    $order_payments->save();
                                                    $last_payment_id = $order_payments->id??NULL;

                                                    $order->last_payment_id = $last_payment_id;
                                                    $order->save();
                                                } else {
                                                    CustomHelper::captureSentryMessage('Flight Payment Success, But Agent Account Credited Failed!');
                                                }
                                            }
                                        }
                                    } else if($order->payment_status == 1 && ($booking_status == 'CANCELLED' || $booking_status == 'FAILED' || $booking_status == 'ABORTED')) {
                                        $response['message'] = 'Booking payment reversed for flight booking '.$booking_status.' - bookingId - '.$bookingId;
                                        self::refundOrderPayment($order->id,['wallet_comment'=>$response['message']]);
                                        $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                                        $order->status = $booking_status;
                                        $order->save();
                                    } else {
                                        $response['message'] = 'Booking '.$booking_status.' - bookingId - '.$bookingId;
                                    }
                                } else {
                                    $response['message'] = 'Errors occurred while fetch booking details for bookingId '.$bookingId.'.';
                                    $response['errors'] = $apiResult['errors']??[];
                                }
                            } else {
                                $response['message'] = 'Errors occurred while we '.$type.' ticket - bookingId '.$bookingId.'. Message: '.$message;
                                $response['errors'] = $apiResult['errors']??[];
                            }
                        }
                    } else {
                        $response['message'] = 'Flight session expired before '.$type.' ticket.';
                    }
                } else {
                    $response['message'] = 'Flight price id not found';
                }
            } else {
                $response['message'] = 'Flight details not found';
            }
            if(isset($booking_status) && !empty($booking_status)) {
                $orders_status = CustomHelper::getOrderStatus($booking_status);
            } else {
                $orders_status = CustomHelper::showEnquiryMaster($order->orders_status)??'';
            }
            $order_status_history  = array(
                'order_id' => $order->id,
                'orders_status' => $orders_status,
                'comments' => $response['message']??'',
                'created_type' => $created_type,
                'created_by' => $user_id,
                'created_by_name' => $user_name,
            );
            $isSaved = OrderStatusHistory::create($order_status_history);
        } else {
            $response['message'] = 'Order details not found';
        }
        return $response;
    }

    public static function parseIIAIRBookingDetails($order, $params=[]) {
        $response = [];
        if($order && $order->id) {
            $emails = [$order->email];
            $contacts = [$order->phone];
            $tripInfos = json_decode($order->flight_details, true);
            $travellerInfos = [];
            $OrderTraveller = $order->OrderTraveller??[];
            if($OrderTraveller) {
                $segments = $tripInfos['info']['tripInfos'][0]['sI']??[];
                foreach($OrderTraveller as $traveller) {
                    $checkinStatusMap = [];
                    $pnrDetails = [];
                    $airline_ticket_no = [];
                    if($segments) {
                        foreach($segments as $segment) {
                            $key = ($segment['da']['code']??'').'-'.($segment['aa']['code']??'');
                            $checkinStatusMap[$key] = 1;
                            $pnrDetails[$key] = $traveller->pnrDetails??'';
                            $airline_ticket_no[$key] = $traveller->airline_ticket_no??'';
                        }
                    }
                    $travellerData = [
                        'checkinStatusMap' => $checkinStatusMap,
                        'ti' => $traveller->title,
                        'pt' => $traveller->pax_type,
                        'fN' => $traveller->first_name,
                        'lN' => $traveller->last_name,
                        'dob' => $traveller->dob,

                        'passport_nationality' => $traveller->passport_nationality,
                        'passport_no' => $traveller->passport_no,
                        'passport_exp_date' => $traveller->passport_exp_date,
                        'passport_issue_date' => $traveller->passport_issue_date,
                        'pnrDetails' => $pnrDetails,
                        'airline_ticket_no' => $airline_ticket_no,
                    ];
                    $travellerInfos[] = $travellerData;
                }
            }
            $totalPriceInfo = [];
            if($order->supplier_id) {
                $status = 'SUCCESS';
            } else {
                $status = 'NEW';
            }
            $tripInfosSIs = $tripInfos['info']['tripInfos'][0]['sI']??[];
            $newTripInfos = [];
            if($tripInfosSIs) {
                foreach($tripInfosSIs as $key => $tripInfosSI) {
                    $newTIs = [];
                    if($travellerInfos) {
                        foreach($travellerInfos as $index => $travellerInfo) {
                            $tI = $tripInfosSI['bI']['tI'][$index]??[];
                            $newTI = $tI;
                            $pt = $travellerInfo['pt']??'';
                            $newTI['ti'] = $travellerInfo['ti']??'';
                            $newTI['pt'] = $travellerInfo['pt']??'';
                            $newTI['fN'] = $travellerInfo['fN']??'';
                            $newTI['lN'] = $travellerInfo['lN']??'';
                            $newTIs[] = $newTI;
                        }
                    }
                    $tripInfos['info']['tripInfos'][0]['sI'][$key]['bI']['tI'] = $newTIs;
                }
            }
            $response = [
                'order' => [
                    'bookingId' => $order->order_no,
                    'amount' => $order->total_amount,
                    'markup' => $order->supplier_markup,
                    'deliveryInfo' => [
                        'emails' => $emails,
                        'contacts' => $contacts
                    ],
                    'status' => ($order->status)?$order->status:$status,
                    'createdOn' => $order->created_at,
                ],
                'itemInfos' => [
                    'AIR' => [
                        'tripInfos' => $tripInfos['info']['tripInfos']??[],
                        'travellerInfos' => $travellerInfos,
                        'totalPriceInfo' => $tripInfos['info']['totalPriceInfo']??[]
                    ]
                ],
                'status' => [
                    'httpStatus' => 200,
                    'success' => true
                ]
            ];
        }
        return $response;
    }

    public static function refundOrderPayment($order_id,$params=[]) {
        $success = false;
        $created_type = 'backend';
        $created_by = auth()->user()->id??0;
        $created_by_name = auth()->user()->name??'system';
        if($order_id) {
            $order = Order::find($order_id);
            if($order && $order->payment_status == 1) {
                $userId = $order->user_id;
                $name = $order->name;
                $agent_id = 0;
                $user = auth::guard('admin')->user();
                if($user && $user->id) {
                    $created_type = 'backend';
                    $created_by = $user->id??0;
                    $created_by_name = $user->name??'system';
                } else {
                    $created_type = 'customer';
                    $user = auth()->user();
                    $user_id = $user->id??0;
                    if($user_id) {
                        $created_by = $user_id;
                        $created_by_name = $user->name??'system';
                        $is_agent = $user->is_agent??0;
                        $approved_agent = $user->approved_agent??0;
                        if($is_agent == 1 && $approved_agent == 1){
                            $agent_id = $user_id;
                            $created_type = 'agent';
                        }
                    } else {
                        $created_by = $userId;
                        $created_by_name = $name;
                    }
                }

                $amount = $order->partial_amount??0;// $order->total_amount;
                $wallet_comment = (isset($params['wallet_comment']) && !empty($params['wallet_comment']))?$params['wallet_comment']:'Booking payment reversed.';
                $reason = (isset($params['reason']) && !empty($params['reason']))?$params['reason']:'Booking payment reversed.';
                if($order->method == 'wallet') {
                    $payment_method_name = CustomHelper::getPaymentGatewayName($order->method);
                    $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                    $balance = $old_balance + $amount;
                    $walletData = array(
                        'user_id' => $userId,
                        'type' => 'credit',
                        'amount' => $amount,
                        'fees' => 0,
                        'payment_method' => 'Order',   
                        'balance' => $balance,
                        'for_date' => date('Y-m-d H:i:s'),
                        'txn_id' => $order->order_no,
                        'comment' => $wallet_comment,
                    );
                    $isSavedWallet = UserWallet::create($walletData);
                    if($isSavedWallet) {
                        $order_payments = new OrderPayments;
                        $order_payments->payment_method = $payment_method_name;
                        $order_payments->order_id = $order->id;
                        $order_payments->order_no = $order->order_no;
                        $order_payments->user_id = $order->user_id;
                        $order_payments->amount = $amount??0;
                        $order_payments->description = $wallet_comment??0;
                        $order_payments->payment_type = 'full_refund';
                        // $order_payments->payment_type = $order->pay_type;
                        $order_payments->pg_payment_status = 3; //Refunded
                        $order_payments->save();
                        $last_payment_id = $order_payments->id??NULL;

                        $order->last_payment_id = $last_payment_id;
                        $order->payment_status = 3;
                        $order->comment = $wallet_comment;
                        $order->save();
                    }
                    $success = true;
                } elseif($order->method == 'tpsl') {
                    $params['reason'] = $reason;
                    $PaymentGatewayRefund = CustomHelper::PaymentGatewayRefund($order,$params);
                    if($PaymentGatewayRefund){
                        $success = true;
                    }
                    //Manual refund need to be done.
                    $success = true;
                }

                if($success) {
                    $inventory = $order->inventory;
                    $is_revort = 1;
                    $inventory_update = Self::inventoryUpdate($order,$is_revort);
                    $rejected_id = CustomHelper::getOrderStatus('cancelled');

                    $order = Order::find($order_id);
                    $order->cancel_request = 0;
                    $order->orders_status = $rejected_id??0;
                    $order->status = 'Cancelled';
                    $order->save();
                    
                    $order_status_history  = array(
                        'order_id' => $order->id,
                        'orders_status' => CustomHelper::showEnquiryMaster($rejected_id) ?? '',
                        'comments' => $reason,
                        'created_type' => $created_type,
                        'created_by' => $created_by,
                        'created_by_name' => $created_by_name,
                    );
                    $isSaved = OrderStatusHistory::create($order_status_history);

                    if($order->inventory_id) {
                        $OrderTraveller = $order->OrderTraveller??[];
                        if($OrderTraveller) {
                            foreach($OrderTraveller as $traveller) {
                                $traveller->pnrDetails = '';
                                $traveller->airline_ticket_no = '';
                                $traveller->save();
                            }
                        }
                        $order = Order::find($order_id);
                        $resp = Order::parseIIAIRBookingDetails($order);
                        $order->booking_details = json_encode($resp);
                        $order->save();

                        if($order->supplier_id) {
                            $payment_method_name = CustomHelper::getPaymentGatewayName('wallet');
                            $amount = Order::getOrderSupplierPayment($order);
                            $userId = $order->supplier_id;
                            $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                            $balance = $old_balance - $amount ;
                            $walletData = array(
                                'user_id' => $userId,
                                'type' => 'debit',
                                'amount' => -$amount??0,
                                'fees' => $order->fees??0,
                                'payment_method' => 'Order',
                                'balance' => $balance,
                                'for_date' => date('Y-m-d H:i:s'),
                                'txn_id' => $order->order_no,
                                'comment' => $wallet_comment,
                            );
                            $isSavedWallet = UserWallet::create($walletData);
                            if($isSavedWallet) {
                                $order_payments = new OrderPayments;
                                $order_payments->payment_method = $payment_method_name;
                                $order_payments->order_id = $order->id;
                                $order_payments->order_no = $order->order_no;
                                $order_payments->user_id = $order->user_id;
                                $order_payments->amount = $amount??0;
                                $order_payments->payment_type = 'Supplier Payment Reversed - '.CustomHelper::showUserName($userId);
                                $order_payments->pg_payment_status = 1;
                                $order_payments->save();
                                $last_payment_id = $order_payments->id??NULL;
                            } else {
                                CustomHelper::captureSentryMessage('Flight Cancellation Success, But Agent Account Debited Failed!');
                            }
                        }
                    }

                    $order_type_arr = config('custom.order_type');
                    $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                    $email_params = array(
                        '{name}' => $order->name,
                        '{order_no}' => $order->order_no,
                        '{order_type}' => $order_type,
                        '{reason_for_cancellation}' => $reason,
                        '{date}' => date("l jS \of F Y h:i A"),
                    );
                    $email = $order->email;
                    $order_phone = '';
                    if($order->phone) {
                        $country_code = $order->country_code ?? '91';
                        $order_phone = '+'.$country_code.'-'.$order->phone;
                    }
                    $REPLYTO = $email;
                    $template_slug = 'refund-payment';
                    $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

                    $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
                    $email_params = isset($email_params) ? $email_params : [];
                    $email_content = strtr($email_content, $email_params);

                    $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';
                    $subject_params = isset($subject_params) ? $subject_params : [];
                    $email_subject = strtr($email_subject, $subject_params);
                    $email_type = isset($email_content_data->email_type) ? $email_content_data->email_type : '';
                    $email_bcc_admin = isset($email_content_data->bcc_admin) ? $email_content_data->bcc_admin : 0;

                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $to_email = $order->email;
                    $cc_email = '';                    
                    $bcc_email_arr = [];
                    if($order->inventory_id) {
                        $supplier_email = $order->supplierData->email??'';
                        if($supplier_email) {
                            $bcc_email_arr[] = $supplier_email;
                        }
                    }
                    if($email_type == 'admin'){
                        $to_email = $ADMIN_EMAIL;
                    }
                    if($email_bcc_admin == 1){
                        $bcc_email_arr[] = $ADMIN_EMAIL;
                    }

                    if(isset($email_content_data) && !empty($email_content_data)) {
                        $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';
                        $params = [];
                        $params['to'] = $to_email;
                        $params['reply_to'] = $REPLYTO;
                        $params['cc'] = $cc_email;
                        $params['bcc'] = $bcc_email;
                        $params['subject'] = $email_subject;
                        $params['email_content'] = $email_content;
                        $params['module_name'] = 'Refund payment';
                        $params['record_id'] = $order_id ?? 0;
                        $is_mail1 = CustomHelper::sendCommonMail($params);
                    }

                    $order_id = $order->id;
                    $new_data = DB::table('orders')->where('id',$order_id)->first();

                    $booking_details =  json_decode($new_data->booking_details)??'';
                    $driver_details  = $booking_details->driver_details??'';
                    $module_desc = $driver_details->driver_name??'';

                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id= $order_id;
                    $params['user_id'] = $created_by;
                    $params['user_name'] = $created_by_name;
                    $params['module'] = 'Order Cancelled/Refund';
                    $params['module_desc'] = '';
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = '';
                    $params['sub_module_id'] = '';
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = "Refund/Cancelled";
                    CustomHelper::RecordActivityLog($params);
                }
            }
        }
        return $success;
    }

    public static function requestOrderPayment($order,$params=[]) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $created_type = 'customer';
        $created_by = auth()->user()->id??0;
        $created_by_name = auth()->user()->name??'system';
        $is_agent = auth()->user()->is_agent??0;
        $approved_agent = auth()->user()->approved_agent??0;
        if($is_agent == 1 && $approved_agent == 1) {
            $created_type = 'agent';
        }
        $adminUser = auth::guard('admin')->user();
        if($adminUser && $adminUser->id) {
            $created_type = 'backend';
            $created_by = $adminUser->id??0;
            $created_by_name = $adminUser->name??'system';
        }

        if($order && $order->id) {
            $userId = $order->user_id;
            $reason = $params['reason']??'Payment due for Order No:#'.$order->order_no;

            $total_amount = $order->total_amount;
            $partial_amount = $order->partial_amount;
            $payment_due = $total_amount - $partial_amount;
            if($payment_due > 0) {
                $pay_type = 'payment_due';
                $order_payments = new OrderPayments;
                $order_payments->payment_method = CustomHelper::getPaymentGatewayName('tpsl');
                $order_payments->order_id = $order->id;
                $order_payments->order_no = $order->order_no;
                $order_payments->user_id = $order->user_id;
                $order_payments->amount = $payment_due??0;
                $order_payments->payment_type = $pay_type;
                $order_payments->status = 0;
                $order_payments->pg_payment_status = 0;
                $order_payments->save();
                $last_payment_id = $order_payments->id ?? NULL;

                $order->last_payment_id = $last_payment_id;
                $order->save();

                $order_type = 7;
                $name = $order->name??'';
                $email = $order->email??'';
                $user =  $order->User??[];
                if($user && $user->id) {
                    $is_agent = $user->is_agent??'';
                    $approved_agent = $user->approved_agent??'';
                    if($is_agent == 1 && $approved_agent == 1) {
                        $name = $user->agentInfo->company_name??$name;
                        $email = $user->email??$email;
                    }
                }
                $order_no = CustomHelper::genrateOrderNoId($userId).'-'.$last_payment_id;

                $newOrder = new Order;
                $newOrder->order_no = $order_no;
                $newOrder->order_type = $order_type;
                $newOrder->name = $order->name;
                $newOrder->user_id = $order->user_id;
                $newOrder->agent_id = $order->agent_id;
                $newOrder->email = $order->email;
                $newOrder->phone = $order->phone;
                $newOrder->country_code = $order->country_code;
                $newOrder->address1 = $order->address1;  
                $newOrder->city = $order->city;
                $newOrder->state = $order->state;
                $newOrder->country = $order->country;
                $newOrder->zip_code = $order->zip_code;
                $newOrder->payment_status = 0;
                $newOrder->comment =  $reason??'';
                $newOrder->payment_response = NULL;
                $newOrder->discount_type = '';
                $newOrder->discount = 0;

                $total_amount = $payment_due??0;
                $action = '';
                if($action == 'wallet') {
                    $fees = 0;
                    $WALLET_PAYMENT_GATEWAY_CHARGE = CustomHelper::WebsiteSettings('WALLET_PAYMENT_GATEWAY_CHARGE');
                    if($request->amount > 0){
                        $fees = ($WALLET_PAYMENT_GATEWAY_CHARGE / 100) * $request->amount;
                        $total_amount = $fees + $request->amount;
                        $newOrder->fees = $fees ?? 0;
                    }
                }
                $newOrder->sub_total_amount = $payment_due??0;
                $newOrder->total_amount = $total_amount??0;
                $newOrder->trip_date = null;
                $isSaved = $newOrder->save();

                if($isSaved) {
                    $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                    $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                    $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                    $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                    $sales_phone = CustomHelper::getPhoneHref($company_phone);
                    $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                    $sales_email = CustomHelper::getEmailHref($company_email);
                    $storage = Storage::disk('public');
                    $path = "settings/";
                    $logoSrc =asset(config('custom.assets').'/images/logo.png');
                    if(!empty($FRONTEND_LOGO)){
                        if($storage->exists($path.$FRONTEND_LOGO)){
                            $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                        }
                    }
                    $userAgentInfo = '';
                    $agentLogo = '';
                    $agent_id = 0;//isset($order->agent_id) ? $order->agent_id : 0;
                    if($agent_id && $agent_id > 0){
                        $userAgentInfo = $order->agentInfo ?? '';
                        if($userAgentInfo && $userAgentInfo->count() > 0){
                            $path = 'agent_logo/';
                            $agentLogo = $order->agentInfo->agent_logo ?? '';

                            $userData = $userAgentInfo->User ?? '';
                            $agent_phone = '';
                            $agent_email = '';
                            if($userData->phone) {
                                $country_code = $userData->country_code ?? '91';
                                $agent_phone = '+'.$country_code.'-'.$userData->phone;
                            }
                            $agent_email = !empty($userData->email)?$userData->email:'';
                        }
                        if(!empty($agentLogo)){
                            if($storage->exists($path.$agentLogo)){
                                $logoSrc = asset('/storage/'.$path.$agentLogo);
                            }
                        }
                    }

                    $common_logo = '';
                    if(!empty($agent_id)){
                        $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                        $b2b_logo_params = array(
                            '{agent_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                    }else{
                        $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                        $b2c_logo_params = array(
                            '{company_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                    }

                    $contact_details = '';
                    if(!empty($agent_id)){
                        $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                        $b2b_email_params = array(
                            '{agent_phone}' => $agent_phone,
                            '{agent_email}' => $agent_email
                        );
                        $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                    } else {
                        $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                        $b2c_email_params = array(
                            '{company_name}' => $company_name,
                            '{sales_mobile}' => $sales_mobile,
                            '{sales_phone}' => $sales_phone,
                            '{sales_email}' => $sales_email,
                            '{company_title}' => $SYSTEM_TITLE
                        );
                        $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                    }

                    $pay_now_link =  route('pay_now',$order_no);

                    $order_type_arr = config('custom.order_type');
                    $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                    $form_values = array(
                        '{header}' => $common_logo??'',
                        '{contact_details}' => $contact_details??'',
                        '{name}' => $order->name,
                        '{order_no}' => $order->order_no,
                        '{order_type}' => $order_type,
                        '{total_amount}' => CustomHelper::getPrice($order->partial_amount),
                        '{payment_due}' => CustomHelper::getPrice($payment_due),
                        '{pay_now_link}' => $pay_now_link,
                        '{reason_for_request_payment}' => $reason,
                        '{order_date}' => CustomHelper::DateFormat($order->created_at,"l jS \of F Y h:i A"),
                        '{date}' => date("l jS \of F Y h:i A"),
                    );
                    $REPLYTO = $email;
                    $template_slug = 'request-payment';
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

                    $search_arr = array_keys($form_values)??[];
                    $replace_arr = array_values($form_values)??[];
                    $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
                    $search_arr = array_keys($form_values)??[];
                    $replace_arr = array_values($form_values)??[];
                    $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
                    $email_bcc_admin = $email_template->bcc_admin ?? 0;
                    $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $to_email = $email;
                    $cc_email = '';                    
                    $bcc_email_arr = [];
                    if($order->inventory_id) {
                        $supplier_email = $order->supplierData->email??'';
                        if($supplier_email) {
                            $bcc_email_arr[] = $supplier_email;
                        }
                    }
                    if($email_type == 'admin'){
                        $to_email = $ADMIN_EMAIL;
                    }
                    if($email_bcc_admin == 1){
                        $bcc_email_arr[] = $ADMIN_EMAIL;
                    }

                    $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';
                    $params = [];
                    $params['to'] = $to_email;
                    $params['reply_to'] = $REPLYTO;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    $params['module_name'] = 'Request payment';
                    $params['record_id'] = $order_id ?? 0;
                    $is_mail1 = CustomHelper::sendCommonMail($params);

                    $order_id = $order->id;
                    $new_data = DB::table('orders')->where('id',$order_id)->first();

                    $booking_details =  json_decode($new_data->booking_details)??'';
                    $driver_details  = $booking_details->driver_details??'';
                    $module_desc = $driver_details->driver_name??'';

                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id = $order_id;
                    $params['user_id'] = $created_by;
                    $params['user_name'] = $created_by_name;
                    $params['module'] = 'Order Request Payment';
                    $params['module_desc'] = '';
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = '';
                    $params['sub_module_id'] = '';
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = "Request Payment";
                    CustomHelper::RecordActivityLog($params);

                    $response['success'] = true;
                } else {
                    $response['message'] = 'Something went wrong, please try again.';
                }
            }
        }
        return $response;
    }

    public static function offline_mail($order_id) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';            
        $order = Order::find($order_id);
        if($order) {
            $package = $order->Package??[];
            $package_title = $package->title??'';
            $emailData = [];
            $emailData['sub_total_amount']= $order->sub_total_amount;
            $emailData['total_amount']= $order->total_amount;
            $emailData['method'] = CustomHelper::getPaymentGatewayName($order->method);
            $emailData['order_no'] = $order->order_no??'';
            $emailData['status'] = $order->payment_status;
            $emailData['itemname'] = $package_title;
            $emailData['payer_name'] = $order->name;
            $emailData['payer_email'] = $order->email;
            $emailData['comment'] = $order->comment;

            $payment_gateway = PaymentGateway::where(['value'=>'bank_payment','status'=>1])->first();
            $perameter_1 = $payment_gateway->perameter_1;
            $emailData['bank_details'] = $perameter_1;

            $email = $order->email;
            $email_subject = "Payment Of ".$package_title;
            $REPLYTO = $email;

            $email_params = array(
                '{sub_total_amount}' => CustomHelper::getPrice($order->sub_total_amount),
                '{total_amount}' => CustomHelper::getPrice($order->total_amount),
                '{method}' => CustomHelper::getPaymentGatewayName($order->method),
                '{order_no}' => $order->order_no,
                '{itemname}' => $package_title,
                '{status}' => ($order->payment_status == 1) ? "Completed" : "Pending",
                '{comment}' => $order->comment,
                '{payer_email}' => $order->email,
                '{bank_details}' => $perameter_1,
            );

            $viewHtml = view('emails.banktransferDetails_email', $email_params)->render();
            $email_content = strtr($viewHtml, $email_params);

            $to_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
            $cc_email = '';
            $bcc_email_arr = [];
            if($order->inventory_id) {
                $supplier_email = $order->supplierData->email??'';
                if($supplier_email) {
                    $bcc_email_arr[] = $supplier_email;
                }
            }
            $bcc_email_arr[] = $to_email;

            $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';
            $params = [];
            $params['to'] = $email;
            $params['reply_to'] = $to_email;
            $params['cc'] = $cc_email;
            $params['bcc'] = $bcc_email;
            $params['subject'] = $email_subject;
            $params['email_content'] = $email_content;
            $params['module_name'] = 'Order bank payment';
			$params['record_id'] = $order->id ?? 0;
            $is_mail2 = CustomHelper::sendCommonMail($params);

            $response['success'] = true;
            $response['bankData'] = $emailData;
        }
        return $response;        
    }

    public static function checkBookingStatus($order_id) {
        $response = [];
        $response['success'] = false;
        if($order_id) {
            $order = Order::find($order_id);
            if($order) {
                $bookingId = $order->bookingId??'';
                $order_no = $order->order_no??'';
                $agent_id = $order->agent_id??'';
                $total_amount = $order->total_amount;
                $booking_details = json_decode($order->booking_details,true)??[];
                $booking_status = $booking_details['order']['status']??'';
                if(!empty($bookingId) && !empty($booking_status) && $booking_status != 'SUCCESS' && ($booking_status == 'PENDING' || $booking_status == 'HOLD' || $booking_status == 'ON_HOLD') ) {
                    $reqParams = [
                        "bookingId" => $bookingId,
                        "requirePaxPricing" => true
                    ];
                    // prd($reqParams);
                    $endPoint = '/oms/v1/booking-details';
                    $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                    $apiResult = json_decode($resp, true);
                    $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                    $success = !empty($apiResult)?$apiResult['status']['success']:'';
                    if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                        $new_booking_status = $apiResult['order']['status']??'';
                        if($new_booking_status != $booking_status) {
                            $order->orders_status = CustomHelper::getOrderStatus($new_booking_status);
                            $order->status = $new_booking_status;
                            $order->booking_details = $resp;

                            if($new_booking_status == 'SUCCESS') {
                                CustomHelper::applyFlightCommission($order->id);
                            } else if($new_booking_status == 'CANCELLED' || $new_booking_status == 'FAILED' || $new_booking_status == 'ABORTED') {
                                $wallet_comment = 'Booking payment reversed for flight booking '.$new_booking_status;
                                self::refundOrderPayment($order->id,['wallet_comment'=>$wallet_comment]);
                            }
                            $order->save();
                            $response['success'] = true;
                            $response['booking_status'] = $new_booking_status;
                        } else {
                            $response['message'] = 'Order is still in "'.$booking_status.'" status, please try again after some time.';
                        }
                    } else {
                        $response['message'] = 'Errors occurred while fetching booking details.';
                        $response['errors'] = $apiResult['errors']??[];
                    }
                } else {
                    $response['message'] = 'Order status can be checked for PENDING or HOLD only.';
                }
                $response['order_no'] = $order_no;
            } else {
                $response['message'] = 'Order details is missing.';
            }
        } else {
            $response['message'] = 'Order id is missing.';
        }
        return $response;
    }

    public static function checkAmendmentStatus($id) {
        $response = [];
        $response['success'] = false;
        if($id) {
            $amendment = OrderAmendments::find($id);
            // prd($amendment->toArray());
            if($amendment && $amendment->status == 0) {
                $order = $amendment->Order??[];
                if($order) {
                    $bookingId = $amendment->bookingId??'';
                    $amendmentId = $amendment->amendmentId??'';
                    $order_id = $order->id??'';
                    $order_no = $order->order_no??'';
                    $reqParams = [
                        "amendmentId" => $amendmentId
                    ];
                    // prd($reqParams);
                    $endPoint = '/oms/v1/air/amendment/amendment-details';
                    $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                    $apiResult = json_decode($resp, true);
                    // prd($apiResult);
                    $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                    $success = !empty($apiResult)?$apiResult['status']['success']:'';
                    if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                        $amendmentStatus = $apiResult['amendmentStatus']??'';
                        $refundableAmount = $apiResult['refundableAmount']??0;
                        if($amendmentStatus == 'SUCCESS') {
                            $amendment->status = 1;
                            $amendment->amendment_details = $resp;
                            $amendment->save();

                            //Apply flight amendment refund
                            if($refundableAmount > 0) {
                                $user_id = $order->user_id;
                                if($order->agent_id) {
                                    $user_id = $order->agent_id;
                                }
                                $old_balance = UserWallet::where('user_id',$user_id)->sum('amount');
                                $balance = $old_balance + $refundableAmount;
                                $walletData  = array(
                                    'user_id' => $user_id,
                                    'type' => 'credit',
                                    'amount' => $refundableAmount,
                                    'fees' => 0,
                                    'payment_method' => 'Order',
                                    'balance' => $balance,
                                    'for_date' => date('Y-m-d H:i:s'),
                                    'txn_id' => $order_no,
                                    'comment' => 'Flight amendment refundable amount Credited',
                                );
                                $isSaved = UserWallet::create($walletData);

                                $pay_type = 'partial_refund';
                                $order_payments = new OrderPayments;
                                $order_payments->payment_method = CustomHelper::getPaymentGatewayName('wallet');
                                $order_payments->order_id = $order->id;
                                $order_payments->order_no = $order->order_no;
                                $order_payments->user_id = $order->user_id;
                                $order_payments->amount = $refundableAmount ?? 0;
                                $order_payments->payment_type = $pay_type;
                                $order_payments->status = 1;
                                $order_payments->pg_payment_status = 3;
                                $order_payments->save();
                                $last_payment_id = $order_payments->id ?? NULL;

                                // $order->pay_type = $pay_type;
                                $order->last_payment_id = $last_payment_id;
                                $order->save();
                            }

                            $reqParams = [
                                "bookingId" => $bookingId,
                                "requirePaxPricing" => true
                            ];
                            $endPoint = '/oms/v1/booking-details';
                            $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                            $apiResult = json_decode($resp, true);
                            $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                            $success = !empty($apiResult)?$apiResult['status']['success']:'';
                            $booking_status = $apiResult['order']['status']??'';
                            if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                                $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                                $order->status = $booking_status;
                                $order->booking_details = $resp;
                                $order->save();
                            } else {
                                $response['message'] = 'Errors occurred while fetching booking details.';
                                $response['errors'] = $apiResult['errors']??[];
                            }
                            $response['success'] = true;

                            Self::sendFlightCancellationEmail($order->id,$amendmentStatus);

                            $description = 'Order amendment request has been processed successfully.';
                            $req_data = [];
                            $req_data['order_id'] = $order->id;
                            $req_data['orders_status'] = $order->status;
                            $req_data['comments'] = $description;
                            $req_data['created_type'] = 'backend';
                            $req_data['created_by'] = 0;
                            $req_data['created_by_name'] = 'system';
                            OrderStatusHistory::create($req_data);

                        } else {
                            if($amendmentStatus == 'REJECTED') {
                                $amendment->status = 2;
                                $amendment->amendment_details = $resp;
                                $amendment->save();

                                Self::sendFlightCancellationEmail($order->id,$amendmentStatus);

                                $description = 'Order amendment request has been REJECTED.';
                                $req_data = [];
                                $req_data['order_id'] = $order->id;
                                $req_data['orders_status'] = $order->status;
                                $req_data['comments'] = $description;
                                $req_data['created_type'] = 'backend';
                                $req_data['created_by'] = 0;
                                $req_data['created_by_name'] = 'system';
                                OrderStatusHistory::create($req_data);
                            }
                            $response['message'] = 'Amendment status is still in "'.$amendmentStatus.'" status, please try again after some time.';
                        }
                    } else {
                        $response['message'] = 'Errors occurred while fetching booking details.';
                        $response['errors'] = $apiResult['errors']??[];
                    }
                    $response['order_no'] = $order_no;
                    $response['amendmentId'] = $amendmentId;
                } else {
                    $response['message'] = 'Order details is missing.';
                }
            } else {
                $response['message'] = 'Amendment details is missing.';    
            }            
        } else {
            $response['message'] = 'Amendment id is missing.';
        }
        return $response;
    }

    public static function sendFlightCancellationEmail($order_id,$status) {
        $order = Order::find($order_id);
        $email_template = EmailTemplate::where('slug','flight-cancellation')->first();
        if($order && !empty($order->email) && !empty($email_template)) {
            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
            $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
            $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
            $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
            $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

            $sales_phone = CustomHelper::getPhoneHref($company_phone);
            $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
            $sales_email = CustomHelper::getEmailHref($company_email);
            $email = $order->email;
            $cc_email = '';
            $bcc_email_arr = [];
            if($order->inventory_id) {
                $supplier_email = $order->supplierData->email??'';
                if($supplier_email) {
                    $bcc_email_arr[] = $supplier_email;
                }
            }

            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';

                    $userData = $userAgentInfo->User ?? '';
                    $agent_phone = '';
                    $agent_email = '';
                    if($userData->phone) {
                        $country_code = $userData->country_code ?? '91';
                        $agent_phone = '+'.$country_code.'-'.$userData->phone;
                    }
                    $agent_email = !empty($userData->email)?$userData->email:'';
                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = asset('/storage/'.$path.$agentLogo);
                    }
                }
            }

            $common_logo = '';
            if(!empty($agent_id)){
                $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                $b2b_logo_params = array(
                    '{agent_logo}' => $logoSrc,
                );
                $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
            }else{
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                   '{company_logo}' => $logoSrc
               );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
            }

          
            $contact_details = '';
            if(!empty($agent_id)){
                $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                $b2b_email_params = array(
                    '{agent_phone}' => $agent_phone,
                    '{agent_email}' => $agent_email
                );
                $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
            }else{

                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $SYSTEM_TITLE
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            }

            $seo_tags = CustomHelper::getSeoModules('flight');
            $manager_email = $seo_tags->admin_email??'';

            $form_values = [];
            $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
            $form_values['{header}'] = $common_logo;
            $form_values['{logo}'] = $logoSrc;
            $form_values['{e_date}'] = date("l jS \of F Y h:i A");
            $form_values['{name}'] = $order->name;
            $form_values['{contact_details}'] = $contact_details??'';
            $form_values['{email}'] = $order->email;
            $form_values['{phone}'] = $order->phone;
            $form_values['{city}'] = $order->city;
            if($order->trip_date) {
                $form_values['{date}'] = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
            } else {
                $form_values['{date}'] = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
            }
            $form_values['{total_amount}'] = CustomHelper::getPrice($order->total_amount);
            $form_values['{booking_id}'] = $order->order_no??'';
            $form_values['{status}'] = $status??'';

            $record_name = 'Flight Cancellation';

            $params = [];
            $params['order'] = $order;
            if($order->booking_details) {
                $params['booking_details'] = json_decode($order->booking_details, true);
            }
            $itineraries = view('emails._flight_booking_email',$params)->render();
            $form_values['{itineraries}'] = $itineraries;

            $params = [];
            $params['order'] = $order;
            if($order->booking_details) {
                $params['booking_details'] = json_decode($order->booking_details, true);
            }
            $pdf = PDF::loadView("emails._flight_booking_pdf", $params);
            $path = 'temp/';
            $pdf_name = $order->order_no.'.pdf';
            if (!File::exists(public_path("storage/" . $path))) {
                File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
            }
            $pdf->save(public_path("storage/" . $path).$pdf_name);
            $attachments = public_path("storage/".$path).$pdf_name;

            $search_arr = array_keys($form_values)??[];
            $replace_arr = array_values($form_values)??[];
            $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
            $search_arr = array_keys($form_values)??[];
            $replace_arr = array_values($form_values)??[];
            $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
            $email_bcc_admin = $email_template->bcc_admin??0;
            $bcc_manager_email = $email_template->manager_email??0;
            $bcc_contact_email = $email_template->contact_email??0;
            $email_type = $email_template->email_type??'';

            if($email_type == 'admin') {
                $email = $ADMIN_EMAIL;
            }

            if($email_bcc_admin == 1 && !empty($ADMIN_EMAIL)){
                $bcc_email_arr[] = $ADMIN_EMAIL??'';
            }

            if($bcc_manager_email == 1 && !empty($manager_email)){
                $bcc_email_arr[] = $manager_email??'';
            }

            $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';
            $params = [];
            $params['to'] = $email;
            $params['reply_to'] = $ADMIN_EMAIL;
            $params['cc'] = $cc_email;
            $params['bcc'] = $bcc_email;
            $params['subject'] = $email_subject;
            $params['email_content'] = $email_content;
            if(isset($attachments)) {
                $params['attachments'] = $attachments;
            }
            $params['module_name'] = $record_name;
            $params['record_id'] = $order->id ?? 0;
            $isSent = CustomHelper::sendCommonMail($params);
        }
    }

    public static function getCancelFlightCharges($order_id, $remarks='CANCELLATION') {
        $response = [];
        $response['success'] = false;
        if($order_id) {
            $order = Order::find($order_id);
            if($order) {
                $bookingId = $order->bookingId??'';
                $order_no = $order->order_no??'';
                $agent_id = $order->agent_id??'';
                $total_amount = $order->total_amount;
                $booking_details = json_decode($order->booking_details,true)??[];
                $booking_status = $booking_details['order']['status']??'';
                $departureDate = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['dt']??'';
                if(!empty($bookingId) && !empty($booking_status) && $booking_status == 'SUCCESS' && !empty($departureDate) && strtotime($departureDate) > strtotime(date('Y-m-d'))) {
                    $trips = [];                    
                    if(isset($booking_details['itemInfos']) && isset($booking_details['itemInfos']['AIR'])) {

                        $tripInfos = $booking_details['itemInfos']['AIR']['tripInfos']??[];
                        $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                        foreach($tripInfos as $tripInfo) {
                            $travellers = [];
                            foreach($travellerInfos as $traveller) {
                                $travellers[] = [
                                    'fn' => $traveller['fN']??'',
                                    'ln' => $traveller['lN']??''
                                ];
                            }
                            $trips[] = [
                                'src' => $tripInfo['sI'][0]['da']['code']??'',
                                'dest' => $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'',
                                'departureDate' => CustomHelper::DateFormat($tripInfo['sI'][0]['dt'],'Y-m-d')??'',
                                'travellers' => $travellers
                            ];
                        }
                    }
                    $reqParams = [
                        "bookingId" => $bookingId,
                        "type" => "CANCELLATION",
                        "remarks" => $remarks,
                        "trips" => $trips
                    ];
                    $endPoint = '/oms/v1/air/amendment/amendment-charges';
                    $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                    $apiResult = json_decode($resp, true);
                    $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                    $success = !empty($apiResult)?$apiResult['status']['success']:'';
                    if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                        $response['success'] = true;
                        $response['results'] = $apiResult;
                    } else {
                        $response['message'] = 'Errors occurred while fetching charges.';
                        $response['errors'] = $apiResult['errors']??[];
                    }
                } else {
                    $response['message'] = 'Only SUCCESS Orders charges can be fetched.';
                }
            } else {
                $response['message'] = 'Order details is missing.';
            }
        } else {
            $response['message'] = 'Order id is missing.';
        }
        return $response;
    }

    public static function cancelFlight($order_id, $params=[]) {
        // prd($params);
        $remarks = $params['remarks']??'CANCELLATION';
        $passengers = $params['passengers']??[];
        $response = [];
        $response['success'] = false;
        if($order_id && !empty($passengers)) {
            $order = Order::find($order_id);
            if($order) {
                $bookingId = $order->bookingId??'';
                $order_no = $order->order_no??'';
                $agent_id = $order->agent_id??'';
                $total_amount = $order->total_amount;
                $booking_details = json_decode($order->booking_details,true)??[];
                $booking_status = $booking_details['order']['status']??'';
                $departureDate = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['dt']??'';
                if(!empty($bookingId) && !empty($booking_status) && $booking_status == 'SUCCESS') { //&& !empty($departureDate) && strtotime($departureDate) > strtotime(date('Y-m-d'))
                    $trips = [];                    
                    if(isset($booking_details['itemInfos']) && isset($booking_details['itemInfos']['AIR'])) {

                        $tripInfos = $booking_details['itemInfos']['AIR']['tripInfos']??[];
                        foreach($tripInfos as $tripInfo) {

                            $src = $tripInfo['sI'][0]['da']['code']??'';
                            $dest = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
                            $departureDate = CustomHelper::DateFormat($tripInfo['sI'][0]['dt'],'Y-m-d')??'';
                            $sector = $src.'-'.$dest;

                            /*foreach($tripInfo['sI'] as $sI) {
                                $src = $sI['da']['code']??'';
                                $dest = $sI['aa']['code']??'';
                                $departureDate = CustomHelper::DateFormat($sI['dt'],'Y-m-d')??'';
                                $sector = $src.'-'.$dest;
                            }*/

                            $travellers = [];
                            foreach($passengers as $passenger) {
                                if($passenger['sector'] == $sector) {
                                    $travellers[] = [
                                        'fn' => $passenger['fN']??'',
                                        'ln' => $passenger['lN']??''
                                    ];
                                }
                            }

                            if(!empty($travellers) && count($travellers) > 0) {
                                $trips[] = [
                                    'src' => $src,
                                    'dest' => $dest,
                                    'departureDate' => $departureDate,
                                    'travellers' => $travellers
                                ];
                            }

                        }
                    }
                    $reqParams = [
                        "bookingId" => $bookingId,
                        "type" => "CANCELLATION",
                        "remarks" => $remarks,
                        "trips" => $trips
                    ];
                    // prd(json_encode($reqParams));
                    $endPoint = '/oms/v1/air/amendment/submit-amendment';
                    $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                    $apiResult = json_decode($resp, true);
                    $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                    $success = !empty($apiResult)?$apiResult['status']['success']:'';
                    if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                        $amendmentId = $apiResult['amendmentId']??'';
                        if($amendmentId) {
                            // $order->status = 'CANCELLATION';
                            // $order->save();

                            $req_data = [
                                'order_id' => $order->id,
                                'bookingId' => $bookingId,
                                'amendmentId' => $amendmentId,
                                'amendment_type' => 'CANCELLATION',
                                'request_json' => json_encode($reqParams),
                                'response_json' => $resp,
                            ];
                            $isSaved = OrderAmendments::create($req_data);
                            
                            $description = 'Order amendment request has been submitted successfully.';
                            $req_data = [];
                            $req_data['order_id'] = $order_id;
                            $req_data['orders_status'] = $order->status;
                            $req_data['comments'] = $description;

                            $user = auth::guard('admin')->user();
                            if($user && $user->id) {
                                $created_type = 'backend';
                                $user_id = $user->id??0;
                                $user_name = $user->name??'system';
                            } else {
                                $user = auth()->user();
                                $created_type = 'customer';
                                $user_id = $user->id??0;
                                $user_name = $user->name??'system';
                            }
                            $req_data['created_type'] = $created_type;
                            $req_data['created_by'] = $user_id;
                            $req_data['created_by_name'] = $user_name;
                            OrderStatusHistory::create($req_data);

                            $response['success'] = true;
                            $response['amendmentId'] = $amendmentId;
                            $response['message'] = 'Your request has been submitted successfully.';
                        } else {
                            $msg = $apiResult['errors'][0]['message']??'';
                            $response['message'] = 'Errors occurred while cancelling ticket. '.$msg;
                            $response['errors'] = $apiResult;
                        }
                    } else {
                        $msg = $apiResult['errors'][0]['message']??'';
                        $response['message'] = 'Errors occurred while cancelling ticket. '.$msg;
                        $response['errors'] = $apiResult['errors'];
                    }
                } else {
                    $response['message'] = 'Only SUCCESS Orders can be cancelled.';
                }
            } else {
                $response['message'] = 'Order details is missing.';
            }
        } else {
            $response['message'] = 'Order id is missing.';
        }
        return $response;
    }

    public static function parseFlightOfflineFare($params=[]) {
        $response = [];
        $response['success'] = false;
        $flight_details = $params['flight_details']??[];
        $booking_details = $params['booking_details']??[];
        $adult_price = $params['adult_price']??0;
        $child_price = $params['child_price']??0;
        $infant_price = $params['infant_price']??0;

        $old_total_amount = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TF']??0;
        $sIs = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI']??[];
        if($sIs) {
            $total_segments = count($sIs);
            $adult_BF = sprintf("%.2f",($adult_price/$total_segments));
            $child_BF = sprintf("%.2f",($child_price/$total_segments));
            $infant_BF = sprintf("%.2f",($infant_price/$total_segments));
            $newSIs = [];
            foreach($sIs as $sI) {
                $newSI = $sI;
                $tIs = $sI['bI']['tI']??[];
                // prd($tIs);
                if($tIs) {
                    $newTIs = [];
                    foreach($tIs as $tI) {
                        $newTI = $tI;
                        $NF = $tI['fd']['fC']['NF']??0;
                        $BF = $tI['fd']['fC']['BF']??0;
                        $TAF = $tI['fd']['fC']['TAF']??0;
                        $TF = $tI['fd']['fC']['TF']??0;
                        $pt = $tI['pt']??'';
                        if($pt == 'ADULT') {
                            $NF = $adult_BF;
                            $BF = $adult_BF;
                        } else if($pt == 'CHILD') {
                            $NF = $child_BF;
                            $BF = $child_BF;
                        } else if($pt == 'INFANT') {
                            $NF = $infant_BF;
                            $BF = $infant_BF;
                        }
                        $newTI['fd']['fC']['NF'] = sprintf("%.2f",($NF+$TAF));
                        $newTI['fd']['fC']['BF'] = $BF;
                        $newTI['fd']['fC']['TAF'] = $TAF;
                        $newTI['fd']['fC']['TF'] = sprintf("%.2f",($BF+$TAF));
                        $newTIs[] = $newTI;
                    }
                    $newSI['bI']['tI'] = $newTIs;
                }
                $newSIs[] = $newSI;
            }
            $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'] = $newSIs;
        }

        $totalPriceLists = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList']??[];
        if($totalPriceLists) {
            $newTotalPriceList = [];
            foreach($totalPriceLists as $totalPriceList) {
                $newTotalPriceListRow = $totalPriceList;
                if(isset($totalPriceList['fd'])) {
                    foreach($totalPriceList['fd'] as $pt => $val) {
                        $newVal = $val;
                        $NF = $val['fC']['NF']??0;
                        $BF = $val['fC']['BF']??0;
                        $TAF = $val['fC']['TAF']??0;
                        $TF = $val['fC']['TF']??0;
                        if($pt == 'ADULT') {
                            $old_adult_price = $BF;
                            $BF = $adult_price;
                        } else if($pt == 'CHILD') {
                            $old_child_price = $BF;
                            $BF = $child_price;
                        } else if($pt == 'INFANT') {
                            $old_infant_price = $BF;
                            $BF = $infant_price;
                        }
                        $newVal['fC']['NF'] = sprintf("%.2f",($BF+$TAF));
                        $newVal['fC']['BF'] = $BF;
                        $newVal['fC']['TAF'] = $TAF;
                        $newVal['fC']['TF'] = sprintf("%.2f",($BF+$TAF));
                        $newVal['afC']['TAF']['OT'] = $TAF;
                        $newTotalPriceListRow['fd'][$pt] = $newVal;
                    }
                }
                $newTotalPriceList[] = $newTotalPriceListRow;
            }
            $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'] = $newTotalPriceList;

            $ADT = 0;
            $CHD = 0;
            $INF = 0;
            $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
            if($travellerInfos) {
                $newTravellerInfos = [];
                foreach($travellerInfos as $key => $travellerInfo) {
                    $pt = $travellerInfo['pt']??'';
                    if($pt == 'ADULT') {
                        $ADT+=1;
                    } else if($pt == 'CHILD') {
                        $CHD+=1;
                    } else if($pt == 'INFANT') {
                        $INF+=1;
                    }

                    $new_pnr = '';
                    $oldPnrDetails = $travellerInfo['pnrDetails']??[];
                    if($oldPnrDetails) {
                        foreach($oldPnrDetails as $pnr_key => $pnr_val) {
                            if(empty($new_pnr)) {
                                $new_pnr = $pnr_val;
                            }
                        }
                    }

                    $new_atn = '';
                    $oldAirline_ticket_no = $travellerInfo['airline_ticket_no']??[];
                    if($oldAirline_ticket_no) {
                        foreach($oldAirline_ticket_no as $atn_key => $atn_val) {
                            if(empty($new_atn)) {
                                $new_atn = $atn_val;
                            }
                        }
                    }

                    $checkinStatusMap = [];
                    $pnrDetails = [];
                    $airline_ticket_no = [];
                    $segments = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI']??[];
                    if($segments) {
                        foreach($segments as $segment) {
                            $key = ($segment['da']['code']??'').'-'.($segment['aa']['code']??'');
                            $checkinStatusMap[$key] = 1;
                            $pnrDetails[$key] = $new_pnr;
                            $airline_ticket_no[$key] = $new_atn;
                        }
                    }
                    $travellerInfo['checkinStatusMap'] = $checkinStatusMap;
                    $travellerInfo['pnrDetails'] = $pnrDetails;
                    $travellerInfo['airline_ticket_no'] = $airline_ticket_no;
                    $newTravellerInfos[] = $travellerInfo;
                }
                $booking_details['itemInfos']['AIR']['travellerInfos'] = $newTravellerInfos;
            }
            $newTotalFareDetail = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']??[];
            $NF = $newTotalFareDetail['fC']['NF']??0;
            $BF = $newTotalFareDetail['fC']['BF']??0;
            $TAF = $newTotalFareDetail['fC']['TAF']??0;
            $TF = $newTotalFareDetail['fC']['TF']??0;

            $BF = ($adult_price*$ADT)+($child_price*$CHD)+($infant_price*$INF);

            $newTotalFareDetail['fC']['NF'] = sprintf("%.2f",($BF+$TAF));
            $newTotalFareDetail['fC']['BF'] = $BF;
            $newTotalFareDetail['fC']['TAF'] = $TAF;
            $newTotalFareDetail['fC']['TF'] = sprintf("%.2f",($BF+$TAF));
            $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail'] = $newTotalFareDetail;
        }
        $total_amount = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TF']??0;

        if($total_amount >= $old_total_amount) {
            $booking_details['order']['amount'] = $total_amount;
            $flight_details['info']['tripInfos'][0]['sI'] = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI']??[];
            $flight_details['info']['tripInfos'][0]['totalPriceList'] = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList']??[];
            $flight_details['info']['totalPriceInfo'] = $booking_details['itemInfos']['AIR']['totalPriceInfo']??[];
            $response['flight_details'] = $flight_details;
            $response['booking_details'] = $booking_details;
            $response['total_amount'] = $total_amount;
            $response['success'] = true;
        }
        return $response;
    }

    public static function getOrderSupplierPayment($order, $params=[]) {
        $amount = 0;
        if($order && $order->id) {
            $total_amount = $order->partial_amount;
            $admin_markup = $order->admin_markup??0;
            $booking_details = json_decode($order->booking_details,true)??[];
            $inventory_data = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'][0]['inventory_data']??[];
            $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
            if($inventory_data && $travellerInfos) {
                foreach($travellerInfos as $traveller) {
                    $pt = $traveller['pt']??'';
                    if($pt) {
                        switch ($pt) {
                            case 'ADULT':
                                $admin_markup += (($inventory_data['admin_adult_price']??0)-($inventory_data['adult_price']??0));
                            break;
                            case 'CHILD':
                                $admin_markup += (($inventory_data['admin_child_price']??0)-($inventory_data['child_price']??0));
                            break;
                            case 'INFANT':
                                $admin_markup += (($inventory_data['admin_infant_price']??0)-($inventory_data['infant_price']??0));
                            break;   
                            default:
                            break;
                        }
                    }
                }
            }
            $amount = $total_amount-$admin_markup;
        }
        return $amount;
    }


}