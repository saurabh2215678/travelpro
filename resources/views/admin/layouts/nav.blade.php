<?php
$websiteSettingsArr=CustomHelper::getSettings(['FRONTEND_LOGO']);
$logoSrc = asset('assets/logo.png');
$storage = Storage::disk('public');
$path = "settings/";
if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
	$logo = $websiteSettingsArr['FRONTEND_LOGO'];
	if($storage->exists($path.$logo)){
		$logoSrc = asset('/storage/'.$path.$logo);
	}
}
?>
<div class="hidesidebar"><i class="fa fa-arrow-left"></i></div>
<div class="leftsec">
	<div class="menuicon"><span></span>
		<img class="admin-logo" src="{{$logoSrc}}" alt="Admin"  />
		<!--<small>Menu</small>-->
	</div>
	<div class="fullwidth leftsec1">
		<ul class="main-nav clearfix adminleft">
			<?php
			$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
			$type = (isset(request()->type))?request()->type:'';
			$faq_type = (isset(request()->module))?request()->module:'';
			if($faq_type) {
                if(session()->has('pa_edit')) {
                	$pa_edit = session('pa_edit');
                	$faq_package_type = '';
                    if($pa_edit == 'packages') {
                        $faq_package_type = 'packages';
                    } if($pa_edit == 'activity') {
                        $faq_package_type = 'activity';
                    }
                }
            }
            //prd($faq_type);
			$segment = (isset(request()->segment))?request()->segment:'';
			$order = (isset(request()->order))?request()->order:'';
			$user = auth()->user();
			$is_vendor = auth()->user() ?auth()->user()->is_vendor :'';

			$online_booking = CustomHelper::isOnlineBooking();
			
			// $online_booking = App\Models\SeoMetaTag::where('module_type','major')->where('online_booking',1)->where('status',1)->count();

			?>
			@if(CustomHelper::checkPermission('dashboard','keyStatistics'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.home') === 0 ? ' class="active"' : '' !!}>
				<a href="{{url('admin')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
			</li>
			@endif


			@if(CustomHelper::checkPermission('enquiries','view') || CustomHelper::checkPermission('all_masters','view'))
			<?php
			$enquiry_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries') === 0 || ($type == 'lead-source' || $type == 'lead-status' || $type == 'rating'))? 'class="active"':'';
			?>
			<li <?php echo $enquiry_active; ?> >
				<a  class="dropul subtab"><i class="fas fa-tasks"></i> <span>Enquiry Management</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('enquiries','view'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries.view') === 0) ? ' class="sub_active"' : '' !!}>
						<?php /* <li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'/enquiries ') === 0 ? ' class="sub_active"' : '' !!}> 
						<a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Contact Us</span></a> */ ?>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries.index', ['pending_enquiries'=>'pending_enquiries']) }}" ><i class="fa fa-circle text-aqua"></i> <span>Enquiries List</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('all_masters','view'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.master_enquiries.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.master_enquiries.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.master_enquiries.edit') === 0) && ($type == 'lead-source' || $type == 'lead-status' || $type == 'rating')  ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.master_enquiries.index',['type'=>'lead-source']) }}" ><i class="fa fa-circle text-aqua"></i> <span>Enquiries Master</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if($online_booking)

			<?php if(CustomHelper::checkPermission('orders','view')){ ?>
				<?php
				$order_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendor-orders') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.details') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.offline_inventory') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline_route') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.booking_list') === 0 || $type == 'order-status')? 'class="active"':'';
				?>
				
				<li <?php echo $order_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-ticket"></i> <span>Booking Management</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('orders','view'))
					<li {!! ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.index') === 0) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendor-orders.index') === 0) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.cab_voucher_view') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.cab') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.package_voucher_view') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.package') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.flight_voucher_view') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.hotel_voucher_view') === 0) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.hotel') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.activity_voucher_view') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.rental') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.rental_voucher_view') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.voucher.activity') === 0 ) || ($order=='confirmed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.details') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.booking_list') === 0 ) ? ' class="sub_active"' : '' !!}>
						<?php
						$order_type = '';
						$order_route = 'orders';
						if($is_vendor == 1){
							$order_route = 'vendor-orders';
							$order_type = 5;
						}
						?>

						<a href="{{ route($ADMIN_ROUTE_NAME.'.'.$order_route.'.index',['order'=>'confirmed','range'=>'custom','order_type'=>$order_type]) }}" ><i class="fa fa-circle text-aqua"></i> <span>View Bookings</span></a>

					</li>
					<?php if($is_vendor != 1){ ?>
						<?php /* <li {!! $order=='pending' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.view') === 0 ? ' class="sub_active"' : '' !!}> */ ?>
						<li {!! ($order=='pending' || $order=='amendment_progress')? ' class="sub_active"' : '' !!}>
							<a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'pending','range'=>'custom','order_type'=>$order_type]) }}" ><i class="fa fa-circle text-aqua"></i> <span>Pending Bookings</span></a>
						</li>

						<!-- <li {!! ($order=='amendment_progress') ? ' class="sub_active"' : '' !!}>
							<a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'amendment_progress','range'=>'custom','order_type'=>$order_type]) }}" ><i class="fa fa-circle text-aqua"></i> <span>Amendment in Progress</span></a>
						</li> -->
						
						<li {!! $order=='failed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.index') === 0 || ($order=='failed' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.details') === 0) ? ' class="sub_active"' : '' !!}>
							<a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'failed','range'=>'custom','order_type'=>$order_type]) }}" ><i class="fa fa-circle text-aqua"></i> <span>Failed Bookings</span></a>
						</li>
					<?php } ?>
					<li {!! $order=='calendar_booking' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.calendar_booking') === 0 || $order_type=='confirmed' || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendor-orders.calendar_booking') === 0 || ($order=='calendar_booking' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.details') === 0) ? ' class="sub_active"' : '' !!}>
						<!-- <a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Orders List</span></a> -->
						<a href="{{ route($ADMIN_ROUTE_NAME.'.'.$order_route.'.calendar_booking',['order'=>'calendar_booking','range'=>'custom','order_type'=>$order_type]) }}" ><i class="fa fa-circle text-aqua"></i> <span>Booking Calendar</span></a>
					</li>
					<?php if($is_vendor != 1){ ?>
						<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.orders.transactions') === 0 ? ' class="sub_active"' : '' !!}>
							<a href="{{ route($ADMIN_ROUTE_NAME.'.orders.transactions') }}" ><i class="fa fa-circle text-aqua"></i> <span>Transactions</span></a>
						</li>

						<li {!! ($order=='cancel_request') ? ' class="sub_active"' : '' !!}>
		
						<a href="{{ route($ADMIN_ROUTE_NAME.'.'.$order_route.'.index',['order'=>'cancel_request','range'=>'custom','order_type'=>$order_type]) }}" ><i class="fa fa-circle text-aqua"></i> <span>Cancellation Request</span></a>

					</li>
					<?php } ?>
					@endif

					@if(CustomHelper::checkPermission('orders','view'))
					<?php if($is_vendor != 1){ ?>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.master_enquiries.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.master_enquiries.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.master_enquiries.edit') === 0) && $type == 'order-status' ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.master_enquiries.index',['type'=>'order-status']) }}" ><i class="fa fa-circle text-aqua"></i> <span>Order Status</span></a>
					</li>
					<?php } ?>
					@endif

					@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('orders','view'))
					<?php if($is_vendor != 1){ ?>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.offline_inventory') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.offline_inventory_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.offline_inventory_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline_route') === 0 )? ' class="sub_active"' : '' !!} >
						<a href="{{ route($ADMIN_ROUTE_NAME.'.airline_route.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Offline Flight</span></a>
					</li>
					<?php } ?>
					@endif

					<?php /*@if(CustomHelper::checkPermission('customer_review','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries.customer_reviews') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries.customerView') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries.customer_review_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries.customer_review_edit') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries.customer_reviews') }}" ><i class="fa fa-circle text-aqua"></i> <span>Customer Review</span></a>
					</li>
					@endif */ ?>
				</ul>
			</li>
			<?php } ?>
			@endif
			

			@if(CustomHelper::checkPermission('packages','view') || CustomHelper::checkPermission('activity','view')|| CustomHelper::checkPermission('all_masters','view'))
			<?php

			$package_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.activity_view') === 0  || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.additional_info') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.info_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.info_edit') === 0 ||strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.info_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.info_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenaries') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenary_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenary_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenary_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.add_agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.add_agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flight') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flight_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.departure') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.departure') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.packageprice') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.packageprice') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.seo_meta') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.seo_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.seo_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.seo_meta') === 0 || $faq_type=='packages' || $faq_type=='activity' ||  strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_inclusion_lists') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_airlines') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.airline_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.air_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.airline_view') === 0 || strpos(Route::currentRouteName(),$ADMIN_ROUTE_NAME.'.packages.package-category') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_category_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.serviceLevel') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.settings') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.settings_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.settings_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flags') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flags_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flags_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.theme') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.price_category') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_inclusion_lists') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.list_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.list_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.list_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_airlines') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.airline_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.air_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.airline_view') === 0 || strpos(Route::currentRouteName(),$ADMIN_ROUTE_NAME.'.activity.package-categor') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_category_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.flags') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.flags_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.flags_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.settings') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.settings_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.settings_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_view') === 0 || $segment=='packages' || $segment=='activity'  ) ? 'class="active"':'';
			?>
			<li <?php echo $package_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Packages/Activities</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('packages','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.additional_info') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.info_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.info_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenaries') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenary_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenary_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.itenary_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.add_agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flight') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flight_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.departure') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.packageprice') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.seo_meta') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.seo_view') === 0 || $faq_type=='packages' && $segment=='packages' && $segment=='activity' ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.packages.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Packages</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('all_masters','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_inclusion_lists') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.serviceLevel') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_airlines') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.airline_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.air_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.airline_view') === 0 || strpos(Route::currentRouteName(),$ADMIN_ROUTE_NAME.'.packages.package-categor') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_category_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.theme_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flags') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flags_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.flags_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.settings') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.settings_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.settings_edit') === 0 || $segment=='packages' ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.packages.type_index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Packages Options</span></a>
					</li>
					@endif
					
					@if(CustomHelper::isAllowedModule('activity_listing')) 

					@if(CustomHelper::checkPermission('activity','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.activity_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.info_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.info_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.packageprice') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.departure') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.seo_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.seo_meta') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.add_agent_price') === 0 || $faq_type=='activity'? ' class="sub_active"' : ''!!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.activity.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Activities</span></a>
					</li>
					
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_inclusion_lists') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.list_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.list_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.list_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_airlines') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.airline_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.air_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.airline_view') === 0 || strpos(Route::currentRouteName(),$ADMIN_ROUTE_NAME.'.activity.package-categor') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.package_category_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.theme_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.flags') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.flags_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.flags_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.settings') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.settings_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activity.settings_edit') === 0 || $segment=='activity' ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.activity.theme_index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Activity Options</span></a>
					</li>
					@endif
					@endif

					@if(CustomHelper::checkPermission('all_masters','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.price_category.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.price_category.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.price_category.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.price_category.view') === 0 ? ' class="sub_active"' : '' !!}>
						
					<a href="{{ route($ADMIN_ROUTE_NAME.'.price_category.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Price Categories</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if(CustomHelper::checkPermission('destinations','view') || CustomHelper::checkPermission('all_masters','view'))
			<?php
			$destinaion_listing_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.view') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.additional_info') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.info_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.info_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.seo_meta') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.seo_view') === 0 || $faq_type=='destinations' || $faq_type=='destination_packages' || $faq_type=='destination_activity' || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.destination_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.flags') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.flags_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.flags_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.type_edit') === 0) ? 'class="active"':'';
			?>
			<li <?php echo $destinaion_listing_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-map-marker" aria-hidden="true"></i> <span>Destinations</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>

					@if(CustomHelper::checkPermission('destinations','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.view') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.additional_info') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.info_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.info_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.seo_meta') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.seo_view') === 0 || $faq_type=='destinations' || $faq_type=='destination_packages' || $faq_type=='destination_activity' ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.destinations.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Destinations</span></a>
					</li>
					@endif
					@if(CustomHelper::checkPermission('all_masters','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.destination_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.flags') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.flags_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.destinations.flags_edit') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.destinations.destination_types') }}" ><i class="fa fa-circle text-aqua"></i> <span>Destination Options</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if(CustomHelper::checkPermission('accommodations','view') || CustomHelper::checkPermission('all_masters','view'))
			<?php
			$hotel_listing_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.view') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.additional_info') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.info_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.info_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodation_room') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.inventory') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.seo_meta') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.seo_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.add_agent_price') === 0 || $faq_type=='accommodations' || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_features') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.features_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.feature_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.feature_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_plan_includes') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.plan_include_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.plan_include_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.plan_include_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_master') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomview_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomview_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomView') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_facilities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomtype_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_features') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomfeature_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomfeatured_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_feature_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_type_view') === 0) ? 'class="active"':'';
			?>
			@if(CustomHelper::isAllowedModule('hotel_listing'))
			<li <?php echo $hotel_listing_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-hotel" aria-hidden="true"></i> <span>Accommodations</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>

					@if(CustomHelper::isAllowedModule('hotel_listing') && CustomHelper::checkPermission('accommodations','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.additional_info') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.info_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.info_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodation_room') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_add') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_edit') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.inventory') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.seo_meta') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.seo_view') === 0 || $faq_type=='accommodations' || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.add_agent_price') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Accommodations</span></a>
					</li>
					@endif

					@if(CustomHelper::isAllowedModule('hotel_listing') && CustomHelper::checkPermission('all_masters','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_features') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.features_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.feature_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.feature_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_facilities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomtype_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_features') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomfeature_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomfeatured_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_feature_view') === 0|| strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_feature_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_type_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accoomodation_type_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_plan_includes') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.plan_include_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.plan_include_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.plan_include_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_master') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomview_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomview_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomView') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accommodations_categories') }}" ><i class="fa fa-circle text-aqua"></i> <span>Rooms and Accommodations</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif
			@endif

			@if(CustomHelper::checkPermission('teammanagements','view') || CustomHelper::checkPermission('media','view'))
			<?php
			$destination_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.team_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.media.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.price_markup') === 0) ? 'class="active"':'';
			?>
			<li <?php echo $destination_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-table" aria-hidden="true"></i> <span>Master Data</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					<!-- @if(CustomHelper::checkPermission('packages.lists','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_inclusion_lists') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.list_view') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.packages.package_inclusion_lists') }}" ><i class="fa fa-circle text-aqua"></i> <span>Package Inclusion List</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('packages.services','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.serviceLevel') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.service_view') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.packages.serviceLevel') }}" ><i class="fa fa-circle text-aqua"></i> <span>Package Service</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('packages.airlines','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.package_airlines') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.airline_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.air_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.packages.airline_view') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.packages.package_airlines') }}" ><i class="fa fa-circle text-aqua"></i> <span>Package Airlines</span></a>
					</li>
					@endif -->

			<!-- @if(CustomHelper::checkPermission('accommodations','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_facilities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.facility_view') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accommodations_facilities') }}" ><i class="fa fa-circle text-aqua"></i> <span>Accommodation Facilities</span></a>
			</li>
			@endif


			@if(CustomHelper::checkPermission('accommodations','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.accommodations_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.category_view') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accommodations_categories') }}" ><i class="fa fa-circle text-aqua"></i> <span>Accommodation Categories</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('accommodations','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_types') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_type_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomtype_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_type_view') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_types') }}" ><i class="fa fa-circle text-aqua"></i> <span>Room Types</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('accommodations','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_features') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomfeature_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.roomfeatured_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.accommodations.room_feature_view') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_features') }}" ><i class="fa fa-circle text-aqua"></i> <span>Room Features</span></a>
			</li>
			@endif -->

			@if(CustomHelper::checkPermission('teammanagements','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.team_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.category_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.category_edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.teammanagements.team_categories') }}" ><i class="fa fa-circle text-aqua"></i> <span>Team Categories</span></a>
			</li>
			@endif

			{{--@if(CustomHelper::checkPermission('all_masters','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries_master.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries_master.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.enquiries_master.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Enquiries Master</span></a>
			</li>
			@endif--}}

		<?php /*	@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('all_masters','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airport_master.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Airport Master</span></a>
			</li>
			@endif

			@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('all_masters','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.price_markup') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airport_master.price_markup') }}" ><i class="fa fa-circle text-aqua"></i> <span>Airline Price Markup Settings</span></a>
			</li>
			@endif*/ ?>

			@if(CustomHelper::checkPermission('media','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.media.index') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{route($ADMIN_ROUTE_NAME.'.media.index')}}"><i class="fa fa-circle text-aqua"></i> <span>Media Gallery</span></a>
			</li>
			@endif
		</ul>
	</li>
	@endif

	@if(CustomHelper::checkPermission('customer_review','view') || CustomHelper::checkPermission('testimonials','view'))
	<?php
	$testimonial_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.testimonials') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.customer_reviews') === 0 || $faq_type == 'testimonials')? 'class="active"':'';
	?>

	<li <?php echo $testimonial_active; ?> >
		<a  class="dropul subtab"><i class="fa fa-quote-left"></i> <span>Testimonials</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('customer_review','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.customer_reviews.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.customer_reviews.customerView') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.customer_reviews.customer_review_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.customer_reviews.customer_review_edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.customer_reviews.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Customer Review</span></a>
			</li>
			@endif
			@if(CustomHelper::checkPermission('testimonials','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.testimonials') === 0 || $faq_type == 'testimonials' ? ' class="active sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.testimonials.index') }}" > <i class="fa fa-circle text-aqua"></i><span> Testimonial</span></a>
			</li>
			@endif
		</ul>
	</li>
	@endif
	<!-- Add Register-User -->
	<?php
	$regisUser_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-users') === 0 )? 'class="active sub_active"':'';
	$regisAgent_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents') === 0 )? 'class="active sub_active"':'';
	?>
	@if(CustomHelper::isAllowedModule('customer'))
	@if(CustomHelper::checkPermission('users','view'))
	<li <?php echo $regisUser_active; ?>>
		<a href="{{ route($ADMIN_ROUTE_NAME.'.register-users.index') }}" ><i class="fa fa-users" aria-hidden="true"></i><span>Customers</span></a>
	</li>
	@endif
	@endif

	@if(CustomHelper::checkPermission('agents','view'))
	<?php
	$regisAgent_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.discount') === 0 )? 'class="active sub_active"':'';
	?>
	@if(CustomHelper::isAllowedModule('agentuser'))
	<li <?php echo $regisAgent_active; ?> >
		<a  class="dropul subtab"><i class="fa fa-user-secret"></i> <span>Agent</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('agents','view'))
			<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.wallet') === 0) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.register-agents.index') }}" ><i class="fa fa-circle text-aqua"></i><span> Agent List </span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('agents','view'))
			<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.groups') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.group_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.register-agents.group_edit') === 0) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.register-agents.groups') }}" ><i class="fa fa-circle text-aqua"></i><span> Agent Groups </span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('agents','view'))
			<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.discount.category') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.discount.addcategory') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.discount.editcategory') === 0) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.discount.category') }}" ><i class="fa fa-circle text-aqua"></i><span> Discount Category </span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('agents','view'))
			<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.discount.agent_groups') === 0) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.discount.agent_groups') }}" ><i class="fa fa-circle text-aqua"></i><span> Discount for Agent Groups </span></a>
			</li>
			@endif
		</ul>
	</li>
	@endif
	@endif

	<!-- Add Vendors -->
	<?php
	$user_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendors') === 0 )? 'class="active sub_active"':'';
	?>
	@if(CustomHelper::isAllowedModule('vendor'))
	@if(CustomHelper::checkPermission('vendor','view'))
	<li <?php echo $user_active; ?>>
		<a  class="dropul subtab"><i class="fa fa-briefcase" aria-hidden="true"></i> <span>Vendors</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('vendor','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendors.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendors.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.vendors.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.vendors.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Vendor List</span></a>
			</li>
			@endif
		</ul>
	</li>
	@endif
	@endif

	<?php if(config('custom.CAB_VERSION') == 2 && CustomHelper::isAllowedModule('cab') && CustomHelper::checkPermission('cabs','view')){ ?>
	<?php
	$cabs_master_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs_sightseeing') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.cities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') === 0 || $faq_type=='cabs_sightseeing')? 'class="active"':'';
	?>
	<li <?php echo $cabs_master_active; ?> >
		<a  class="dropul subtab"><i class="fas fa-car" aria-hidden="true"></i> <span>Cabs</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cabs.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cabs</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.cities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.cities_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.cities_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.cities_view') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cabs.cities') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab City</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.sightseeing') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.sightseeing_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.sightseeing_edit') === 0 || $faq_type=='cabs_sightseeing' ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cabs.sightseeing') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Sightseeing</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.inventory') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cabs.inventory') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Inventory</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cabs.price') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cabs.price') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Price</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') === 0) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') }}" ><i class="fa fa-circle text-aqua"></i><span>Cab Options</span></a>
			</li>
			@endif
		</ul>
	</li>
	<?php } else if(CustomHelper::isAllowedModule('cab') && CustomHelper::checkPermission('cabs','view')){ ?>
	<?php
	$cab_master_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_master') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route') === 0 || $faq_type=='cab_route'  ||  strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_cities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_addons') === 0)? 'class="active"':'';
	?>
	<li <?php echo $cab_master_active; ?> >
		<a  class="dropul subtab"><i class="fas fa-car" aria-hidden="true"></i> <span>Cabs</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_master.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_master.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_master.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_master.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cabs</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_cities.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_cities.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_cities.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_cities.view') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_cities.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab City</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.groups') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.groupadd') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.groupedit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_route.groups') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Route Group</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.add') === 0 || $faq_type=='cab_route' || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_route.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_route.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Route</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_master.inventory') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_master.inventory') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Inventory</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_master.price') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_master.price') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Price</span></a>
			</li>
			@endif
			<?php /*
			@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cab_addons') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.cab_addons.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Cab Addons</span></a>
			</li>
			@endif */ ?>

			<?php /*@if(CustomHelper::checkPermission('cabs','view'))
			<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') === 0) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') }}" ><i class="fa fa-circle text-aqua"></i><span>Cab Options</span></a>
			</li>
			@endif */ ?>
		</ul>
	</li>
	<?php } ?>

	@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view'))
	<?php
	$flight_master_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.FlightMarkup') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.FlightDiscount') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.FlightCommission') === 0 || (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline') === 0 && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.offline_inventory') !== 0 && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline_route') !== 0 ) )? 'class="active"':'';
	?>
	<li <?php echo $flight_master_active; ?> >
		<a  class="dropul subtab"><i class="fas fa-plane" aria-hidden="true"></i> <span>Flights</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.FlightMarkup') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airline.FlightMarkup') }}" ><i class="fa fa-circle text-aqua"></i> <span>Flight Markup</span></a>
			</li>

			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.FlightDiscount') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airline.FlightDiscount') }}" ><i class="fa fa-circle text-aqua"></i> <span>Flight Discount</span></a>
			</li>

			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.FlightCommission') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airline.FlightCommission') }}" ><i class="fa fa-circle text-aqua"></i> <span>Flight Commission</span></a>
			</li>
			@endif

			@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airport_master.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airport_master.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Airport Master</span></a>
			</li>
			@endif

			@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airline.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Airline Master</span></a>
			</li>
			@endif

			@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline.fare_rules') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airline.fare_rules') }}" ><i class="fa fa-circle text-aqua"></i> <span>Airline Offer Fare Rule</span></a>
			</li>
			@endif

			@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline_route.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline_route.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.airline_route.edit') === 0 ? ' class="sub_active"' : '' !!} style="display: none;">
				<a href="{{ route($ADMIN_ROUTE_NAME.'.airline_route.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Airline Routes</span></a>
			</li>
			@endif
		</ul>
	</li>
	@endif

	@if(CustomHelper::isAllowedModule('rental') && CustomHelper::checkPermission('rental','view'))
	<?php $bike_master_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_master') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.add_agent_price') === 0) ? 'class="active"':''; ?>
	<li <?php echo $bike_master_active; ?> >
		<a  class="dropul subtab"><i class="fas fa-motorcycle" aria-hidden="true"></i> <span>Rental</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('rental','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_master.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_master.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_master.edit') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.bike_master.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Bike</span></a>
			</li>
			
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.agent_price') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_cities.add_agent_price') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.bike_cities.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>City</span></a>
			</li>

			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_master.inventory') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.bike_master.inventory') }}" ><i class="fa fa-circle text-aqua"></i> <span>Inventory</span></a>
			</li>

			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.bike_master.price') === 0 ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.bike_master.price') }}" ><i class="fa fa-circle text-aqua"></i> <span>Price</span></a>
			</li>
			@endif
		</ul>
	</li>
	@endif


	@if($online_booking)
	<?php
	$coupon_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.coupons') === 0 )? 'class="active sub_active"':'';
	?>
	@if(CustomHelper::checkPermission('coupons','view'))
	<li <?php echo $coupon_active; ?>>
		<a href="{{ route($ADMIN_ROUTE_NAME.'.coupons.index') }}" ><i class="fa fa-gift" aria-hidden="true"></i><span>Coupons</span></a>
	</li>
	@endif
	@endif

	@if(CustomHelper::checkPermission('blogs','view'))
	<?php
	$blog_active = ( $type == 'blogs' && (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs') === 0 ))? 'class="active"':'';
	?>
	<li <?php echo $blog_active; ?> >
		<a  class="dropul subtab"><i class="fa fa-book" aria-hidden="true"></i> <span> Blogs</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
		<ul>
			@if(CustomHelper::checkPermission('blogs','view'))
			<li {!! ($type == 'blogs' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.categories_view') === 0 ) ? ' class="sub_active"' : '' !!}>
				<a href="{{ route($ADMIN_ROUTE_NAME.'.blogs_categories.index', ['type'=>'blogs']) }}" >
					<i class="fa fa-circle text-aqua"></i> <span>Blog Category</span></a>
				</li>
				@endif
				@if(CustomHelper::checkPermission('blogs','view'))
				<li {!! ($type == 'blogs' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.blog_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.seo_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.seo_meta') === 0) ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.blogs.index', ['type'=>'blogs']) }}" ><i class="fa fa-circle text-aqua"></i> <span>Blog List</span></a>
				</li>
				@endif
			</ul>
		</li>
		@endif

		<!-- Add News -->
		@if(CustomHelper::checkPermission('news','view'))
		<?php
		$news_active = ( $type == 'news' && (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs') === 0 ))? 'class="active"':'';
		?>

		<li <?php echo $news_active; ?> >
			<a  class="dropul subtab"><i class="fa fa-newspaper-o"></i> <span>News</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
			<ul>
				@if(CustomHelper::checkPermission('news','view'))
				<li {!! ($type == 'news' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs_categories.categories_view') === 0) ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.blogs_categories.index',['type'=>'news']) }}" ><i class="fa fa-circle text-aqua"></i> <span>News Category</span></a>
				</li>
				@endif

				@if(CustomHelper::checkPermission('news','view'))
				<li {!! ($type == 'news' && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.blog_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.seo_view') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.blogs.seo_meta') === 0) ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.blogs.index', ['type'=>'news']) }}" ><i class="fa fa-circle text-aqua"></i> <span>News List</span></a>
				</li>
				@endif
			</ul>
		</li>
		@endif
		<!-- Add News Closed -->

		<!-- Add Faqs -->
		@if(CustomHelper::checkPermission('faqs','view'))
		<?php
		$faq_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.faqs') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.faq_categories') === 0 )? 'class="active"':'';
		?>

		{{--<li <?php echo $faq_active; ?> >
			<a  class="dropul subtab"><i class="fa fa-list-ul" aria-hidden="true"></i> <span>Manage Faqs</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
			<ul>
				@if(CustomHelper::checkPermission('faqs','view'))
				<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.faqs') === 0 ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.faqs.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>FAQs</span></a>
				</li>
				@endif
				@if(CustomHelper::checkPermission('faqs','view'))
				<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.faq_categories.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.faq_categories.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.faq_categories.edit') === 0 ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.faq_categories.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>FAQs Categories list</span></a>
				</li>
				@endif
			</ul>
		</li>--}}
		@endif
		<!-- Add Faqs Closed -->

		@if(CustomHelper::checkPermission('cms','view') || CustomHelper::checkPermission('newsletter','view') || CustomHelper::checkPermission('teammanagements','view'))
		<?php
		$cms_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cms') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.newsletter') === 0 || $faq_type=='cms_pages' || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.view') === 0 ) ? 'class="active"':'';
		?>

		<li <?php echo $cms_active; ?> >
			<a  class="dropul subtab"><i class="fa fa-file-text" aria-hidden="true"></i> <span>CMS Pages</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
			<ul>
				@if(CustomHelper::checkPermission('cms','view'))
				<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cms') === 0 || $faq_type=='cms_pages' ? ' class="active sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.cms.index') }}" ><i class="fa fa-circle text-aqua"></i><span>CMS Pages</span></a>
				</li>
				@endif

				@if(CustomHelper::checkPermission('teammanagements','view'))
				<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.teammanagements.view') === 0 ? ' class="sub_active"' : '' !!}>
					<a href="{{ route($ADMIN_ROUTE_NAME.'.teammanagements.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Team Management</span></a>
				</li>
				@endif

				<!-- For Newletter Subscriber-->
				@if(CustomHelper::checkPermission('newsletter','view'))
				<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.newsletter') === 0 ? ' class="active sub_active"' : '' !!}>
					<a href="{{url('admin/newsletter')}}" ><i class="fa fa-circle text-aqua"></i><span> Newsletter</span></a>
				</li>
				@endif
			</ul>
		</li>
		@endif

	<?php /*
			@if(CustomHelper::checkPermission('banner_images','view'))
			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.managebanners') === 0 ? ' class="active"' : '' !!}>
				<a class="dropul subtab"><i class="fas fa-image"></i> <span> Banners Image Gallery</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('banner_images','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.managebanners.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.managebanners.edit') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.managebanners.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Banners List</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('banner_images','add'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.managebanners.add') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.managebanners.add') }}" ><i class="fa fa-circle text-aqua"></i> <span>Add Banner</span></a>
					</li>
					@endif

				</ul>
			</li>
			@endif

			 @if(CustomHelper::checkPermission('image_categories','view'))

			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.images.upload') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.image_categories') === 0 ? 'class="active"' : '' !!}>
				<a class="dropul subtab"><i class="fa fa-file-image-o"></i> <span>Image Category List</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('image_categories','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.image_categories.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.image_categories.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.image_categories.edit') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.image_categories.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Category List</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('image_categories','add'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.images.upload') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.images.upload',['tid'=>'0','tbl'=>'gallery']) }}" ><i class="fa fa-circle text-aqua"></i> <span>Gallery List</span></a>
					</li>
					@endif

				</ul>
			</li>
			@endif
			*/?>

			<!-- <li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.media') === 0 ? ' class="active"' : '' !!}>
				<a class="dropul subtab"><i class="fa fa-photo"></i> <span> Media</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.media.index') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.media.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Media</span></a>
					</li>
				</ul>
			</li> -->

			<!-- Country,state, city-->
			@if(CustomHelper::checkPermission('countries','view') || CustomHelper::checkPermission('states','view') || CustomHelper::checkPermission('cities','view'))

			<?php
			$country_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.countries') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.states') === 0  ||  strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cities') === 0 )? 'class="active"':'';
			?>

			<!-- <li <?php //echo $country_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-home" aria-hidden="true"></i> <span>Country, State, City</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>

					@if(CustomHelper::checkPermission('countries','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.countries') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.countries.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Manage Countries</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('states','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.states') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.states.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Manage States</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('cities','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.cities') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.cities.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Manage Cities</span></a>
					</li>
					@endif

				</ul>
			</li> -->
			@endif
			<!-- Country,state, city Closed-->

			@if(CustomHelper::checkPermission('widget','view'))
			<?php $widget_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.widget') === 0 )? 'class="active"':''; ?>
			
			<li <?php echo $widget_active; ?>>
				<a  class="dropul subtab"><i class="fa fa-list-ol" aria-hidden="true"></i> <span>Widget</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('widget','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.widget') === 0 ? ' class="sub_active"' : '' !!}>

						<a href="{{ route($ADMIN_ROUTE_NAME.'.widget.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Widget</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif 

			@if(CustomHelper::checkPermission('forms','view'))
			<li {!! strpos(Route::currentRouteName(), 'admin.forms') === 0 ? ' class="active sub_active"' : '' !!}>
				<a href="{{ route('admin.forms.index') }}" ><i class="fa fa-wpforms"></i> <span>Forms</span></a>
			</li>
			@endif

			@if(CustomHelper::checkPermission('banners','view'))

			<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.banners') === 0 ? ' class="active"' : '' !!}>
				<a class="dropul subtab"><i class="fa fa-file-image-o"></i> <span>Banners</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('banners','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.banners.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.banners.edit') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.banners.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Banners List</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('banners','add'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.banners.add') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.banners.add') }}" ><i class="fa fa-circle text-aqua"></i> <span>Add Banner</span></a>
					</li>
					@endif

				</ul>
			</li>
			@endif


			<!-- Add Manage Footer Menu -->
			@if(CustomHelper::checkPermission('downloads','view') || CustomHelper::checkPermission('menus','view'))

			<?php
			$footerMenu_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.menus') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.others') === 0 )? 'class="active"':'';
			?>

			<li <?php echo $footerMenu_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-bars" aria-hidden="true"></i> <span>Manage Menu</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('menus','view'))
					<li {!! strpos(Route::currentRouteName(), 'admin.menus') === 0 ? ' class="active sub_active"' : '' !!}>
						<a href="{{ route('admin.menus.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Manage Header/Footer Menu</span></a>
					</li>
					@endif
				<?php /*
					@if(CustomHelper::checkPermission('downloads','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.edit') === 0  || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.view') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.downloads.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Manage Footer Menu</span></a>
					</li>
					@endif

					 @if(CustomHelper::checkPermission('downloads','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.others') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.other_add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.other_edit') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.downloads.other_view') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.downloads.others') }}" ><i class="fa fa-circle text-aqua"></i> <span>Others</span></a>
					</li>
					@endif */ ?>
				</ul>
			</li>
			@endif
			<!-- Add Manage Footer Menu Closed -->

			<!-- Add User -->
			<?php
			$user_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.users') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.roles') === 0 )? 'class="active sub_active"':'';
			?>
			@if(CustomHelper::checkPermission('staff','view'))
			<li <?php echo $user_active; ?>>
				<a  class="dropul subtab"><i class="fa fa-user" aria-hidden="true"></i> <span>Users</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('staff','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.users.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.users.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.users.edit') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.users.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Manage Admin Users</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('roles','view'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.roles') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.roles.index') }}" ><i class="fa fa-circle text-aqua"></i><span>Role and Permissions</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if(CustomHelper::checkPermission('module_config','view'))
			<?php
			$tags_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.module_config') === 0 || $faq_type=="seo_meta_tags" )? 'class="active"':'';
			?>
			<li <?php echo $tags_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-list-ol" aria-hidden="true"></i> <span>Module Configuration</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('module_config','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.module_config') === 0 || $faq_type=="seo_meta_tags" ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.module_config.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Modules</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif


			@if(CustomHelper::checkPermission('email_templates','view'))
			<?php
			$template_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.email-templates') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.email_header_footer') === 0)? 'class="active"':'';
			?>
			<li <?php echo $template_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-envelope" aria-hidden="true"></i> <span>Email</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>

					@if(CustomHelper::checkPermission('email_templates','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.email-templates') === 0 ? ' class="sub_active"' : '' !!}>

						<a href="{{ route($ADMIN_ROUTE_NAME.'.email-templates.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Email Templates</span></a>
					</li>
					@endif

					@if(CustomHelper::checkPermission('email_templates','view'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.email_header_footer') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.email_header_footer') }}" ><i class="fa fa-circle text-aqua"></i><span>Email Header Footer</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif


			@if(CustomHelper::checkPermission('sms_templates','view'))
			<?php
			$sms_template_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.sms-templates') === 0 )? 'class="active"':'';
			?>
			<li <?php echo $sms_template_active; ?> >
				<a  class="dropul subtab"><i class="fas fa-sms" aria-hidden="true"></i> <span>SMS</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>

					@if(CustomHelper::checkPermission('sms_templates','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.sms-templates') === 0 ? ' class="sub_active"' : '' !!}>

						<a href="{{ route($ADMIN_ROUTE_NAME.'.sms-templates.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>SMS Templates</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			<!-- Add Manage Activity Log -->
			<?php /*
			@if(CustomHelper::checkPermission('activities','view'))
			<?php
			$activityLog_active = (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activities') === 0 )? 'class="active"':'';

			<li <?php echo $activityLog_active;  >
				<a  class="dropul subtab"><i class="fa fa-history"></i> <span>Activity Logs</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('activities','view'))
					<li {!! strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activities') === 0 ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.activities.index') }}" ><i class="fa fa-circle text-aqua"></i> <span>Admin Users Activity logs</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif */ ?>

			@if(CustomHelper::checkPermission('settings','view'))
			<?php
			$settings_active = (( (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.payment-gateways') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.url_redirection.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.url_redirection.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.url_redirection.edit') === 0) && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.email_header_footer') !== 0 && strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.display_cab_tabs') !== 0 ))? 'class="active"':'';
			?>
			<li <?php echo $settings_active; ?> >
				<a  class="dropul subtab"><i class="fa fa-cog"></i> <span> Settings</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.index') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.index') }}" ><i class="fa fa-circle text-aqua"></i><span>Settings</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.general') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.general') }}" ><i class="fa fa-circle text-aqua"></i><span>General Settings</span></a>
					</li>
					<?php /*<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.comapany_info') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.comapany_info') }}" ><i class="fa fa-circle text-aqua"></i><span>Company Information</span></a>
					</li> */ ?>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.sms_setting') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.sms_setting') }}" ><i class="fa fa-circle text-aqua"></i><span>SMS Setting</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.smtpSetting') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.smtpSetting') }}" ><i class="fa fa-circle text-aqua"></i><span>Email Settings</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.google_maps_api') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.google_maps_api') }}" ><i class="fa fa-circle text-aqua"></i><span>Google Maps API</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.google_recaptcha') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.google_recaptcha') }}" ><i class="fa fa-circle text-aqua"></i><span>Google Recaptcha</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.social_configuration') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.social_configuration') }}" ><i class="fa fa-circle text-aqua"></i><span>Social Login</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.social_plateform') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.social_plateform') }}" ><i class="fa fa-circle text-aqua"></i><span>Social Platform</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.social_sharing') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.social_sharing') }}" ><i class="fa fa-circle text-aqua"></i><span>Social Sharing</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.analytics_tool') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.analytics_tool') }}" ><i class="fa fa-circle text-aqua"></i><span>Header & Footer</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.images') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.images') }}" ><i class="fa fa-circle text-aqua"></i><span>Images Settings</span></a>
					</li>

					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.sitemap') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.sitemap') }}" ><i class="fa fa-circle text-aqua"></i><span>Sitemap Generate</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.cookies_consent') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.cookies_consent') }}" ><i class="fa fa-circle text-aqua"></i><span> Cookies Consent</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.currency_setting') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.currency_setting') }}" ><i class="fa fa-circle text-aqua"></i><span> Currency Setting</span></a>
					</li>

					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.robot_txt') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.robot_txt') }}" ><i class="fa fa-circle text-aqua"></i><span>Robot txt</span></a>
					</li>

					@if(CustomHelper::checkPermission('seo_url_redirection','list'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.url_redirection.index') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.url_redirection.add') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.url_redirection.edit') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.url_redirection.index') }}" ><i class="fa fa-circle text-aqua"></i><span>SEO URL Redirection</span></a>
					</li>
					@endif

					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.payment-gateways.index') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.payment-gateways.index') }}" ><i class="fa fa-circle text-aqua"></i><span>Payment Gateways</span></a>
					</li>
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.payment-gateways.add') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.payment-gateways.add') }}" ><i class="fa fa-circle text-aqua"></i><span>Payment Gateways Configuration</span></a>
					</li>

					@if(CustomHelper::isAllowedModule('flight'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.settings.flight_apis') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.flight_apis') }}" ><i class="fa fa-circle text-aqua"></i><span>Flight API Configuration</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif

			@if(CustomHelper::checkPermission('activities','view'))
			<?php
			$activity_active = ((strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activitylogs') === 0 || strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.login_history') === 0 ))? 'class="active"':'';
			?>

			<li <?php echo $activity_active; ?> >
				<a  class="dropul subtab"><i class="fas fa-history"></i> <span> Activity Logs</span></a><i aria-hidden="true" class="fa fa-angle-left dropul"></i>
				<ul>
					@if(CustomHelper::checkPermission('activities','view'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.activitylogs.index') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index') }}" ><span>Backend User Activity Logs</span></a>
					</li>

					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.login_history.index') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.login_history.index') }}" ><span>Backend User Login History</span></a>
					</li>
					@endif

					@if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('activities','view'))
					<li {!! (strpos(Route::currentRouteName(), $ADMIN_ROUTE_NAME.'.apilogs.index') === 0) ? ' class="sub_active"' : '' !!}>
						<a href="{{ route($ADMIN_ROUTE_NAME.'.apilogs.index') }}" ><span>API Logs</span></a>
					</li>
					@endif
				</ul>
			</li>
			@endif


		</ul>
	</div>
</div>
