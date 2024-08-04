<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\OrderPayments;
use App\Models\OrderPaymentsHistory;
use App\Models\WebhookResponse;
use App\Models\PaymentGateway;
use App\Helpers\CustomHelper;
use Session;
use Exception;
use Response;

class WebhookController extends Controller {


    public function razorpay(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $payload = $request->all();
        // prd($payload);
        if(!empty($payload)) {
            $input = $request->all();
            $paymentGateway = PaymentGateway::where(['value'=>'razorpay','status'=>1])->first();
            $RAZORPAY_KEY = $paymentGateway->perameter_1;
            $RAZORPAY_SECRET = $paymentGateway->perameter_2;
            $RAZORPAY_WEBHOOK_SECRET = $paymentGateway->perameter_2;

            $webhookSignature = $request->header('X-Razorpay-Signature');
            $webhookBody = $request->getContent();
            $IS_WEBHOOK_CALLED = CustomHelper::WebsiteSettings('IS_WEBHOOK_CALLED');
            $doNotVerifyWebhookSignature = CustomHelper::WebsiteSettings('doNotVerifyWebhookSignature');

            if(!empty($webhookSignature) && $payload) {
                $api = new Api($RAZORPAY_KEY, $RAZORPAY_SECRET);
                if($doNotVerifyWebhookSignature) {
                    // Do not verify razorpay signature.
                } else {
                    $api->utility->verifyWebhookSignature($webhookBody, $webhookSignature, $RAZORPAY_WEBHOOK_SECRET);
                }

                $paymentEventsArr = ['payment.created', 'payment.authorized', 'payment.captured', 'payment.refunded', 'payment.failed'];
                if(in_array($payload['event'], $paymentEventsArr)) {
                    $pg_payment_id = $payload['payload']['payment']['entity']['id']??'';
                    $pg_payment_status = $payload['payload']['payment']['entity']['status']??'';
                    $razorpay_amount = $payload['payload']['payment']['entity']['amount'];
                    $razorpay_currency = $payload['payload']['payment']['entity']['currency'];
                    $razorpay_entity = $payload['payload']['payment']['entity'];
                    // prd($pg_payment_id);
                    if(!empty($pg_payment_id)) {
                        $orderPayments = OrderPayments::where('pg_payment_id', $pg_payment_id)->first();
                        if($orderPayments && $orderPayments->id) {
                            $order = $orderPayments->orderData??[];
                            if($order && $order->id) {
                                if($pg_payment_status == 'created' || $pg_payment_status == 'captured') {
                                    if($order->payment_status == 1) {
                                        $orderPayments->status = 1;
                                        $orderPayments->pg_payment_status = 1;
                                        // Payment might get success in controller
                                    } else {
                                        $orderPayments->status = 1;
                                        $orderPayments->pg_payment_status = 1;

                                        $order->payment_status = 1;
                                        $order->save();
                                        $resp = Order::processPaymentSuccess($order->id);
                                        if($resp['success']) {
                                            $response['success'] = true;
                                        }
                                    }                                    
                                    $orderPayments->save();
                                }

                                $payment_history_data = [];
                                $payment_history_data['payment_id'] = $orderPayments->id;
                                $payment_history_data['amount'] = $razorpay_amount;
                                $payment_history_data['currency'] = $razorpay_currency;
                                $payment_history_data['user_id'] = $orderPayments->user_id;
                                $payment_history_data['name'] = $orderPayments->name;
                                $payment_history_data['email'] = $orderPayments->email;
                                $payment_history_data['address'] = $orderPayments->address;
                                $payment_history_data['phone'] = $orderPayments->phone;
                                $payment_history_data['payment_channel'] = $orderPayments->payment_channel;
                                $payment_history_data['description'] = $orderPayments->description;
                                $payment_history_data['order_id'] = $orderPayments->order_id;
                                $payment_history_data['status'] = $orderPayments->status;
                                $payment_history_data['payment_type'] = $razorpay_entity['method']??'';
                                $payment_history_data['payment_detail'] = $orderPayments->payment_detail;
                                $payment_history_data['refunded_amount'] = $orderPayments->refunded_amount;
                                $payment_history_data['refund_note'] = $orderPayments->refund_note;
                                $payment_history_data['customer_type'] = $orderPayments->customer_type;
                                $payment_history_data['pg_order_id'] = $orderPayments->pg_order_id;
                                $payment_history_data['pg_payment_id'] = $pg_payment_id;
                                $payment_history_data['pg_response'] = json_encode($payload);
                                if($pg_payment_status == 'created' || $pg_payment_status == 'captured') {
                                    $payment_history_data['pg_payment_status'] = 1;
                                } else {
                                    $payment_history_data['pg_payment_status'] = 2;
                                }
                                $payment_history_data['pg_created_at'] = $razorpay_entity['created_at']??'';
                                $payment_history_data['pg_response_date'] = date('Y-m-d H:i:s');
                                OrderPaymentsHistory::create($payment_history_data);
                            } else {
                            // CustomHelper::captureSentryMessage('empty order_data for='.json_encode($payload));
                            }
                        }
                    } else {
                        // CustomHelper::captureSentryMessage('empty razorpay_order_id for='.json_encode($payload));
                    }
                }

                if(isset($payload['event']) && !empty($payload['event']) && strtolower($IS_WEBHOOK_CALLED) != 'yes'){
                    $this->saveWebhookData($request, 'razorpay');
                }
            } else {
                $response['message'] = "Signature not found.";
            }

            if(strtolower($IS_WEBHOOK_CALLED) == 'yes') {
                $this->saveWebhookData($request, 'razorpay');
            }
        } else {
            $response['message'] = "Data not found.";
        }

        // return $response_msg;
        return Response::json($response, 200);
    }

