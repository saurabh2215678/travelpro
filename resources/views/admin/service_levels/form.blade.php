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

    $id = (isset($service_query->id))?$service_query->id:0;
    $name = (isset($service_query->name))?$service_query->name:'';
    $status = (isset($service_query->status))?$service_query->status:1;
    
    ?>
   
    <div class="centersec">
     <?php
     $active_menu = "packages.services";
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
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Service Level Type:</label>

                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <?php
                            $fixedservices = config('custom.fixed_service_level_arr');
                            if(!in_array($id,$fixedservices)){?>
                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                                <label class="control-label">Status:</label>
                                &nbsp;&nbsp;
                                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                &nbsp;
                                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                            <?php }else{
                                echo "Active";
                            }
                            ?>
            </div>   
            <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
            <button type="submit" class="btn btn-success" title="Create this new service level type"><i class="fa fa-save"></i> Submit</button>
               
            </form>
        </div>
        </div>
    <div class="clearfix"></div>
        
@slot('bottomBlock')

@endslot

@endcomponent