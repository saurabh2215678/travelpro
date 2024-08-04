<!DOCTYPE html>
<html>
<head>
 <title></title>
</head>
<body>
    <?php
    $routeName = CustomHelper::getAdminRouteName();
    ?>
    <div class="table-responsive">  
       <?php 
       $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
       $vendor_id = $accommodation->vendor_id;  ?>   

       <form action="<?php echo route('admin.accommodations.update_vendor',[$accommodation->id]) ?>" class="form-inline" method="POST">
      {{ csrf_field() }}
         <table class="table table-striped table-bordered table-hover" >
            <tr>
                <td>
                    <label>Vendor :</label><br>
                    <select class="form-control admin_input1 select2" name="vendor_id" id="sel_vendor_id" style="width: auto !important;">  
                        <option value="">Select</option>
                        <?php
                        foreach ($vendors as $vendor)
                        {
                            $selected = '';
                            if($vendor->id == $vendor_id) {
                                $selected = 'selected';
                            }
                            ?>
                            <option value="{{$vendor->id}}" {{$selected}}>{{$vendor->name}}</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="data_id" value="">
                    <input type="submit" name="" class="btn_admin2 location_form_submit" value="Submit">
                    <!-- <input type="button" name="" class="btn_admin2 location_form_cancel" value="Cancel"> -->
                   <?php //<a href="{{ url($routeName.'/accommodations/update_vendor/'.$accommodation->id) }}" class="btn_admin2" title="Cancel">Cancel</a> ?>
                   <a href="{{route($routeName.'.accommodations.index')}}" class="btn_admin2">Cancel</a>
                </td></tr>
            </table>
        </form>
    </div>  
 </body>
</html>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('.select2').select2({ placeholder: "Select Vendor", allowClear: true });
</script>