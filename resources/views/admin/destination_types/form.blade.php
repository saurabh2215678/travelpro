@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >

    <link rel="stylesheet" type="text/css" href="{{ url('public/datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >

    @endslot


     <?php
    $BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($BackUrl)){
        $BackUrl = $routeName.'/destination_types';
    }
    $id = (isset($destination_type->id))?$destination_type->id:'';
    $name = (isset($destination_type->name))?$destination_type->name:'';
    $status = (isset($destination_type->status))?$destination_type->status:1; 
    $storage = Storage::disk('public');
     
     // echo $subject_name; die;

    ?>
<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            
            <a href="{{url('admin/destinations/types')}}" class="btn_admin" style='float: right;'><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
 Back</a><?php } ?>
    </div>
    </div>

    <div class="centersec">

        <div class="bgcolor p10">

            

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" >
                {{ csrf_field() }}

                <!-- Category fields added here  --> 
                 <div class="row">   
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                            <label class="control-label">Status:</label>
                            &nbsp;&nbsp;
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <p></p>
                                    <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                                    <button type="submit" class="btn btn-success" title="Create this new destination type"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>


@slot('bottomBlock')




@endslot

@endcomponent