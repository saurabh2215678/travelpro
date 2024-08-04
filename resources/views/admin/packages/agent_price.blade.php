@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Agent Price - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    // $back_url = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_day_title = (request()->has('day_title'))?request()->day_title:'';
    $old_status = app('request')->input('status');
    ?>

<?php
$active_menu = "packages".$package_id.'/agent_price';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $heading }}</h2>
    </div>
   
</div>


<!-- Start Search box section     -->
<div class="centersec">


        <div class="bgcolor">

          @include('snippets.errors')
          @include('snippets.flash')

          <div class="ajax_success_msg alert alert-success" style="display:none"></div>       
          <div class="ajax_error_msg alert alert-danger" style="display:none;">Discount Type is required!</div>       
          <div class="ajax_error_msg_discount alert alert-danger" style="display:none;">Invalid Discount!</div>

            <div class="table-responsive" id="discount_form" style="display: none;margin-bottom: 10px;">
                     <div class="full">
                        <label style="display: inline-block;">Select discount category for this package:</label>
                        <select name="discount_category" id="discount_category" class="form-control admin_input1" style="display: inline-block; width:auto !important; margin-bottom:0;">
                            <option value="0">Discount Not Applicable</option>
                            <?php foreach ($discount_categories as $key => $discount_category_row) { ?>
                                <option value="{{$discount_category_row->id ?? ''}}" {{ ($discount_category_id == $discount_category_row->id)?'selected':'' }}>{{$discount_category_row->name ?? ''}}</option>
                            <?php } ?>
                            <option value="-1" {{($discount_category_id == '-1')?'selected':'' }} >Custom Discount</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                   <div class="col-md-6" id="update_section">
                    <!-- <label></label><br> -->
                        <button type="submit" class="btn btn-success" onclick="save_discoount_category()">Update</button>
                        <a href="{{route($routeName.'.'.$segment.'.agent_price',['package_id'=>$package_id])}}" class="btn_admin2">Cancel</a>
                    </div>  
            </div>
            <div class="row" id="custom_discount_section" style="display: none;">
              <div class="col-md-12">
                @include('admin.discount.add_agent_groups')
              </div>
            </div>
        </div>

<!-- End Search box Section -->
            <div class="table-responsive discount_category">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                       <td><strong> Selected discount category</strong></td>
                       <td>{{$discount_category_name}} {{($is_default_category == 1)?'(Default)': ''}}</td>
                       <td>&nbsp <button class="btn btn_admin2 edit_form">Edit</button></td>
                    </tr>
                </table>
                <p>Applicable price for agents based on selected discount category</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <?php
                  $discount_module_to_group = $discount_category_data->DiscountModuleToGroup??[];
                  // prd($discount_module_to_group);
                  if(!empty($discount_module_to_group) && count($discount_module_to_group) > 0 || $discount_category_id == '-1'){?>
                    <tr>
                        <th>Retail Price</th>

                        <th>
                          B2C Price <br> (Discount Amount)<br>
                          <?php
                          $agent_group_id = '-1';
                          if($discount_category_id != 0){
                            $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$agent_group_id,$module_record_id);
                          }
                          ?>
                          {{$discount_type}}
                        </th>
                        
                      <?php $publish_price = $packageDetails->publish_price?? '' ;
                     $discount_amount = 0;
                     foreach ($agent_groups as $key => $agent_group) {
                     ?>  <th>B2B ({{$agent_group->name}}) Price <br> (Discount Amount)<br>
                        <?php  
                        if($discount_category_id != 0){
                            $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$agent_group->id,$module_record_id);
                        }
                        ?>
                        {{$discount_type}}
                      </th>   
                 <?php } ?>
                    </tr>
                    <tr>
                        <td>{{CustomHelper::getPrice($publish_price)}}</td>
                        <td>
                          <?php
                          $agent_group_id = '-1';
                          $agent_price = $publish_price;
                          if($publish_price > 0 && $discount_category_id != 0) {
                            $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                            if($publish_price > $discount_amount) {
                              $agent_price = $publish_price - $discount_amount;
                            }
                          }
                          ?>
                          {{CustomHelper::getPrice($agent_price)}} <br> ({{CustomHelper::getPrice($discount_amount)}})
                        </td>
                        <?php 
                         foreach ($agent_groups as $key => $agent_group) {
                            $agent_price = $publish_price;
                            if($publish_price > 0 && $discount_category_id != 0){
                               $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group->id,$module_record_id);
                               if($publish_price > $discount_amount){
                                 $agent_price = $publish_price - $discount_amount;
                             }
                           }
                        ?>
                        <td>{{CustomHelper::getPrice($agent_price)}} <br> ({{CustomHelper::getPrice($discount_amount)}})</td>
                    <?php } ?>
                    </tr>
                  <?php } else if(empty($discount_category_id)) { ?>
                  <tr>
                    <td align="center"><div class="alert alert-warning"><strong> Agent discount not applicable.</strong></div></td>
                  </tr>
                  <?php } else { ?>
                  <tr>
                    <td align="center"><div class="alert alert-warning"><strong> The selected discount category does not contain discount data. Go to "Discount for Agent Groups" and add applicable discount.</strong></div></td>
                  </tr>
                  <?php } ?>
                </table>
            </div>            
            </div>
    </div>

