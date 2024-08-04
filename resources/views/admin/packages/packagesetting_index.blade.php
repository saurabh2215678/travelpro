@component('admin.layouts.main')

    @slot('title')
        Admin - Settings (Package) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    // $BackUrl = CustomHelper::BackUrl();
    // $routeName = CustomHelper::getAdminRouteName();
    ?>

<?php
$active_menu = "packages".$package_id.'/packagesetting';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif

    <div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    </div>
    <div class="centersec">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="ajax_msg"></div>
                <div class="table-responsive">
                    @if($package_settings)
                    @foreach($package_settings as $ps)
                    <p>
                        <input type="checkbox" name="package_settings[]" value="{{$ps->id}}" {{(!empty($settings_ids) && in_array($ps->id,$settings_ids))?'checked':''}} > {{$ps->name}}
                    </p>
                    @endforeach
                    @endif
                    @if(CustomHelper::checkPermission('packages','edit'))
                    <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
                    @endif
                </div>
            </form>
        </div>

    </div>
    </div>
    </div>

    @slot('bottomBlock')

    
    @endslot

@endcomponent