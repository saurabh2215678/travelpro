@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }}(Package) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endslot

<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
if(empty($back_url)){
    $back_url = $routeName.'packages'.$package_id.'/additional-info';
}

$info_title = (isset($package_info->title))?$package_info->title:'';
$brief = (isset($package_info->brief))?$package_info->brief:'';
$description = (isset($package_info->description))?$package_info->description:'';
$sort_order = (isset($package_info->sort_order))?$package_info->sort_order:0;
$status = (isset($package_info->status))?$package_info->status:'';
?>

<?php
$active_menu = "packages".$package_id.'/additional-info';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif

    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
    <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
    Back</a><?php } ?>
    </div>
    </div>

   <div class="centersec">
    <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
			<div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="form-group col-md-6{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Title:</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title',$info_title) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label for="brief" class="control-label">Brief:</label>

                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

				<div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                	<label for="description" class="control-label required">Description:</label>

                	<textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    <a href="<?php echo url($routeName.'/'.$segment.'/'.$package->id).'/additional-info' ?>" class="btn_admin2" title="Cancel">Cancel</a>
                </div>
                <br/><br/>
            </form>
			</div>
        </div>       
 
    </div>

@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">

        var description = document.getElementById('description');
        CKEDITOR.replace(description);

    </script>

@endslot

@endcomponent