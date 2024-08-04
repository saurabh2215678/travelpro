@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
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
        $back_url = $routeName.'/blogs';
    }
    $routeName = CustomHelper::getAdminRouteName();

    $meta_title = (isset($blogs->meta_title))?$blogs->meta_title:'';
    $meta_keyword = (isset($blogs->meta_keyword))?$blogs->meta_keyword:'';
    $meta_description = (isset($blogs->meta_description))?$blogs->meta_description:'';
    ?>


<?php
$active_menu = "blogs".$id.'/seo_meta';
?>

@if(!empty($blogs))
@include('admin.includes.blogmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
    <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a><?php } ?>
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

                <div class="form-group col-md-12{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                    <label for="meta_keyword" class="control-label" maxlength="688" >Meta Keyword:</label>

                    <input type="text" name="meta_keyword" value="{{ old('meta_keyword', $meta_keyword)}}" id="meta_keyword" class="form-control"  />    

                    @include('snippets.errors_first', ['param' => 'meta_keyword'])
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

                        <a href="{{ route($routeName.'.blogs.seo_view',['id'=>$id, 'type'=>$type]) }}" class="btn_admin2" title="Cancel">Cancel</a>
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