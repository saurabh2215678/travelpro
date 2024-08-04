@component('admin.layouts.main')
    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    @slot('headerBlock')
    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
    @endslot
    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();
    if(empty($back_url)){
        $back_url = $routeName.'/packages';
    }
    //pr($blog->toArray());
    $routeName = CustomHelper::getAdminRouteName();
    $id = (isset($user->id))?$user->id:'';
    $name = (isset($user->name))?$user->name:'';
    $status = (isset($user->status))?$user->status:1;
    $module_name = (isset($user->module_name))?$user->module_name:'';
    $seo_module_config_arr = config('custom.seo_module_config_arr');
    // prd($modules);
    ?>
    <div class="centersec">
        <div class="top_title_admin tab-title">
            <div class="title">
                <h2>{{ $page_heading }}</h2>
            </div>
            <div class="add_button">
                <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

                <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                   Back</a><?php } ?>
               </div>
           </div>
        <div class="bgcolor p10">
            @include('snippets.errors')
            @include('snippets.flash')
        <div class="ajax_msg"></div>
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                        <label class="required" >Module Name:</label><br/>
                            <select class="form-control select2" name="module_name" id="module">
                             <option value="">Select</option>
                            <?php foreach ($modules as $key => $module) { ?>                   
                                <option value="{{$module->identifier}}" {{($module->identifier == $module_name)?'selected':''}}>{{$seo_module_config_arr[$module->identifier]}}</option>
                            <?php } ?>
                            </select> 
                            @include('snippets.errors_first', ['param' => 'module_name'])                          
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required"> Discount Category Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                        <label class="control-label">Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                        &nbsp;
                        Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                        @include('snippets.errors_first', ['param' => 'status'])
                    </div>
                </div>   
                <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                <button type="submit" class="btn btn-success" title="Create this new Group name"><i class="fa fa-save"></i> Submit</button>       
            </form>
        </div>
        </div>
    <div class="clearfix"></div>
        
@slot('bottomBlock')

@endslot

@endcomponent