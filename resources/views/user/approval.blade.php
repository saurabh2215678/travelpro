@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .crop_change { padding-bottom:35px; }
    .user_profile_inner .right_info .btn2{font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
</style>
@endslot

<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Your application for agent is under review. </h1>
                        </div>
                        <p class="para-lg2">After being approved, you will receive an email during the next 5-10 business days.</p>
                    </div>                 
                </div>
                </div>               
        </div>
    </div>
</section>

@slot('bottomBlock')
@endslot

@endcomponent


