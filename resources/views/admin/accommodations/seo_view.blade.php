
@component('admin.layouts.main')

@slot('title')
Admin - SEO Meta View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
    

    $meta_title = (isset($accommodation->meta_title))?$accommodation->meta_title:'';
    $meta_description = (isset($accommodation->meta_description))?$accommodation->meta_description:'';
    
    //prd($experts);
    $routeName = CustomHelper::getAdminRouteName();
    //$back_url=CustomHelper::BackUrl(); 
    $back_url = request()->has('back_url') ? request()->input('back_url') : '';
?>
<?php
$active_menu = "accommodations".$accommodation_id.'/seo_view';
?>

@if(!empty($accommodation))
@include('admin.includes.accommodationoptionmenu')
@endif

<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <a  <?php if($active_menu=="accommodations".$accommodation_id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.accommodations.seo_meta', ['accommodation_id'=>$accommodation->id]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit SEO Meta"></i>Edit SEO Meta</a>
    </div>
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
                        <div>{{isset($accommodation->meta_title) ? $accommodation->meta_title:""}}</div>
                    </td>
            </tr>

            <tr>
                <td>
                    <div><b>Meta Description:</b></div>
                    <div>{{isset($accommodation->meta_description) ? $accommodation->meta_description:""}}</div>
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



