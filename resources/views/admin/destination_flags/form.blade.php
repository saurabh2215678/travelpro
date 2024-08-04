@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
    <style>
        .bootstrap-tagsinput { display: block;}
        .slugEdit {
            position: absolute;
            right: 22px;
            top: 26px;
            font-size: 15px;
            opacity: .7;
    </style>
    @endslot


     <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/destinations';
    }

    //pr($blog->toArray());
    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($destination_query->id))?$destination_query->id:'';
    $destination_flag = (isset($destination_query->name))?$destination_query->name:'';
    $slug = (isset($destination_query->slug))?$destination_query->slug:'';
    $status = (isset($destination_query->status))?$destination_query->status:1;
    $page_title = (isset($destination_query->page_title))?$destination_query->page_title:'';
    $page_brief = (isset($destination_query->page_brief))?$destination_query->page_brief:'';
    $page_description = (isset($destination_query->page_description))?$destination_query->page_description:'';
    
    ?>

    <div class="centersec">
       <?php
       $active_menu = "destinations.flags";
       ?>
       @include('admin.includes.destinationmenu')
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
                            <label for="name" class="control-label required">Destination Flag:</label>

                            <input type="text" id="name" class="form-control" name="name" value="{{ old('destination_flag', $destination_flag) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    <?php
                if(!empty($destination_query->id)){
                    ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditDSlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php }?>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('page_title') ? ' has-error' : '' }}">
                            <label class="control-label ">Page Title</label>
                            <input type="text" name="page_title" class="form-control" value="{{ old('page_title', $page_title) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'page_title'])
                        </div>
                    </div>

                    <div class="form-group col-md-12{{$errors->has('page_brief')?' has-error':''}}">
                    <label for="page_brief" class="control-label">Page Brief </label>

                    <textarea name="page_brief" id="page_brief" class="form-control" ><?php echo old('page_brief', $page_brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'page_brief'])
                </div>


                    <div class="form-group  col-md-12{{ $errors->has('page_description') ? ' has-error' : '' }}">
                        <label for="page_description" class="control-label ">Page Description </label>

                        <textarea name="page_description" id="page_description" class="form-control ckeditor" ><?php echo old('page_description', $page_description); ?></textarea>    

                        @include('snippets.errors_first', ['param' => 'page_description'])
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
            <button type="submit" class="btn btn-success" title="Create this new destination type"><i class="fa fa-save"></i> Submit</button>
               
            </form>
        </div>
        </div>
    <div class="clearfix"></div>
        
@slot('bottomBlock')
<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript">
    var page_description = document.getElementById('page_description');
    CKEDITOR.replace(page_description);        
</script>
  <script type="text/javascript">
    var slug = '{{$slug}}';
    if(slug != ''){
      $('#slug').attr('readonly',true);
    }

    $("#EditDSlug").click(function(){
        $('#slug').attr('readonly',false);
    });
    </script>
@endslot

@endcomponent