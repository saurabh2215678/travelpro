
@component('admin.layouts.main')

@slot('title')
Admin - Manage Service Level Type View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?phpservice_level_type
$name = (isset($service_query->name))?$service_query->name:'';
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
                     <h2>Service Level Type Detail</h2> 
                     <?php
                 
                            ?>
                     <tr>
                        <td><b>Name : </b></td>
                        <td>
                        	{{$service_query->name}}
                     </td>
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




