@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot
<?php
   $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
   $routeName = CustomHelper::getAdminRouteName();
   if(empty($back_url)){
       $back_url = $routeName.'/cab_route/groups';
   }
   //pr($blog->toArray());
   $routeName = CustomHelper::getAdminRouteName();
   $id = (isset($group->id))?$group->id:'';
   $name = (isset($group->name))?$group->name:'';
   $status = (isset($group->status))?$group->status:1;
   $sharing = (isset($group->sharing))?$group->sharing:0;
   $inclusion = (isset($group->inclusion))?$group->inclusion:'';
   $exclusion = (isset($group->exclusion))?$group->exclusion:'';
   $term = (isset($group->term))?$group->term:'';
   $cab_data = (isset($group->cab_data))?json_decode($group->cab_data):'';
   $selected_cab = [];
   if($cab_data){
      foreach ($cab_data as $key => $cab_row) {
         $selected_cab[] = $cab_row->id ;
      }
   }
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
               <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  <label class="required" >Name:</label><br/>
                  <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                  @include('snippets.errors_first', ['param' => 'name'])                          
               </div>
            </div>
             <div class="col-md-4">
               <div class="form-group{{ $errors->has('cab') ? ' has-error' : '' }}">
                  <label class="required">Cab:</label><br/>
                  <select name="cab[]" class="form-control admin_input1 select2" multiple="multiple">
                     <option value="">Select</option>
                     <?php foreach ($cabs as $key => $cab) {
                        $selected = "";
                        if(in_array($cab->id,$selected_cab)){
                           $selected = "selected";
                        }
                      ?>
                     <option value="{{$cab->id}}" {{$selected}}>{{$cab->name}}</option>
                   <?php } ?>
                  </select>                  
                  @include('snippets.errors_first', ['param' => 'cab'])                          
               </div>
            </div>
            <div class="form-group  col-md-12{{ $errors->has('inclusion') ? ' has-error' : '' }}">
                    <label for="inclusion" class="control-label">Inclusion:</label>
                    <textarea name="inclusion" id="inclusion" class="form-control ckeditor" ><?php echo old('inclusion', $inclusion); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'inclusion'])
            </div>
            <div class="form-group  col-md-12{{ $errors->has('exclusion') ? ' has-error' : '' }}">
                    <label for="exclusion" class="control-label">Exclusion:</label>
                    <textarea name="exclusion" id="exclusion" class="form-control ckeditor" ><?php echo old('exclusion', $exclusion); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'exclusion'])
            </div>
            <div class="form-group  col-md-12{{ $errors->has('term') ? ' has-error' : '' }}">
                    <label for="term" class="control-label">Term:</label>
                    <textarea name="term" id="term" class="form-control ckeditor" ><?php echo old('term', $term); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'term'])
            </div>
            <div class="form-group col-md-2{{$errors->has('sharing')?' has-error':''}} ">
                    <label class="control-label ">Sharing:</label>
                    <input type="checkbox" name="sharing" value="1" <?php echo ($sharing == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'sharing'])
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
         <a href="{{ url($back_url) }}" class="btn_admin2">Cancel</a>
         <div class="panel panel-default">  
      </form>
   </div>
</div>
<div class="clearfix"></div>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>


<script type="text/javascript">

var editor = CKEDITOR.replace('inclusion',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});
var editor = CKEDITOR.replace('exclusion',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});
var editor = CKEDITOR.replace('term',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});

   var room = 1;
   
           window.alert = function(){};
           var defaultCSS = document.getElementById('bootstrap-css');
           function changeCSS(css){
               if(css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="'+ css +'" type="text/css" />'); 
               else $('head > link').filter(':first').replaceWith(defaultCSS); 
           }
           $( document ).ready(function() {
             var iframe_height = parseInt($('html').height()); 
             window.parent.postMessage( iframe_height, 'https://bootsnipp.com');
           });


      $('.select2').select2({
        placeholder: "Please Select",
        allowClear: true
      });      
</script>
@endslot
@endcomponent