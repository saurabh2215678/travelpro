<?php 
$id = '';
$name = '';
$status = '';
$routeName = 'admin';
$back_url = '';
$seo_module_config_arr = config('custom.seo_module_config_arr');
?>           
<?php if(!empty($discount_groups) && count($discount_groups) > 0) { ?>
<div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th>Module Name</th>
            <th>Discount Category</th>
            <th>Discount type</th>
            <?php if(isset($custom_discount_section) && !empty($custom_discount_section)){ }else{ ?>
            <th>Set Default <span class="ihover">(i) <div class="hover_text">(If discount category is not selected for a package, the default selected discount category will applied to that package.)</div></span></th>
            <?php } ?>
            <th>B2C</th>
            <?php foreach ($agent_groups as $key => $agent_group) { ?>
                <th>B2B ({{$agent_group->name??''}})</th>
            <?php } ?>
            <th>Action</th>
        </tr>
        <?php  
        foreach($discount_groups as $group){
            $old_discount_type = '';
            $old_is_default = 0;
            $old_agent_group_ids = [];
            if(isset($discount_module_groups) && !empty($discount_module_groups)) {
                foreach ($discount_module_groups as $key => $discount_module_group) {
                    if($discount_module_group->module_discount_type_id == $group->id){
                        $old_discount_type = $discount_module_group->discount_type ?? '';
                        $old_is_default = $discount_module_group->is_default ?? '';
                        $old_agent_group_ids[$discount_module_group->agent_group_id] = $discount_module_group->discount_value;
                    }
                }
            }
        ?>
        <tr>
            <td>{{$seo_module_config_arr[$module_name]}}</td>
            <td><?php echo $group->name; ?></td>
            <td>
                <select class="form-control select2" name="group_id" id="discount_type_{{$group->id}}">
                    <option value="">Select</option>
                    <option value="flat" {{($old_discount_type == 'flat')? 'selected':''}}>Flat</option>
                    <option value="percentage" {{($old_discount_type == 'percentage')? 'selected':''}}>Percentage</option> 
                </select>
            </td>
            <?php if(isset($custom_discount_section) && !empty($custom_discount_section)){ }else{ ?>
            <td>
                <input type="radio" name="default" id="default_{{$group->id}}" {{($old_is_default == 0)? '':'checked'}} class="form-control admin_input1" value="">
            </td>
            <?php } ?>
            <td>
                <?php
                $agent_group_id = '-1';
                $old_discount_value = '';
                if(isset($old_agent_group_ids[$agent_group_id])){
                    $old_discount_value = $old_agent_group_ids[$agent_group_id];
                }
                ?>
                <input type="hidden" name="agent_group_{{$group->id}}[]" value="{{$agent_group_id}}" class="form-control admin_input1" >
                <input type="text" name="discount_value_{{$group->id}}[]" value="{{$old_discount_value}}"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" class="form-control admin_input1" value="">
            </td>
            <?php foreach ($agent_groups as $key => $agent_group) {
                $agent_group_id = $agent_group->id;
                $old_discount_value = '';
                if(isset($old_agent_group_ids[$agent_group_id])){
                    $old_discount_value = $old_agent_group_ids[$agent_group_id];
                }
                ?>
                <td>
                    <input type="hidden" name="agent_group_{{$group->id}}[]" value="{{$agent_group_id}}" class="form-control admin_input1" >
                    <input type="text" name="discount_value_{{$group->id}}[]" value="{{$old_discount_value}}"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  class="form-control admin_input1" >
                </td>
            <?php } ?>
            <td>
                <button class="btn btn-success" name="discount_save" value="{{$group->id}}" onclick="save_discoount('{{$group->id}}')">Save</button>
            </td>     
        </tr>
        <?php } ?>
    </table>
</div>
<?php } ?>