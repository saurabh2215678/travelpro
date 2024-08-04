@component('admin.layouts.main')

    @slot('title')
        Admin - Site-Map - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot


    <?php    

    // $id = (isset($homeImage->id))?$homeImage->id:'';
    // $title = (isset($homeImage->title))?$homeImage->title:'';  
    // $subtitle = (isset($homeImage->subtitle))?$homeImage->subtitle:'';  
    // $link = (isset($homeImage->link))?$homeImage->link:'';  
    // $image = (isset($homeImage->image))?$homeImage->image:'';
    // $sort_order = (isset($homeImage->sort_order))?$homeImage->sort_order:'';
    // $status = (isset($homeImage->status))?$homeImage->status:1;
   
    // $storage = Storage::disk('public');

    // //pr($storage);

    // $path = 'home_images/';
    $old_sitemap = 0;
    $old_robot = 0;
    // $image_req = 'required';
    // $link_req = '';

    // $IMG_HEIGHT = CustomHelper::WebsiteSettings('HOMEIMAGE_IMG_HEIGHT');
    // $IMG_WIDTH = CustomHelper::WebsiteSettings('HOMEIMAGE_IMG_WIDTH');

    // $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:768;
    // $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:768;

    ?>
 
    <h2>Site-Map</h2>

    <div class="bgcolor">

        <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}              

            
                    <?php
        // $image_required = $image_req;
        // if($id > 0){
        //     $image_required = 'required';
        // }
        ?>
        <div class="row">
            <div class="col-md-4">

                <div class="form-group{{ $errors->has('sitemap') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label "> Sitemap :</label>

                    <input type="file" id="sitemap" name="sitemap"/>

                    @include('snippets.errors_first', ['param' => 'sitemap'])
                </div>

                <input type="hidden" name="old_sitemap" value="{{ $old_sitemap }}">

            </div>

            <div class="col-md-4">

                <div class="form-group{{ $errors->has('robot') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label "> Robot :</label>

                    <input type="file" id="robot" name="robot"/>

                    @include('snippets.errors_first', ['param' => 'robot'])
                </div>

             

                <input type="hidden" name="old_robot" value="{{ $old_robot }}">

            </div>



        </div>


            <div class="col-md-12">
                <div class="form-group">
                    <p></p><br>

                    <button type="submit" class="btn btn-success" title="Create this new sitemap"><i class="fa fa-save"></i> Submit</button>
                </div>
            </div>

      </form>
<div class="clearfix"></div>
</div>
 

@endcomponent

