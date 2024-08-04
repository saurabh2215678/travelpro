<?php
namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Blog;
use App\Models\Order;
use App\Models\Widget;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\CmsPages;
use App\Models\UserWallet;
use App\Models\Testimonial;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\ThemeCategories;
use App\Models\EnquiriesMaster;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use DateTime;
use Validator;
use Response;

class HomeController extends Controller {

	// Dashboard - URL: /admin
	
	public function index(Request $request) {
		$data = [];
		
		$getIds = EnquiriesMaster::where('category', 'closed')->where('type','lead-status')->select('id')->get();
		$xArray = [];
		foreach ($getIds as $key => $get_id) { $xArray[] = $get_id->id; }
		
		$data['assign_enquiries'] = Enquiry::where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        })->where(function($query1) use ($xArray){
            $query1->WhereNotIn('lead_status', $xArray);
            $query1->orWhereNull('lead_status');
        })->whereNotNull('cc_id')->where('cc_id','!=',0)->count();

		$data['unassign_enquiries'] = Enquiry::where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        })->where(function($query1) use ($xArray){
            $query1->WhereNotIn('lead_status', $xArray);
            $query1->orWhereNull('lead_status');
        })->where(function($query2){
            $query2->whereNull('cc_id');
            $query2->orWhere('cc_id','=',0);
        })->count();

		$data['packages'] = Package::where('is_activity',0)->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        })->count();

        $data['destinations'] = Destination::where(function($query){
            $query->where('is_city', 0);
            $query->where('parent_id', 0);
            $query->orWhereNull('is_city');
            $query->orWhereNull('parent_id');
        })->count();

		$user = auth()->user();
		$user_id = 0;
		$is_vendor = 0;
		if($user) {
			$user_id = $user->id??0;
			$is_vendor = $user->is_vendor??0;
		}

		$query = Accommodation::all();
		$data['accommodations'] = $query->count();

		if($is_vendor == 1){
			$data['accommodations'] = $query->where('vendor_id',$user_id)->count();
		}

		$agents_query = User::where(function($query){
            $query->where('is_agent',1);
            $query->orWhere('is_vendor',1);
        });
		$data['agents'] = $agents_query->count();

		$blogQuery = Blog::where(function($query){
			$query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
		});
		$data['blogs'] = $blogQuery->where('type','blogs')->count();

		$pageQuery = CmsPages::where(function($query){
			$query->where('parent_id', 0);
			$query->where('is_deleted', 0);
			$query->orWhereNull('parent_id');
		});
		$data['cms'] = $pageQuery->where('cms_type','page')->count();
		//prd($data['cms']);

		$testimonial_query = Testimonial::where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
		$data['active_testimonials'] = $testimonial_query->where('status',1)->count();

		$testimonial_query1 = Testimonial::where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
		$data['inactive_testimonials'] = $testimonial_query1->where('status',0)->count();

		$theme_query = ThemeCategories::where(function ($query) {
			$query->where("identifier",'package');
			$query->where("parent_id", 0);
			$query->orWhereNull("parent_id");
		});
		$data['theme_package'] = $theme_query->where('status',1)->count();

		$theme_query = ThemeCategories::where(function ($query) {
			$query->where("identifier",'activity');
			$query->where("parent_id", 0);
			$query->orWhereNull("parent_id");
		});
		$data['theme_activity'] = $theme_query->where('status',1)->count();

		$payment_status='confirmed';
		if($payment_status=='confirmed') {
			$query = Order::orderBy('id', 'desc');
			$status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
			$status_query->where(function($q1){
				$q1->where('category','confirmed');
			});
			$status_arr = $status_query->get()->pluck('id')->toArray();
			$query->where('cancel_request',0);
			$query->where('payment_status',1);
			$query->where(function($q1) use ($status_arr) {
				$q1->where(function($q2) use ($status_arr) {
					$q2->where('order_type','!=',3);
                        // $q2->whereIn('status',['Order Accepted','Voucher created']);
					$q2->whereIn('orders_status',$status_arr);
				});
				$q1->orWhere(function($q2) {
					$q2->where('order_type',3);
					$q2->whereIn('status',['SUCCESS','PENDING','ON_HOLD']);
				});
			});
			if($is_vendor == 1){
				$query->where('vendor_id',$user_id);
			}
			$query->where('order_type','!=',7);
			$data['orders_confirmed'] = $query->count();
		}

		$payment_status='pending';
		if($payment_status=='pending') {
			$query = Order::orderBy('id', 'desc');
			$status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
			$status_query->where(function($q1){
				$q1->where('category','processing');
				$q1->orWhere('category','new');
			});
			$status_arr = $status_query->get()->pluck('id')->toArray();
			$query->where('cancel_request',0);
			$query->where('payment_status',1);
			$query->where(function($q1) use ($status_arr) {
				$q1->where(function($q2) use ($status_arr) {
					$q2->where('order_type','!=',3);
                        // $q2->whereNotIn('status',['Order Accepted','Voucher created']);
					$q2->whereIn('orders_status',$status_arr);
				});
				$q1->orWhere(function($q2) {
					$q2->where('order_type',3);
					$q2->whereNotIn('status',['SUCCESS','PENDING','ON_HOLD']);
				});
			});
			if($is_vendor == 1){
				$query->where('vendor_id',$user_id);
			}
			$query->where('order_type','!=',7);
			$data['orders_pending'] = $query->count();
		}

		$total_agent_sales = Order::withWhereHas('userData',function($q1){
			$q1->where('is_agent',1);
		})->selectRaw('user_id,sum(total_amount) as total_sales')->groupBy('user_id')->orderbyRaw('sum(total_amount) desc')->limit(5)->get();
		$data['total_agent_sales'] = $total_agent_sales;

		$agent_wallet_balances = UserWallet::withWhereHas('UserDetails',function($q2){
			$q2->where('is_agent',1);
		})->selectRaw('user_id,sum(amount) as total_balances')->groupBy('user_id')
		->orderbyRaw('sum(amount) desc')->limit(5)->get();
		$data['agent_wallet_balances'] = $agent_wallet_balances;

		return view('admin.index', $data);
	}

	public function search(Request $request) {
		$nicknames = [];
		$nicknames['keyword'] = 'Booking ID';
		$message = [];
		$rules = [];
		$rules['keyword'] = 'required';
		$message['required'] = ':attribute is required.';
		$validator = Validator::make($request->all(), $rules, $message, $nicknames);
		if ($validator->fails()){
			return back()->withErrors($validator)->withInput();
		} else {
			$order_no = $request->keyword;
			$order = Order::where('order_no',$order_no)->first();
			if($order) {
				$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
            	return redirect(route($ADMIN_ROUTE_NAME.'.orders.details',$order->order_no));
			} else {
				return back()->withErrors('Booking ID details not found.')->withInput();
			}
		}
	}

	public function loadSalesData(Request $request) {
		$response = [];
		$graph = [];
		$response['success'] = false;
		$dayType = $request->dayType??'Daily';
		$startDate = $endDate = '';
		$status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
		$status_query->where(function($q1){
			$q1->where('category','confirmed');
		});
		$status_arr = $status_query->get()->pluck('id')->toArray();

		$user = auth()->user();
		$user_id = 0;
		$is_vendor = 0;
		if($user) {
			$user_id = $user->id??0;
			$is_vendor = $user->is_vendor??0;
		}
		if($dayType == 'Daily') {
			$endDate = date('Y-m-d');
			//$startDate = date("Y-m-d", strtotime( "$endDate -6 day" ) );
			$startDate = date("Y-m-d", strtotime( "$endDate -1 months" ) );
			$startDate = $startDate.' 00:00:00';
			$endDate = $endDate.' 23:59:59';
			$begin = new DateTime($startDate);
			$end = new DateTime($endDate);
			$query = Order::selectRaw('sum(total_amount) as total_sales,created_at as date, DATE_FORMAT(created_at, "%Y%m%d") AS yearMonth')->where('payment_status',1)->whereIn('orders_status',$status_arr)->whereBetween('created_at',[$startDate,$endDate])->groupByRaw('DATE_FORMAT(created_at, "%Y%m%d")')->orderBy('date','asc');
			if($is_vendor == 1){
				$query->where('vendor_id',$user_id);
			}
			$records = $query->get();
			$graph = [];
			if($records) {
				$data = [];
				$labels = [];
				if($records) {
					foreach($records as $row) {
						$data[CustomHelper::DateFormat($row->date,'Ymd')] = $row->total_sales;
						$labels[] = CustomHelper::DateFormat($row->date,'Y-m-d');
					}
				}
				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					$date_arr[]=$i->format("d M Y");
					if(isset($data[$i->format("Ymd")])){
						$data_array[] = $data[$i->format("Ymd")];
					} else {
						$data_array[] = 0;
					}
				}
			}
		} else if($dayType == 'Weekly') {
			$startDate=$endDate='';
			$endDate=date('Y-m-d');
			$startDate = date( "Y-m-d", strtotime( "$endDate -41 day" ) );
			$startDate = $startDate.' 00:00:00';
			$endDate = $endDate.' 23:59:59';
			$begin = new DateTime( $startDate );
			$end   = new DateTime( $endDate);
			$date_arr = [];
			for($i = 7; $i >= 1; $i--){
				$date_arr[]=$i;
			}
			$query =Order::whereBetween('created_at',[$startDate,$endDate])->selectRaw('sum(total_amount) as total_sales, created_at, WEEKDAY(created_at) AS yearMonth')->where('payment_status',1)->whereIn('orders_status',$status_arr)->groupBy('yearMonth')->orderBy('yearMonth','asc'); 
			if($is_vendor == 1){
				$query->where('vendor_id',$user_id);
			}
			$form_data = $query->get()->toArray();

			$formsname=array();
			$labels=array();
			$data_array=array();
			if(!empty($form_data)){
				foreach($form_data as $data){
					$data_array[$data['yearMonth']]['order']=$data['total_sales'];
					$formsname['order']='order';
				}
			}
			$finalData=[];
			if(!empty($date_arr)) {
				$cur_date = new DateTime(date('Y-m-d'));
				$cur_week = $cur_date->format("W");
				foreach($date_arr as $key=>$week_no) {
					$week_arr = CustomHelper::getStartAndEndDateOfWeek($cur_week-($week_no-1),date('Y'));
					$labels[]= CustomHelper::DateFormat($week_arr['start_date'],'d-M').' - '.CustomHelper::DateFormat($week_arr['end_date'],'d-M');
					if(!empty($formsname)){
						foreach($formsname as $frm){
							$finalData[$frm][]=isset($data_array[$week_no][$frm])?$data_array[$week_no][$frm]:0;
						}
					}else{
						$finalData['order'][] =0;
					}		
				}
				$finalData['form_name']=$formsname;
			}
			$data_array = $finalData['order'] ?? [];
			$date_arr = $labels;
		} else if($dayType == 'Monthly') {
			$startDate=$endDate='';
			$startDate=date('Y-m-01',strtotime('-6 months')); 
			$endDate=date('Y-m-t');
			$startDate = $startDate.' 00:00:00';
			$endDate = $endDate.' 23:59:59';
			$date_arr=[];
			$begin = new DateTime( $startDate );
			$end   = new DateTime( $endDate);

			for($i = $begin; $i <= $end; $i->modify('+1 months')){
				$date_arr[$i->format("Ym")]=$i->format("Y-m-d");
			}
			$query=Order::whereBetween('created_at',[$startDate,$endDate])
			->selectRaw('sum(total_amount) as total_sales,EXTRACT(YEAR_MONTH FROM date(created_at)) AS yearMonth')->where('payment_status',1)->whereIn('orders_status',$status_arr)->groupBy('yearMonth')->orderBy('yearMonth','asc');
			if($is_vendor == 1) {
				$query->where('vendor_id',$user_id);
			}
			$form_data = $query->get()->toArray();

			$formsname=array();
			$data_array=array();
			$labels=array();
			if(!empty($form_data)){
				foreach($form_data as $data){
					$data_array[$data['yearMonth']]['Sales']=$data['total_sales'];
					$formsname['Sales']='Sales';
				}
			}
			$finalSaleData=[];
			if(!empty($date_arr)){
				foreach($date_arr as $key=>$rec){
					$labels[]=date('M Y',strtotime($rec)); 
					if(!empty($formsname)){
						foreach($formsname as $frm){
							$finalSaleData[$frm][]=isset($data_array[$key][$frm])?$data_array[$key][$frm]:0;
						}
					} else {
						$finalSaleData['Sales'][] =0;
					}
				}
				$finalSaleData['form_name']=$formsname;
			}
			$data_array = $finalSaleData['Sales']?? [];
			$date_arr = $labels;
		}
		$graph['data'] = $data_array;
		$graph['labels'] = $date_arr;		
		$response['success'] = true;
		$response['graph'] = $graph;
		return response()->json($response);
	}


	public function verify_old_password(Request $request){


		$back_url = (isset($request->back_url))?$request->back_url:'';

		if(!empty($back_url)){

			if($request->method() == 'POST'){
				//prd($request);

				$auth_user = auth()->guard('admin')->user();

				$message = [];
				$rules = [];

				$rules['password'] = 'required';

				$validator = Validator::make($request->all(), $rules, $message);

				$validator->after(function($validator) use ($auth_user){
					if (!Hash::check(request('password'), $auth_user->password)){
						$validator->errors()->add('password', 'Password did not matched!');
					}
					else{
						session(['verify_password'=>TRUE, 'verify_time'=>date('Y-m-d H:i:s')]);
					}
				});

				if ($validator->fails()){
					return back()->withErrors($validator);
				}
				elseif(!empty($back_url)){
					return redirect(url($back_url));
				}
				else{
					return back()->with('success', 'Password has been verified!');
				}
			}
			return view('admin.verify_password');
		}
		else{
			return back();
		}

	}

	public function verify_password(Request $request){

		if($request->method() == 'POST'){
				//prd($request->toArray());

			$auth_user = auth()->guard('admin')->user();

			$message = [];
			$rules = [];

			$rules['password'] = 'required';

			$validator = Validator::make($request->all(), $rules, $message);

			$validator->after(function($validator) use ($auth_user){
				if (!Hash::check(request('password'), $auth_user->password)){
					$validator->errors()->add('password', 'Password did not matched!');
				}
				else{
					session(['verify_password'=>TRUE, 'verify_time'=>date('Y-m-d H:i:s')]);
				}
			});

			if ($validator->fails()){
				return back()->withErrors($validator);
			}
			// elseif(!empty($back_url)){
			// 	return redirect(url($back_url));
			// }
			else{
				return back()->with('success', 'Password has been verified!');
			}
		}
		else{
			return back();
		}

	}

	/* ck_upload */
	public function ckUpload(Request $request){
        //pr(csrf_token());
        //prd($request->toArray());

        $response = [];

        $response['success'] = false;

        $type = (isset($request->type))?$request->type:'';

        if ($request->hasFile('upload')){

            $file = $request->file('upload');

            $path = 'ck';

            /*if(!empty($type)){
                $path = $type.'/'.'ck';
            }*/

            //UploadFile($file, $path, $ext='')

            $ext='jpg,jpeg,png,gif';

            $uploadResult = CustomHelper::UploadFile($file, $path, $ext);

            //prd($upload_result);

            if($uploadResult['success']){

            	$fileName = $uploadResult['file_name'];
                
                $funcNum = $request->CKEditorFuncNum;
                // Optional: instance name (might be used to load a specific configuration file or anything else).
                $CKEditor = $request->CKEditor;
                // Optional: might be used to provide localized messages.
                $langCode = $request->langCode;

                // Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
                //$url = $uploadResult['fileUrl'];

            	$url = asset('storage/'.$path.'/'.$fileName);
                // Usually you will only assign something here if the file could not be uploaded.
                $message = 'Image/file uploaded successfully.';

                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
            }
            else{
                return response()->json($response);
            }
        }
    }

	public function flightApiBalance(Request $request) {
		$resp = CustomHelper::flightApiBalance($refresh=true);
		return Response::json($resp, 200);
	}
    


	/* end of controller */
}
