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

    $id = (isset($user->id))?$user->id:'';
    $name = (isset($user->name))?$user->name:'';
    $description = (isset($user->description))?$user->description:'';
    $status = (isset($user->status))?$user->status:1;
    
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
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Group Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Group Description:</label>
                            <textarea id="address" class="form-control" name="description">{{ old('description', $description) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'description'])
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