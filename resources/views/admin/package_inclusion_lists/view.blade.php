
@component('admin.layouts.main')

@slot('title')
Admin - Manage {{ucfirst($segment)}} Inclusion List View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$title = (isset($package_list->title))?$package_list->title:'';
$status = (isset($package_list->status))?$package_list->status:1; 
$sort_order = (isset($package_list->sort_order))?$package_list->sort_order:0;
?>

<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

    <div class="alert_msg"></div>
    <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table width="1100" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">
          <tr>
            <td width="10" valign="top" height="400">
             <td width="806" valign="top" class="innersec">
                 <table cellspacing="1" cellpadding="0" border="0" width="100%">
                     <h2>{{ucfirst($segment)}} Inclusion List Detail</h2>
                     <tr>
                        <td><b>Title : </b></td>
                        <td>
                        	{{$package_list->title}}
                     </td>
                 </tr>
                 <tr>
                    <td><b>Status: </b></td>
                    <td>{{$package_list->status}}</td>
                </tr>

                <tr>
                    <td><b>SortBy: </b></td>
                    <td>{{$package_list->sort_order}}</td>
                </tr>
            </table>    
        </td>
    </tr>
</table>
</form>
</div>
@slot('bottomBlock')
@endslot
@endcomponent