<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Exception;
use App\Models\Order;
use App\Models\OrderAmendments;
use App\Helpers\CustomHelper;


class CronController extends Controller {

    public function index(Request $request) {
        return "ok";
    }

    public function check_order_status(Request $request) {
        $results = Order::where('order_type',3)->where('payment_status',1)->whereIn('status',['PENDING','HOLD','ON_HOLD'])->get();
        // prd($results->toArray());
        if($results) {
            foreach($results as $row) {
                $resp = Order::checkBookingStatus($row->id);
                pr($resp);
            }
        }
        prd('('.count($results).') Done');
    }

    public function check_order_amendments_status(Request $request) {
        $results = OrderAmendments::where('status',0)->get();
        // prd($results->toArray());
        if($results) {
            $cron_last_run = date('Y-m-d H:i:s',strtotime('-30 minutes'));
            // prd($cron_last_run);
        	foreach($results as $row) {
                if($row->cron_last_run) {
                    if(strtotime($row->cron_last_run) > strtotime($cron_last_run) ) {
                        continue;
                    }
                }
                // prd($row->toArray());
        		$resp = Order::checkAmendmentStatus($row->id);
                $row->cron_last_run = date('Y-m-d H:i:s');
                $row->save();
                pr($resp);
        	}
        }
        prd('('.count($results).') Done');
    }

    public function check_offer_fare_cancel(Request $request) {
        $response = [];
        $OFFER_FARE_CANCEL_TIME = CustomHelper::WebsiteSettings('OFFER_FARE_CANCEL_TIME');
        if(is_numeric($OFFER_FARE_CANCEL_TIME) && $OFFER_FARE_CANCEL_TIME > 0) {
            $cron_last_run = date('Y-m-d H:i:s',strtotime('-'.$OFFER_FARE_CANCEL_TIME.' minutes'));
            // prd($cron_last_run);
            $query = Order::orderBy('id', 'desc');
            $query->where('order_type','=',3);
            $query->where('cancel_request',0);
            $query->where('payment_status',1);
            $query->where('inventory_id','>',0);
            $query->whereIn('status',['NEW']);
            $query->where('created_at','<=',$cron_last_run);
            $results = $query->get();
            // prd($results->toArray());
            if($results) {
                foreach($results as $order) {
                    if($order->inventory_id) {
                        $order_id = $order->id;
                        $reason = 'Offline ticket auto cancelled due to not any action perfomed by the admin for '.$OFFER_FARE_CANCEL_TIME.' minutes.';
                        $params = [];
                        $params['reason'] = $reason;
                        $params['wallet_comment'] = $reason;
                        $resp = Order::refundOrderPayment($order_id,$params);
                        $cancelled_response = [
                            'order_id' => $order_id
                        ];
                        if($resp) {
                            $cancelled_response['success'] = true;
                            $cancelled_response['message'] = ' Flight has been cancelled successfully';
                        } else {
                            $cancelled_response['success'] = false;
                            $cancelled_response['message'] = ' Flight has been not been cancelled, please try again or contact the adminstrator!';
                        }
                        $response[$order_id] = $cancelled_response;
                    }
                }
                if($response) {
                    CustomHelper::captureSentryMessage('Cron response for OFFER_FARE_CANCELLED: '.json_encode($response));
                }
            }
            prd($response);
        }
    }
}