@slot('bottomBlock')
<script type="text/javaScript">
  $(document).on('change','#discount_category',function(){
        var discount_category_id = $(this).val();
        showHideCustomDiscount(discount_category_id);
      });

    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });

    $('.edit_form').click(function(){
      $('#discount_form').show();
      $('.discount_category').hide();
      showHideCustomDiscount('<?php echo $discount_category_id; ?>');
    });

    function showHideCustomDiscount(discount_category_id) {
      if(discount_category_id == '-1') {
        $('#custom_discount_section').show();
        $('#update_section').hide();
      } else {
        $('#custom_discount_section').hide();
        $('#update_section').show();
      }
    }


    function save_discoount_category(){

       var discount_category = $('#discount_category').val();
       var package_id = '{{$package_id}}';

       var _token = '{{ csrf_token() }}';
           $.ajax({
            url: "{{ route('admin.packages.add_agent_price') }}",
            type: "POST",
            data: { discount_category:discount_category, package_id:package_id },
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            async: false,
            beforeSend:function(){
                $(".ajax_success_msg").hide();
            },
            success: function(resp){
                if(resp.success){
                    $(".ajax_success_msg").html('Discount category saved');
                    $(".ajax_success_msg").show();
                    setTimeout( function() {
                        $(".ajax_success_msg").hide();
                        window.location.reload();
                    }, 700 );

                
                }
            }
        });

    }

    function save_discoount(group_id) {
      var module_name = '{{$module_name}}';
      var module_record_id = '{{$module_record_id}}';
      var discount_type = $('#discount_type_'+group_id).val();
      if(discount_type == ''){
        //alert('Discount Type is required!');
       $(".ajax_error_msg").html("Discount Type is required!");
       $(".ajax_error_msg").show();
       setTimeout( function() {
        $(".ajax_error_msg").hide();
      },3500); 
        return false;
      }
      let saveButton = $('#discount_type_'+group_id).parent().parent().find('.btn-success');
      if(saveButton.length) {
        saveButton.html('Processing...');
        saveButton.attr('disabled',true);
      }
      var discount_value = $("input[name='discount_value_"+group_id+"[]']").map(function(){return $(this).val();}).get();
      var agent_group = $("input[name='agent_group_"+group_id+"[]']").map(function(){return $(this).val();}).get();
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route('admin.discount.add_custom_module_record_discount') }}",
        type: "POST",
        data: {module_name:module_name, module_record_id:module_record_id,group_id:group_id, discount_type:discount_type, agent_group:agent_group, discount_value:discount_value},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        async: true,
        beforeSend:function(){

        },
        success: function(resp){
            if(saveButton.length) {
                saveButton.html('Save');
                saveButton.attr('disabled',false);
            }
          if(resp.success){
            $(".ajax_success_msg").html(resp.message);
            $(".ajax_success_msg").show();
            setTimeout( function() {
              $(".ajax_success_msg").hide();
              window.location.reload();
            }, 700 );
          } else {
            $(".ajax_error_msg").html(resp.message);
            $(".ajax_error_msg").show();
            setTimeout( function() {
              $(".ajax_error_msg").hide();
            },3500);
          }
        }
      });
    }


</script>
@endslot

@endcomponent