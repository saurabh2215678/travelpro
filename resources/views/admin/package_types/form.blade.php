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
    // $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($package_query->id))?$package_query->id:'';
    $package_type = (isset($package_query->package_type))?$package_query->package_type:'';
    $slug = (isset($package_query->slug))?$package_query->slug:'';
    $status = (isset($package_query->status))?$package_query->status:1;
    //$identifier = (isset($package_query->identifier))?$package_query->identifier:'';
    
    ?>

    <div class="centersec">
       <?php
       $active_menu = "packages.types";
       ?>
       @include('admin.includes.packagemenu')
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
                    <?php
                    if(!empty($package_query->id)){
                        ?>
                        <div style="display: none;" class="form-group  col-md-4{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="slug" class="control-label">Slug:</label>

                            <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" readonly />
                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('package_type') ? ' has-error' : '' }}">
                            <label for="package_type" class="control-label required">{{($segment == 'activity')?'Activity':'Package'}} Type:</label>

                            <input type="text" id="package_type" class="form-control" name="package_type" value="{{ old('package_type', $package_type) }}" />

                            @include('snippets.errors_first', ['param' => 'package_type'])
                        </div>
                    </div>

                    <div style="display:none;" class="col-md-4">
                        <div class="form-group{{ $errors->has('identifier') ? ' has-error' : '' }}">
                            <label for="identifier" class="control-label">{{($segment == 'activity')?'Activity':'Package'}} Type Identifier :</label>
                            <select class="form-control" name="identifier" id="identifier">
                             <?php //<option value="">--Select--</option> ?>
                             <option value="package" {{($identifier=='package')?'selected':''}}>Package</option>
                             <option value="activity" {{($identifier=='activity')?'selected':''}}>Activity</option>
                         </select>
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
            <button type="submit" class="btn btn-success" title="Create this new {{($segment == 'activity')?'Activity':'Packages'}} type"><i class="fa fa-save"></i> Submit</button>
               
            </form>
        </div>
        </div>
    <div class="clearfix"></div>    
@slot('bottomBlock')
@endslot
@endcomponent