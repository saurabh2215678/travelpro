<style>
    .top_title_admin.fancybox-content {
    width: 800px;
    height: 600px;
}
</style>
<?php
$name = (isset($testimonial->name))?$testimonial->name:'';
$title = (isset($testimonial->title))?$testimonial->title:'';
$slug = (isset($testimonial->slug))?$testimonial->slug:'';
$destination_id = (isset($testimonial->destination_id))?json_decode($testimonial->destination_id,true):[];
$package_id = (isset($testimonial->package_id))?json_decode($testimonial->package_id,true):[];
$description = (isset($testimonial->description))?$testimonial->description:'';
$position_in_company = (isset($testimonial->position_in_company))?$testimonial->position_in_company:'';
$company_name = (isset($testimonial->company_name))?$testimonial->company_name:'';
$client_link = (isset($testimonial->client_link))?$testimonial->client_link:'';
$website = (isset($testimonial->website))?$testimonial->website:'';
// $pages_title = (isset($testimonial->pages_title))?$testimonial->pages_title:'';
// $pages_description = (isset($testimonial->pages_description))?$testimonial->pages_description:'';
// $pages_keywords = (isset($testimonial->pages_keywords))?$testimonial->pages_keywords:'';
$email = (isset($testimonial->email))?$testimonial->email:'';
$image = (isset($testimonial->image))?$testimonial->image:'';
$date_on = (isset($testimonial->date_on))?$testimonial->date_on:'';
$featured = (isset($testimonial->featured))?$testimonial->featured:1;
$status = (isset($testimonial->status))?$testimonial->status:1;
$sort_order = (isset($testimonial->sort_order))?$testimonial->sort_order:0;

$date_on = CustomHelper::DateFormat($date_on, 'd/m/Y');

$storage = Storage::disk('public');
$path = 'testimonials/';

if(!empty($testimonial)){
    $testimonialMedia = $testimonial->Media;
    if(!empty($testimonialMedia)){
        $media = $testimonialMedia->getFirstMedia('media');
    }
}
if(!empty($media)){
    $testimonial_img = $media->getUrl();
    $testimonial_thumb = $media->getUrl('thumb');
}

// $storage = Storage::disk('public');
// $path = 'testimonials/';

$pkgObj = "";
    if(!empty($package_id)){
        $pkgObj = App\Models\Package::whereIn('id',(array)$package_id)->get();
    }
$destinationObj = "";
    if(!empty($destination_id)){
        $destinationObj = App\Models\Destination::whereIn('id',(array)$destination_id)->get();
    }

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/testimonials';
}

$active_menu = "cms";
// $back_url = CustomHelper::BackUrl();
?>

<div class="top_title_admin">
    <?php
    // @if(!empty($id))
    //     @include('admin.includes.testimonialmenu')
    // @endif
    ?>
<div class="title">
<h2>{{ $page_heading }}</h2>
<div class="add_button">
<!-- <a <?php if($active_menu=='testimonials/view/'.$id){echo 'class="active"' ;}?>  href="{{ route($routeName.'.testimonials.edit', ['id'=>$id, 'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit Testimonials"></i>Edit Testimonials</a> -->
</div>
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">

            <tr>
                 <td>
                    <div><b>Title: </b></div>
                    <div>{{$testimonial->title}}</div>
                </td>

                <td>
                    <div><b>Name: </b></div>
                    <div>{{$testimonial->name}}</div>
                </td>
                <td>
                    <div><b>Destination: </b></div>
                    <div><?php if(!empty($destinationObj) && !($destinationObj->isEmpty())){
                        foreach ($destinationObj as $destination) {
                            $destinationArr[] = $destination->name;
                        }
                        echo implode(',', $destinationArr);
                    }
                    ?></div>
                </td>
                
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Description: </b></div>
                    <div>{!! $testimonial->description !!}</div>
                </td>
             </tr>

             <tr>
                <td colspan="3">
                    <div><b>Package: </b></div>
                    <div><?php if(!empty($pkgObj) && !($pkgObj->isEmpty())){
                        foreach ($pkgObj as $package) {
                            $packageArr[] = $package->title;
                        }
                        echo implode(',', $packageArr);
                    }
                    ?>
                    </div>
                </td>
             </tr>

            <tr>

                <td>
                    <div><b>Slug: </b></div>
                    <div>{{ $testimonial->slug }}</div>
                </td>

                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($testimonial->status) }}</div>
                </td>
                <td>
                    <div><b>Featured: </b></div>
                    <div>{{CustomHelper::getStatusStr($testimonial->featured)}}</div>
                </td>
               
            </tr>

            <tr>
                 <td>
                    <div><b>Image: </b></div>
                    <div><?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                </td>

                <td>
                    <div><b>Date On: </b></div>
                    <div>{{ CustomHelper::DateFormat($testimonial->date_on, 'd/m/Y') }}</div>
                </td>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($testimonial->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Position in Company</b></div>
                    <div>{{ $testimonial->position_in_company }}</div>
                </td>

                <td>
                    <div><b>Company Name</b></div>
                    <div>{{ $testimonial->company_name }}</div>
                </td>

                <td>
                    <div><b>Client Link</b></div>
                    <div>{{ $testimonial->client_link }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Website</b></div>
                    <div>{{ $testimonial->website }}</div>
                </td>

                <td>
                    <div><b>Email</b></div>
                    <div>{{ $testimonial->email }}</div>
                </td>

                 <td>
                    <div><b>DIsplay Order</b></div>
                    <div>{{ $testimonial->sort_order }}</div>
                </td>
            </tr>

            {{-- <tr>
                <td>
                    <div><b>Page Title</b></div>
                    <div>{{ $testimonial->meta_title }}</div>
                </td>

                <td>
                    <div><b>Page Keyword</b></div>
                    <div>{{ $testimonial->meta_keywords }}</div>
                </td>
            </tr> --}}

            {{--<tr>
                                        <td colspan="3">
                                            <div><b>Page Description: </b></div>
                                            <div>{!! $testimonial->meta_description !!}</div>
                                        </td>
                                     </tr>--}}
</table>
</div>
</div>