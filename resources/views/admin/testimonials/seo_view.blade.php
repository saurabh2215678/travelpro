
@component('admin.layouts.main')

@slot('title')
Admin - SEO Meta View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
    $meta_title = (isset($testimonial->meta_title))?$testimonial->meta_title:'';
    $meta_keywords = (isset($testimonial->meta_keywords))?$testimonial->meta_keywords:'';
    $meta_description = (isset($testimonial->meta_description))?$testimonial->meta_description:'';
    
    $routeName = CustomHelper::getAdminRouteName();
    $back_url = CustomHelper::BackUrl();
    $active_menu = "testimonials".$id.'/seo_view';
?>

@if(!empty($testimonial))
    @include('admin.includes.testimonialmenu')
@endif

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('testimonials','edit'))
    <div class="add_button">
        <a  <?php if($active_menu=="testimonials".$id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.testimonials.seo_meta', ['id'=>$id, 'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit SEO Meta"></i>Edit SEO Meta</a>
    </div>
    @endif
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
        <h2 class="d-flex justify-content-between px-0">
            

        </h2> 

             <tr>
                
                    <td>
                        <div><b>Meta Title: </b></div>
                        <div>{{isset($testimonial->meta_title) ? $testimonial->meta_title:""}}</div>
                    </td>
            </tr>

             <tr>
                
                    <td>
                        <div><b>Meta Keyword: </b></div>
                        <div>{{isset($testimonial->meta_keywords) ? $testimonial->meta_keywords:""}}</div>
                    </td>
            </tr>

            <tr>
                <td>
                    <div><b>Meta Description:</b></div>
                    <div>{{isset($testimonial->meta_description) ? $testimonial->meta_description:""}}</div>
                </td>
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




