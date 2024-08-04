@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
       /* .select2-container {height: 32px; }*/
        .bootstrap-tagsinput { display: block; }
    </style>
    @endslot

<?php

$BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

$id = (isset($faq->id))?$faq->id:'';
$question = (isset($faq->question))?$faq->question:'';
$destination_id = (isset($faq->destination_id))?$faq->destination_id:'';
$sub_destination_id = (isset($faq->sub_destination_id))?$faq->sub_destination_id:'';
$answer = (isset($faq->answer))?$faq->answer:'';
$sort_order = (isset($faq->sort_order))?$faq->sort_order:0;
$status = (isset($faq->status))?$faq->status:1;
$category  =  isset($faq->category) ? explode(',', $faq->category) : 0;
?>

@if(!empty($tbl_name) && $tbl_name=='packages')
    <?php
        $package_id = $tbl_id;
        $active_menu = "packages".$package_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.packageoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destinations')
    <?php
        $destination_id = $tbl_id;
        $active_menu = "destinations".$destination_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.destinationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='accommodations')
    <?php
        $accommodation_id = $tbl_id;
        $active_menu = "accommodations".$accommodation_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.accommodationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='cms_pages')
    <?php
        $id = $tbl_id;
        $active_menu = "cms".$id.'/'.$tbl_category;
    ?>
    @include('admin.includes.cmsmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='seo_meta_tags')
    <?php
        $module_config_id = $tbl_id;
        $active_menu = "module_config".$module_config_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.modulemenu')
@endif
@if(!empty($tbl_name) && $tbl_name=='themes')
    <?php
        $theme_id = $tbl_id;
        $active_menu = "themes".$theme_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.themefaqmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destination_packages')
    <?php
        $destination_id = $tbl_id;
        $active_menu = "destination_packages".$destination_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.destinationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destination_activity')
    <?php
        $destination_id = $tbl_id;
        $active_menu = "destination_activity".$destination_id.'/'.$tbl_category;
    ?>
    @include('admin.includes.destinationoptionmenu')
@endif



<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
        @if(isset($BackUrl))
        <a href="{{ url($BackUrl)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a>
        @endif
    </div>
</div>

    <div class="centersec">
	<div class="bgcolor">
            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
		
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="form-group col-md-12{{ $errors->has('question') ? ' has-error' : '' }}">
                    <label for="question" class="control-label required">Question:</label>
                    <input type="text" name="question" id="question" class="form-control" value="{{ old('question',$question) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'question'])
                </div>

                <div style="display:none;" class="form-group col-md-6{{ $errors->has('destination_id') ? ' has-error' : '' }}">
                    <label for="destination_id" class="control-label">Destination:</label>
                    <select class="form-control select2" name="destination_id" id="destination">
                        <?php
                        $destination_id = old('destination_id',$destination_id);
                        if(!empty($destinations)){
                        $parent_destinations = $destinations->where('parent_id', 0);
                        ?>
                            <option value="">Select</option>
                            <?php
                            if(!($parent_destinations->isEmpty())){
                            foreach ($parent_destinations as $destination_it){
                                $selected = '';
                                if($destination_it->id == $destination_id){
                                    $selected = 'selected';
                                }
                                $des_status = '';
                                if($destination_it->status != 1 ){
                                    $des_status = ' (Inactive)';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}{{$des_status}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination_id'])
                </div>

                <div style="display:none;" class="form-group col-md-6{{ $errors->has('sub_destination_id') ? ' has-error' : '' }}">
                    <label for="sub_destination_id" class="control-label">Sub Destination:</label>
                    <select class="form-control select2" name="sub_destination_id" id="sub_destination">
                        <?php $sub_destination_id = old('sub_destination_id',$sub_destination_id); ?>
                        <option value="">Select</option>
                    </select>
                    @include('snippets.errors_first', ['param' => 'sub_destination_id'])
                </div>

                <div style="display:none;" class="form-group col-md-6{{ $errors->has('category') ? ' has-error' : '' }}">
                <label for="category" class="control-label">Faq Category:</label>
                <select class="form-control select2" name="category[]" multiple>
                 <?php
                 $category = old("category[]",$category);
                 if(!empty($categories)){ ?>
                  <option value="">Select</option>
                  
                  <?php
                     // prd($categories);
                  foreach ($categories as $categoryFaq){
                    ?>
                    <option value="{{$categoryFaq->id}}" <?php echo is_array($category) && in_array($categoryFaq->id,$category) ? 'selected' : ''  ?> >{{$categoryFaq->title}}</option>
                  <?php } } ?>
                </select>
                @include('snippets.errors_first', ['param' => 'category'])
                </div>

				<div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('answer') ? ' has-error' : '' }}">
                	<label for="answer" class="control-label required">Answer:</label>

                	<textarea name="answer" id="answer" class="form-control ckeditor" ><?php echo old('answer', $answer); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'answer'])
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

                    <a href="{{ route('admin.faqs.index',['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$tbl_category,'segment'=>$segment]) }}" class="btn_admin2" title="Cancel">Cancel</a>
                </div>
				<br/><br/>

            </form>
			</div>
        </div>       
 


@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        var _token = '{{ csrf_token() }}';

         $(document).ready(function () {

        var destinationId = $('#destination').val();
        var subDestinationId = '{{ $sub_destination_id }}';

        populateSubDestination(destinationId,subDestinationId);

        $('#destination').on('change',function(e) {
            var destination_id = e.target.value;
            populateSubDestination(destination_id);
        });     

    });
         function populateSubDestination(destination_id,setDestination=""){
        $.ajax({
            url:"{{ route('common.ajax_load_sub_destinations') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
                destination_id: destination_id
            },
            success:function (data) {
                let text = "";
                $('#sub_destination').empty();
                text +=  `<option value="">---Select Sub Destination---</option>`
                text += data.options;
                $("#sub_destination").html(text)
            },complete:function(){
                $('#sub_destination').val(setDestination);
            }
        });
    }
        var description = document.getElementById('answer');
        CKEDITOR.replace(description);
        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });
       
    </script>

@endslot
@endcomponent