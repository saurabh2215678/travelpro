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
       // $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
       $experts = (!empty($package) && !empty($package->experts)) ? json_decode($package->experts) : [];
       ?>   

       <form action="<?php echo route('admin.packages.update_expert',[$package->id]) ?>" class="form-inline" method="POST">
      {{ csrf_field() }}
         <table class="table table-striped table-bordered table-hover" >
            <tr>
                <td>
                    <label>Experts :</label><br>
                    <select class="form-control select2" name="experts[]" multiple>
                        <?php
                        $experts = old('experts[]',$experts);
                        if(!empty($packageExperts)){ ?>
                            <?php foreach ($packageExperts as $expert) {
                                $selected = '';
                                if(in_array($expert->id,$experts)) {
                                    $selected = 'selected';
                                }
                            ?>
                        {{-- <option value="{{$expert->id}}" {{$selected}}>{{$expert->first_name.' '.$expert->last_name}}</option>--}}
                        <option value="{{$expert->id}}" {{$selected}}>{{$expert->title}}</option>
                        <?php } } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="data_id" value="">
                    <input type="submit" name="" class="btn_admin2 location_form_submit" value="Submit">
                    <!-- <input type="button" name="" class="btn_admin2 location_form_cancel" value="Cancel"> -->
                   <?php //<a href="{{ url($routeName.'/accommodations/update_vendor/'.$accommodation->id) }}" class="btn_admin2" title="Cancel">Cancel</a> ?>
                   @if($is_activity == 1)
                   <a href="{{route($routeName.'.activity.index')}}" class="btn_admin2">Cancel</a>
                   @elseif($is_activity == 0)
                   <a href="{{route($routeName.'.packages.index')}}" class="btn_admin2">Cancel</a>
                   @endif
                </td></tr>
            </table>
        </form>
    </div>  
 </body>
</html>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('.select2').select2({ placeholder: "Select Experts", allowClear: true });
</script>