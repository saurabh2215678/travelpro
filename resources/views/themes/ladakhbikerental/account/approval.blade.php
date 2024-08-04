@component(config('custom.theme').'.layouts.main')
@slot('title')
Agent Approval - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<style type="text/css">
.theme_footer:before {
    z-index: -1;
}
</style>
@endslot

<section>
    <div class="container">
        <div class="user_profile_login login_sec">
            <div class="not_found_page">
                <div class="text_center">
                    <div class="theme-title">
                        <div class="text-2xl">Agent Approval </div>
                    </div>
                </div>

                <p>Agent not approoved!!</p>
                <a class="btn" href="{{url('/')}}">Go To Home</a>
            </div>
        </div>
    </div>
</section>

@slot('bottomBlock')
@endslot

@endcomponent