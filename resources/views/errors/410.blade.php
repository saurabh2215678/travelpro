@component(config('custom.theme').'.layouts.main')
@slot('title')
    Page Gone - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot

<div class="not_found_page">
<h3>410 ! Page Gone</h3>
<p>This page has expired or the page dosen’t exist anymore</p>
<a class="btn" href="{{url('/')}}">Go To Home</a>
</div>
@slot('bottomBlock')

@endslot

@endcomponent