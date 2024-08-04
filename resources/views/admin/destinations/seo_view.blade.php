@component('admin.layouts.main')

@slot('title')
Admin - SEO Meta View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
    $meta_title = (isset($destination->meta_title))?$destination->meta_title:'';
    $meta_keyword = (isset($destination->meta_keyword))?$destination->meta_keyword:'';
    $meta_description = (isset($destination->meta_description))?$destination->meta_description:'';
    
    //prd($experts);
    $routeName = CustomHelper::getAdminRouteName();
    //$back_url=CustomHelper::BackUrl(); 
    $back_url = request()->has('back_url') ? request()->input('back_url') : '';
?>
<?php
$active_menu = "destinations".$destination_id.'/seo_view';
?>

@if(!empty($destination))
@include('admin.includes.destinationoptionmenu')
@endif

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('destinations','edit'))
    <!-- <div class="add_button">
        <a <?php //if($active_menu=="destinations".$destination_id.'/seo_meta'){echo 'class="active"' ;}?>
            href="{{ route($routeName.'.destinations.seo_meta', ['destination_id'=>$destination->id, 'type'=>$des_type]) }}"
            class="btn_admin"><i class="fas fa-edit" title="Edit SEO Meta"></i>Edit SEO Meta</a>
    </div> -->
    @endif
</div>

<div class="centersec">
    <div class="bgcolor viewsec">
       
        @include('snippets.errors')
        @include('snippets.flash')

        <div class="alert_msg"></div>

        <?php
            $meta_title = isset($destination->meta_title) ? $destination->meta_title:"";
            $meta_keyword = isset($destination->meta_keyword) ? $destination->meta_keyword:"";
            $meta_description = isset($destination->meta_description) ? $destination->meta_description:"";

            $package_meta_title = isset($destination->package_meta_title) ? $destination->package_meta_title:"";
            $package_meta_keyword = isset($destination->package_meta_keyword) ? $destination->package_meta_keyword:"";
            $package_meta_description = isset($destination->package_meta_description) ? $destination->package_meta_description:"";

            $activity_meta_title = isset($destination->activity_meta_title) ? $destination->activity_meta_title:"";
            $activity_meta_keyword = isset($destination->activity_meta_keyword) ? $destination->activity_meta_keyword:"";
            $activity_meta_description = isset($destination->activity_meta_description) ? $destination->activity_meta_description:"";

            $hotels_pages_title = isset($destination->hotels_pages_title) ? $destination->hotels_pages_title:"";
            $hotels_pages_description = isset($destination->hotels_pages_description) ? $destination->hotels_pages_description:"";
            $hotels_meta_title = isset($destination->hotels_meta_title) ? $destination->hotels_meta_title:"";
            $hotels_meta_keyword = isset($destination->hotels_meta_keyword) ? $destination->hotels_meta_keyword:"";
            $hotels_meta_description = isset($destination->hotels_meta_description) ? $destination->hotels_meta_description:"";

        ?>

        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec"
            class="table-responsive">
            <tr>
                <td width="806" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                        <div class="top_title_admin">
                            <div class="title">
                            <h2 class="d-flex justify-content-between px-0" style="margin-left: -26px;">Destination Detail Meta</h2>
                            </div>
                            @if(CustomHelper::checkPermission('destinations','edit'))
                            <div class="add_button">
                                <a <?php if($active_menu=="destinations".$destination_id.'/seo_meta'){echo 'class="active"' ;}?>
                                    href="{{ route($routeName.'.destinations.seo_meta', ['destination_id'=>$destination->id]) }}"
                                    class="btn_admin"><i class="fas fa-edit" title="Edit Destination Detail Meta"></i>Edit</a>
                            </div>
                            @endif
                        </div>
                        <tr>
                            <td>
                                <div><b>Meta Title: </b></div>
                                <div>{{$meta_title}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Meta Keyword: </b></div>
                                <div>{{$meta_keyword}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Meta Description:</b></div>
                                <div>{{$meta_description}}</div>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td width="806" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                        <div class="top_title_admin">
                            <div class="title">
                            <h2 class="d-flex justify-content-between px-0" style="margin-left: -26px;">Packages List by Destination Meta</h2>
                            </div>
                            @if(CustomHelper::checkPermission('destinations','edit'))
                            <div class="add_button">
                                <a <?php if($active_menu=="destinations".$destination_id.'/seo_meta'){echo 'class="active"' ;}?>
                                    href="{{ route($routeName.'.destinations.seo_meta', ['destination_id'=>$destination->id, 'type'=>'package']) }}"
                                    class="btn_admin"><i class="fas fa-edit" title="Edit Packages List by Destination Meta"></i>Edit</a>
                            </div>
                            @endif
                        </div>
                        <tr>
                            <td>
                                <div><b>Meta Title: </b></div>
                                <div>{{$package_meta_title}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Meta Keyword: </b></div>
                                <div>{{$package_meta_keyword}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Meta Description:</b></div>
                                <div>{{$package_meta_description}}</div>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td width="806" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                        <div class="top_title_admin">
                            <div class="title">
                            <h2 class="d-flex justify-content-between px-0" style="margin-left: -26px;">Activities List by Destination Meta</h2>
                            </div>
                            @if(CustomHelper::checkPermission('destinations','edit'))
                            <div class="add_button">
                                <a <?php if($active_menu=="destinations".$destination_id.'/seo_meta'){echo 'class="active"' ;}?>
                                    href="{{ route($routeName.'.destinations.seo_meta', ['destination_id'=>$destination->id, 'type'=>'activity']) }}"
                                    class="btn_admin"><i class="fas fa-edit" title="Edit Activities List by Destination Meta"></i>Edit</a>
                            </div>
                            @endif
                        </div>
                        <tr>
                            <td>
                                <div><b>Meta Title: </b></div>
                                <div>{{$activity_meta_title}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Meta Keyword: </b></div>
                                <div>{{$activity_meta_keyword}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Meta Description:</b></div>
                                <div>{{$activity_meta_description}}</div>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td width="806" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                        <div class="top_title_admin">
                            <div class="title">
                            <h2 class="d-flex justify-content-between px-0" style="margin-left: -26px;">Hotels List by Destination Meta</h2>
                            </div>
                            @if(CustomHelper::checkPermission('destinations','edit'))
                            <div class="add_button">
                                <a <?php if($active_menu=="destinations".$destination_id.'/seo_meta'){echo 'class="active"' ;}?>
                                    href="{{ route($routeName.'.destinations.seo_meta', ['destination_id'=>$destination->id, 'type'=>'hotel']) }}"
                                    class="btn_admin"><i class="fas fa-edit" title="Edit Hotels List by Destination Meta"></i>Edit</a>
                            </div>
                            @endif
                        </div>
                        <tr>
                            <td>
                                <div><b>Page Title: </b></div>
                                <div>{{$hotels_pages_title}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Page Description: </b></div>
                                <div>{!!$hotels_pages_description!!}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Meta Title: </b></div>
                                <div>{{$hotels_meta_title}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Meta Keyword: </b></div>
                                <div>{{$hotels_meta_keyword}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Meta Description:</b></div>
                                <div>{{$hotels_meta_description}}</div>
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