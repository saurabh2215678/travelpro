
@component('admin.layouts.main')

@slot('title')
Admin - Manage Destination Type - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$name = (isset($destination_query->name))?$destination_query->name:'';
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
                     <h2>Destination Type Detail</h2> 
                     <?php
                 
                            ?>
                     <tr>
                        <td><b>Name : </b></td>
                        <td>
                        	{{$destination_query->name}}
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




