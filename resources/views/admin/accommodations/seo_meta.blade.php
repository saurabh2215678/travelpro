@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ > -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
       /* .select2-container {height: 32px; }*/
        .bootstrap-tagsinput { display: block; }
    .tooltip {
          position: relative;
          display: inline-block;
          border-bottom: 1px dotted black;
          opacity: 1;
          font-style: normal;
        }

.tooltip .tooltiptext {
  visibility: hidden;
  width: 330px;
  background-color:#e4e4e4;
  color: #000;
  text-align: center;
  border-radius: 6px;
  padding: 10px;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
    </style>
    @endslot

    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/destinations';
    }

    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($accommodation->id))?$accommodation->id:'';
    //$package_id = (isset($package->package_id))?$package->package_id:null;
    $meta_title = (isset($accommodation->meta_title))?$accommodation->meta_title:'';
    $meta_description = (isset($accommodation->meta_description))?$accommodation->meta_description:'';

    ?>


<?php
$active_menu = "accommodations".$accommodation_id.'/seo_meta';
?>

@if(!empty($accommodation))
@include('admin.includes.accommodationoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
            
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
               
                <div class="form-group col-md-12 {{ $errors->has('meta_title') ? ' has-error' : '' }}">
                    <label for="meta_title" class="control-label">Meta Title:</label>

                    <input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                    @include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div class="form-group col-md-12 {{ $errors->has('meta_description') ? ' has-error' : '' }}">
                    <label for="meta_description" class="control-label">Meta Description:</label>

                    <textarea name="meta_description" id="meta_description"  class="form-control" >{{ old('meta_description', $meta_description) }}</textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_description'])
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <p></p>
                        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                        <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>

                        <a href="<?php echo url($routeName.'/accommodations/'.$accommodation->id).'/seo_view' ?>" class="btn_admin2" title="Cancel">Cancel</a>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>

@slot('bottomBlock')
    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
   
    

@endslot

@endcomponent