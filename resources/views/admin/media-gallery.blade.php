@component('admin.layouts.main')

@slot('title')
{{ $title??'Media Gallery' }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
    .fancybox-slide--iframe .fancybox-content { height:100% !important;  }
</style>
@endslot
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
<div class="data-space">
    <div class="row">
        <div class="col-md-12">
            <h2>{{$heading??'Media Gallery'}}</h2>
            <div class="bgcolor">
                @include('snippets.errors')
                @include('snippets.flash')

                <div class="col-lg-2" style="display: none;">
                    <span class="btn btn-success media_box" data-fancybox data-type="iframe" data-fancybox-type="iframe" href="javascript:;" data-src="{{ route($ADMIN_ROUTE_NAME.'.media.pop',['action'=>'dashboard']) }}" data-preload="false" role="dialog"> Select From Gallery </span>
                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

@slot('bottomBlock')
<script type="text/javascript">
// Media Library Box
$(document).ready(function() {
    $('span.media_box').fancybox({
        type: "iframe",
        beforeClose: function(){
            return false;
        },
    });
    setTimeout(function(){
        $('span.media_box').trigger('click');
    },100);
    setTimeout(function(){
        $('.fancybox-toolbar').hide();
    },500);
});
</script>
@endslot
@endcomponent