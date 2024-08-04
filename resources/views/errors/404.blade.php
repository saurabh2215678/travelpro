@component(config('custom.theme').'.layouts.main')
@slot('title')
    Page Not Found(404) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot

<div class="not_found_page">
<h3>404 not Found</h3>
<p>The page you are looking for does not exist</p>
<a class="btn" href="{{url('/')}}">Go To Home</a>
</div>
@slot('bottomBlock')


@endslot

@endcomponent