    private function saveWebhookData($request, $from='') {
        $webhookBody = $request->getContent();
        $payload = $request->all();

        if(!empty($payload) && isset($payload['payload'])) {
            $payment_update_data = [];

            $event_type = (isset($payload['event'])) ? $payload['event'] : '';
            $created_at = date('Y-m-d H:i:s');
            $transfer_date = (isset($payload['created_at'])) ? $payload['created_at'] : '';
            if($transfer_date){
                $created_at = date('Y-m-d H:i:s',$transfer_date);
            }
            $settlement_status = '';
            $transfer_status = '';
            $razorpay_payment_id = '';
            if(isset($payload['payload']['payment']) && isset($payload['payload']['payment']['entity'])) {
                $razorpay_payment_id = $payload['payload']['payment']['entity']['id'];
            }
            $settlement_date = date('Y-m-d H:i:s');

            if($event_type == 'transfer.processed') {
                if(isset($payload['payload']['transfer']['entity']['source'])) {
                    $razorpay_payment_id = $payload['payload']['transfer']['entity']['source'];
                    $settlement_status = $payload['payload']['transfer']['entity']['settlement_status'];
                    $transfer_status = $payload['payload']['transfer']['entity']['status'];
                    $settlement_timstamp = $payload['payload']['transfer']['entity']['created_at'];
                    if($settlement_timstamp) {
                        $settlement_date = date('Y-m-d H:i:s',$settlement_timstamp);
                    }
                }
            }

            if($settlement_status) {
                $payment_update_data['settlement_status'] = $settlement_status;
                $payment_update_data['settlement_date'] = $settlement_date;
            }

            if($transfer_status) {
                $payment_update_data['transfer_status'] = $transfer_status;
                $payment_update_data['transfer_date'] = $created_at;
            }


            if($event_type == 'settlement.processed') {
                if(isset($payload['payload']['settlement']['entity'])) {
                    $api = new Api(config('custom.RAZORPAY_KEY'), config('custom.RAZORPAY_SECRET'));
                    $recipientSettlementId = $payload['payload']['settlement']['entity']['id'];
                    $response =	$api->transfer->all(array('recipient_settlement_id'=> $recipientSettlementId));
                    if(isset($response['items']) && isset($response['items'][0])) {
                        $payment_update_data['settlement_status'] = $response['items'][0]['settlement_status'];
                        $razorpay_payment_id = $response['items'][0]['source'];
                        $payment_update_data['settlement_utr']  = $payload['payload']['settlement']['entity']['utr'];
                        $settlement_timstamp = $response['items'][0]['processed_at'];
                        if($settlement_timstamp) {
                            $settlement_date = date('Y-m-d H:i:s',$settlement_timstamp);
                            $payment_update_data['settlement_date'] = date('Y-m-d H:i:s',$settlement_timstamp);
                        }
                    }
                }
            }

            $webhook_data = [];
            $webhook_data['method'] = $request->method();
            $webhook_data['from'] = $from;
            $webhook_data['event_type'] = (isset($payload['event'])) ? $payload['event'] : '';
            $webhook_data['headers'] = json_encode($request->header());
            $webhook_data['razorpay_payment_id'] = $razorpay_payment_id;
            $webhook_data['request_body'] = $webhookBody;
            $webhook_data['request_data'] = (is_array($payload)) ? json_encode($payload) : $payload;
            $webhook_data['created_at'] = $created_at;
            WebhookResponse::create($webhook_data);

            if($payment_update_data && !empty($razorpay_payment_id)) {
                $is_updated = OrderPaymentsHistory::where('pg_order_id', $razorpay_payment_id)->update($payment_update_data);
                if($is_updated) {
                    return true;
                } else {
                    $payment_update = OrderPaymentsHistory::where('pg_payment_id', $razorpay_payment_id)->update($payment_update_data);
                }
                if(empty($payment_update) && empty($is_updated)) {
                    // $msg = "Razorpay Payment Id: ".$razorpay_payment_id." not found for settlement status updation";
                    // CustomHelper::captureSentryMessage($msg);
                }
            }
        }
    }
}