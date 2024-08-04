	@component('admin.layouts.main')

    @slot('title')
        Admin - Modules - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    // $BackUrl = CustomHelper::BackUrl();
    // $routeName = CustomHelper::getAdminRouteName();
    ?>
    
    <div class="row">
        <div class="col-md-12">
			<div class="titlehead">
			<h1 class="pull-left">Modules</h1>            
            
           

			</div>
		</div>
   </div>

      


   <div class="row">

    <div class="col-md-12">

        @include('snippets.errors')
        @include('snippets.flash')

        <?php

        if(!empty($module_data) && $module_data->count() > 0){
            ?>
            <div class="table-responsive">

               <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th class="">Name</th>
                        
                        <th class="">Action</th>
                    </tr>
                    <?php                  
                    foreach ($module_data as $module_data){
                       ?>
                       <tr>
                        <td>{{$module_data->name}}</td>
                        <td>
                            <input type="checkbox" name="modules[]" value="{{$module_data->id}}" <?php if($module_data->status == 1){ echo 'checked';} ?>>
                      </td>
                  </tr>
                  <?php
              }
              ?>
          </table>
          <div class="col-md-10" style="text-align: right;">
           <button type="submit" class="btn btn-primary " align-right id="paypal-payment-form-btn">Submit </button>
       </div>
   </form>
</div>

<?php
}
else{
    ?>
    <div class="alert alert-warning">There are no Records at the present.</div>
    <?php
}
?>
</div>

</div>


@slot('bottomBlock')

<script type="text/javascript">
$(".delBtn").click(function(){
    var conf = confirm("Are you sure you want to delete this record?");

    if(conf){
        $(this).siblings(".delForm").submit();
    }
});


function exportList(exportName){

    if(exportName ){
        if( exportName == 'export_xls'){
            var exportForm = $("form[name='exportForm']");

            exportForm.find("input[name='export_xls']").val('1');
            exportForm.find("input[name='export_inventory']").val('');
            document.exportForm.submit();
        }
    }

}

</script>

@endslot
   

@endcomponent

