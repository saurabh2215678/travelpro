@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <?php
    $routeName = CustomHelper::getAdminRouteName();
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    if(empty($back_url)){
        $back_url = $routeName.'/roles';
    }
    $name = (isset($role->name))?$role->name:'';
    $id = (isset($role->id))?$role->id:0;
    $userPermission = (isset($role->permissions))?$role->permissions:'';
    $permissionData = '';
    if(!empty($userPermission)){
        $permissionData = json_decode($role->permissions,true);
    }

    ?>
    <div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
        <div class="add_button">
        <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

        <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a><?php } ?>
    </div>
</div>
<div class="centersec">
        <div class="bgcolor">
            @include('snippets.errors')
            @include('snippets.flash')

            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12 col-sm-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}" style="padding: 5px 15px 0;">
                            <label class="control-label required">Role Name:</label>

                            <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    <div class="clearfix"></div>
               <?php /*  <div class="col-sm-12 col-sm-6">
                    <input type="hidden" name="back_url" value="{{ $back_url }}" >
                    <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>

                    <a href="{{ url($back_url) }}" class="btn btn-lg btn-primary" title="Click here to cancel">Cancel</a>
                </div> */ ?>
                <div class="bgcolor-full">
                    <div class="col-sm-12">
                    <h2 style="margin-bottom: 0">Permission</h2>
                    <?php
            //pr($permissions);
                        $permission_params = [];
                        $permission_params['modules'] = $modules;
                        $permission_params['permissionData'] = $permissionData;
                        ?>
                        @include('admin.admins._permission', $permission_params)
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                    <div class="form-group" style="padding: 5px 15px 0;">
                        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                        <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i>Submit</button>
                        <a href="{{ url($back_url) }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                    </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent