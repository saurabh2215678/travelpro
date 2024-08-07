  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>{{ $title ?? ''}}</title>
      <?php if(isset($meta_description) && $meta_description != '') { ?>
        <meta name="description" content="{{ (isset($meta_description)) ? $meta_description:''}}">
      <?php } ?>

      <?php if(isset($meta_keyword) && $meta_keyword != '') { ?>
        <meta name="keywords" content="{{(isset($meta_keyword))?$meta_keyword:''}}"/>
      <?php } ?>
      <?php $websiteSettingsArr=CustomHelper::getSettings(['HTML_HEAD_TOP','HTML_BODY_TOP','HTML_HEAD_BOTTOM','HTML_BODY_BOTTOM','FAVICON_LOGO','SCRIPTS_VERSION']);
      $HTML_HEAD_TOP = $websiteSettingsArr['HTML_HEAD_TOP']??'';
      $SCRIPTS_VERSION = $websiteSettingsArr['SCRIPTS_VERSION']??'1.0';
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

      if(strpos($actual_link , "blog?page=") !== false){
          $actual_link = url('blog');
      }


      $storage = Storage::disk('public');
      $path = "settings/";
      $favIconSrc = asset(config('custom.assets').'/img/fav-icon.png');
      if(!empty($websiteSettingsArr['FAVICON_LOGO'])){
        $logo = $websiteSettingsArr['FAVICON_LOGO'];
        if($storage->exists($path.$logo)){
          $favIconSrc = asset('/storage/'.$path.$logo);
        }
      }
      ?>    
      <link rel="canonical" href="<?php echo $actual_link; ?>"/>
      <link rel="icon" href="{{$favIconSrc}}" type="image/x-icon">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500&display=swap" rel="stylesheet">
      <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
      <!-- <link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/output.css') }}"> -->
      <link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/website.css') }}?v={{$SCRIPTS_VERSION}}">
      <!-- <link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/output_new.css') }}"> -->
      <link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/media.css') }}?v={{$SCRIPTS_VERSION}}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
      
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    
  {!!(isset($websiteSettingsArr['HTML_HEAD_TOP']))?$websiteSettingsArr['HTML_HEAD_TOP']:''!!}

      <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


  <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
        
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>
        
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" />

  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/flick/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.1/jquery-ui.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="{{ asset(config('custom.assets').'/js/monthpicker.js') }}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js" ></script> -->
    {{ $headerBlock ?? '' }}

    {!!(isset($websiteSettingsArr['HTML_HEAD_BOTTOM']))?$websiteSettingsArr['HTML_HEAD_BOTTOM']:''!!}
    </head>
    <body class="home {{ $bodyClass ?? '' }}">

  {!!(isset($websiteSettingsArr['HTML_BODY_TOP']))?$websiteSettingsArr['HTML_BODY_TOP']:''!!}
    @include(config('custom.theme').'.layouts.header')

    {{ $slot }}
    

    @include(config('custom.theme').'.layouts.footer')

    {{ $bottomBlock ?? '' }}

  {!!(isset($websiteSettingsArr['HTML_BODY_BOTTOM']))?$websiteSettingsArr['HTML_BODY_BOTTOM']:''!!}

  </body>
  </html>