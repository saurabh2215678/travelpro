<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name') . ' - Admin Panel' }}</title>

	<?php
	$websiteSettingsArr = CustomHelper::getSettings(["FAVICON_LOGO"]);
    $SCRIPTS_VERSION = $websiteSettingsArr['SCRIPTS_VERSION']??'1.0';

	$storage = Storage::disk('public');
    $path = "settings/";
    $favIconSrc = asset(config('custom.assets').'/img/fav-icon.png');
    if(!empty($websiteSettingsArr['FAVICON_LOGO'])){
      $logo = $websiteSettingsArr['FAVICON_LOGO'];
      if($storage->exists($path.$logo)){
        $favIconSrc = asset('/storage/'.$path.$logo);
      }
    }
    $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
	?>
	<link rel="icon" href="{{$favIconSrc}}" type="image/x-icon">
    <!-- <link rel="stylesheet" href="{{asset('assets')}}/css/chat.css"> -->
    
    <link href="{{asset('/')}}css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{url('')}}/css/site.css?v={{$SCRIPTS_VERSION}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{url('')}}/css/jquery.mCustomScrollbar.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900" rel="stylesheet" /> 
	 <!-- Include Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />  
	<style>	
		@-webkit-keyframes rotating {
		from{
			-webkit-transform: rotate(0deg);
		}
		to{
			-webkit-transform: rotate(360deg);
		}
		}

		.fetching-balance {
		-webkit-animation: rotating 2s linear infinite;
		animation: rotating 2s linear infinite;
		}
	</style>
    <!--Header block-->
    {{ $headerBlock ?? '' }}
</head>

<body>
<div class="fullwidth mainwrapper">
<div class="container">
    <!-- Site Top -->
    
    <!-- Menu -->
    @include('admin.layouts.nav')
    
	<div class="rightsec">
	<!-- Header -->
	<div class="headersec">
	<!-- <div class="logo">
		 	<img src="{{asset('/images/logo.png')}}" alt="Admin"  />
		 </div> -->
     	<div class="menuicon menu_on"><span></span></div>
		 @include('admin.layouts.top')
		
     </div>
    <!-- Body -->

    {{ $slot }}

    
    <div class="copyright text-center">
	&copy;  <?php echo date('Y'); ?> {{ CustomHelper::WebsiteSettings('SYSTEM_TITLE') ?? 'Admin Panal'}} - All Right Reserved 
	</div>
    </div>
</div>
</div>

<!-- Placed at the end of the document so the pages load faster -->

<script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" referrerpolicy="no-referrer"></script>
  <!-- Include Date Range Picker -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"  />

	<style>
		.leftsec1{height: calc(100% - 70px); position: absolute; top: 0; margin-top: 70px;}
	</style>
	<script>
		(function($){
			$(document).click(function(){
				$("body").removeClass("nav_active");
			});
			$(".menu_on,.leftsec1").click(function(e){
				e.stopPropagation();
				$("body").addClass("nav_active");
			});
		})(jQuery);
	</script>
	<script type="text/javascript">
        // ========== daterangepicker ============
        $('.daterange').daterangepicker({
            locale: {
                    format: 'DD/MM/YYYY'
                }
        });

        $('input[name="bulkdaterange"]').daterangepicker(
            {
            locale: {
                    format: 'DD/MM/YYYY'
                }
        }
        );

        // ========== daterangepicker End============

    </script>
<!--Bottom block-->
{{ $bottomBlock ?? '' }}
	
<script>
	$('.dropul').click(function(){
		$(".dropul").parent('li').not($(this).parent('li')).removeClass( "active" );
		// $(".dropul").parent('li').not($(this).parent('li')).find("ul").slideUp(300);
		$(this).parent('li').toggleClass( "active" );
		//$(this).parent() .toggleClass( "active" );
		 $(this).parent().find("ul").slideToggle(300);
		//$(this).siblings("ul").slideToggle(300);
	} );
	if($(".adminleft > li").hasClass("active")){
		$(".adminleft > li.active").find("ul").slideDown(300);
	}
	$('.child_dropul').click(function(){
		$(".child_dropul").siblings('ul').not($(this).siblings('ul')).slideToggle();

		if($(this).find('i').hasClass("fa-angle-right")){
			$(this).find('i').removeClass("fa-angle-right");
			$(this).find('i').addClass("fa-angle-down");
		}
		else{
			$(this).find('i').addClass("fa-angle-right");
			$(this).find('i').removeClass("fa-angle-down");
		}
		
		$(this).siblings('ul').toggle();
		//$(this).parent() .toggleClass( "active" );
	} );


	/*$('.main-nav a').each(function(){
		var windowLocation = window.location.href;
		if($(this).attr('href') == windowLocation){
			$(this).closest('ul').show();
			console.log($(this).closest('ul').closest('li'));
			$(this).closest('ul').parent('li').addClass('active');
			$(this).closest('ul').parent('li').closest('li').addClass('active');
			$(this).closest('ul').parent('li').closest('ul').show();
			$(this).closest('ul').parent('li').siblings('li').removeClass('active');
			$(this).closest('ul').parent('li').siblings('li').find('ul').hide();
		}
	});*/
	$('.hidesidebar').click(function(){
		$(this).toggleClass( "active" );
		$('.leftsec').toggleClass( "active" );
		$('.rightsec').toggleClass( "active" );
	} );

	$(document).ready(function() {
		$(document).on('click','.flightBalance .rotate',function(e) {
			$('.rotate').addClass('fetching-balance');
			$('.flightBalance').addClass('ftch');
			$('.rotate').hide();
			$('.rotateing-icon').show();

			e.preventDefault();
			e.stopPropagation();
			var _token = '{{ csrf_token() }}';
			$.ajax({
				url: "{{ route($ADMIN_ROUTE_NAME.'.flightApiBalance') }}",
				type: "POST",
				data: {},
				dataType:"JSON",
				headers:{'X-CSRF-TOKEN': _token},
				cache: false,
				async: false,
				beforeSend:function(){
					// $(".flightBalanceHtml").html('Fetching balance...');
					
				},
				success: function(resp){
					if(resp.success) {
						$(".flightBalanceHtml").html(resp.walletBalance);
					} else if(resp.message) {
						alert(resp.message);
					} else {
						alert('Something went wrong, please try again.');
					}
					setTimeout(() => {
						$('.rotate').removeClass('fetching-balance');
						$('.flightBalance').removeClass('ftch');
						$('.rotate').show();
						$('.rotateing-icon').hide();
					}, 1000);
				}
			});
		});
	});



</script>
</body>
</html>