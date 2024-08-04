
@component('admin.layouts.main')

@slot('title')
Admin - Faq Categories View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php

$name = (isset($page->name))?$page->name:'';
$related_destinations = (isset($page->related_destinations))?json_decode($page->related_destinations):[];
$slug = (isset($page->slug))?$page->slug:'';
$title = (isset($page->title))?$page->title:'';
$category_id = (isset($faq->category_id))?json_decode($faq->category_id,true):[];
$heading = (isset($page->heading))?$page->heading:'';
$brief = (isset($page->brief))?$page->brief:'';
$template = (isset($page->template))?$page->template:'';
$content = (isset($page->content))?$page->content:'';
$status = (isset($page->status))?$page->status:1;
$featured = (isset($page->featured))?$page->featured:'';

$meta_title = (isset($page->meta_title))?$page->meta_title:'';
$meta_keyword = (isset($page->meta_keyword))?$page->meta_keyword:'';
$meta_description = (isset($page->meta_description))?$page->meta_description:'';
$image = (isset($page->image))?$page->image:'';
$banner_image = (isset($page->banner_image))?$page->banner_image:'';
$document = (isset($page->document))?$page->document:'';
$doc_link = (isset($page->doc_link))?$page->doc_link:'';

$storage = Storage::disk('public');
$path = 'faq_categories/';

$relaCMStObj = "";
    if(!empty($related_destinations)){
        $relaCMStObj = App\Models\Destination::whereIn('id',$related_destinations)->get();
    }
$faqCategoriesObj = "";
    if(!empty($category_id)){
        $faqCategoriesObj = App\Models\CourseCategory::whereIn('id',$category_id)->get();
    }
?>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table width="1100" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                     <h2>Faq Category Detail</h2> 
                     <?php
                 
                            ?>
               

                 <tr>
                    <td><b>Related Destinations :  </b></td>
                    <td>
                        <?php if(!empty($relaCMStObj) && !($relaCMStObj->isEmpty())){
                        foreach ($relaCMStObj as $hotel) {
                            $hotelsArr[] = $hotel->name;
                            }
                            echo implode(', ', $hotelsArr);
                        }
                    ?>
                    </td>
                </tr>

                <tr>
                <td><b>Faq Category : </b></td>
                <td>
                    <?php if(!empty($faqCategoriesObj) && !($faqCategoriesObj->isEmpty())){
                        foreach ($faqCategoriesObj as $faqCategory) {
                            $faqArr[] = $faqCategory->title;
                        }
                        echo implode(', ', $faqArr);
                    }
                    ?>
                </td>
               </tr>

                
                 <tr>
                    <td><b>Slug: </b></td>
                    <td>{{$page->slug}}</td>
                </tr>

                      <tr>
                        <td><b>Title : </b></td>
                        <td>
                            {{$page->title}}
                     </td>
                 </tr>
                 <tr>
                    <td><b>Heading: </b></td>
                    <td>{{$page->heading}}</td>
                </tr>
                <tr>
                    <td><b>Brief: </b></td>
                    <td>{{$page->brief}}</td>
                </tr>
                <tr>
                    <td><b>Template: </b></td>
                    <td>{!!$page->template!!}</td>
                </tr>
                <tr>
                    <td><b>Content: </b></td>
                    <td>{!!$page->content!!}</td>
                </tr>

                 <tr>
                    <td><b>Status: </b></td>
                    <td>{{CustomHelper::getStatusStr($page->status)}}</td>
                </tr>
                <tr>
                    <td><b>Featured: </b></td>
                    <td>{{CustomHelper::getStatusStr($page->featured)}}</td>
                </tr>

                <tr>
                    <td><b>Sort Order: </b></td>
                    <td>{{$page->sort_order}}</td>
                </tr>
                <tr>
                    <td><b>Meta Title: </b></td>
                    <td>{{$page->meta_title}}</td>
                </tr>
                <tr>
                    <td><b>Meta Keyword: </b></td>
                    <td>{{$page->meta_keyword}}</td>
                </tr>
                <tr>
                    <td><b>Meta Description: </b></td>
                    <td>{{$page->meta_description}}</td>
                </tr>
                <tr>
                    <td><b>Image: </b></td>
                    <td>
                        <?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                        </td>
                </tr>
                <tr>
                    <td><b>Banner Image: </b></td>
                    <td><?php
                    if(!empty($banner_image)){
                        if($storage->exists($path.$banner_image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$banner_image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?></td>
                </tr>
                <tr>
                    <td><b>Document: </b></td>
                    <td><?php
                    if(!empty($document)){
                        if($storage->exists($path.$document)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$document) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?></td>
                </tr>
                <tr>
                    <td><b>Doc Link: </b></td>
                    <td>{{$page->doc_link}}</td>
                </tr>

                <tr>
                    <td><b>Date Created: </b></td>
                    <td>{{ CustomHelper::DateFormat($page->created_at, 'd/m/Y') }}</td>
                </tr>
</table>
</td>
</tr>
</table> 
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent