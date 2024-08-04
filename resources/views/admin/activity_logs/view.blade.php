<div class="enq-view activity-log">
    <h2>{{$page_heading}}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="perdataSec">
                <ul>
                    <tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                    <tr>
                <td>
                    <div><b>User Name: </b></div>
                    <div>{{isset($activity_log->adminUser->name) ? $activity_log->adminUser->name : '' }}</div>
                </td>
                <td>
                    <div><b>User ID: </b></div>
                    <div>{{isset($activity_log->user_id) ? $activity_log->user_id : '' }}</div>
                </td>
                <td>
                    <div><b>Module: </b></div>
                    <div>{{isset($activity_log->module) ? $activity_log->module : '' }}
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Module Object ID: </b></div>
                    <div><?php if(!empty($activity_log->module_id)){?>
                        {{isset($activity_log->module_id) ? $activity_log->module_id : '' }}
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <div><b>Module Object Name: </b></div>
                    <div><?php if(!empty($activity_log->module_desc)){?>
                        {{isset($activity_log->module_desc) ? $activity_log->module_desc : '' }}
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <div><b>Sub Module: </b></div>
                    <div>
                        <?php if(!empty($activity_log->sub_module)){?>
                        {{isset($activity_log->sub_module) ? $activity_log->sub_module : '' }}
                        <?php } ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Submodule Object ID: </b></div>
                    <div><?php if(!empty($activity_log->sub_module_id)){?>
                        {{isset($activity_log->sub_module_id) ? $activity_log->sub_module_id : '' }}
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <div><b>Submodule Object Name: </b></div>
                    <div><?php if(!empty($activity_log->sub_module_desc)){?>
                        {{isset($activity_log->sub_module_desc) ? $activity_log->sub_module_desc : '' }}
                        <?php } ?></div>
                </td>

                <td>
                    <div><b>Created At: </b></div>
                    <div>{{CustomHelper::DateFormat($activity_log->created_at,'d M,Y h:i A')}}</div>
                </td>
            </tr>

             <tr>
                <td colspan="3">
                    <div><b>User Agent: </b></div>
                    <div><?php if(!empty($activity_log->user_agent)){?>
                        {{isset($activity_log->user_agent) ? $activity_log->user_agent : '' }}
                        <?php } ?>
                    </div>
                </td>
            </tr>

             <tr>
                <td>
                    <div><b>Action: </b></div>
                    <div>{{isset($activity_log->action) ? $activity_log->action : '' }}
                    </div>
                </td>
                 <td>
                    <div><b>IP Address: </b></div>
                    <div><?php if(!empty($activity_log->ip_address)){?>
                        {{isset($activity_log->ip_address) ? $activity_log->ip_address : '' }}
                        <?php } ?>
                    </div>
                </td>
            </tr>

            </table>

                </ul>
                <?php /* if(!empty($activity_log->data_after_action)){ 
                <div class="moretext">
                   
                        <h2>Updated Data</h2>
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Column Name</td>
                                    <td>Value</td>
                                </tr>
                                <?php $search_data = isset($activity_log->data_after_action) ? json_decode($activity_log->data_after_action,true) : [];
                                    if(!empty($search_data) && is_array($search_data)){
                                        foreach ($search_data as  $key =>$valueData) {
                                            if(!empty($valueData)){
                                                if(is_array($valueData)){
                                                $valueData = json_encode($valueData);
                                            }
                        
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$valueData}}</td>
                                </tr>
                                <?php 
                                        }   
                                    }
                                }
                            
                           
                            </tbody>
                        </table>
                            
                                        

                </div>
                <a class="moreless-button" href="javascript:void(0);" style="padding-left: 0px">View More</a> */ ?>
                <?php //} ?>
            </div>
        </div>
    </div>
    <div class="row activitylog">
        <div class="col-md-12">
            <h2>Updated Data Difference</h2>
            <?php if(!empty($diff)) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th>Column Name</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                        </tr>
                        <?php foreach($diff as $key => $value) { ?>
                        <tr>
                            <td>{{$key}}</td>
                            <td style="word-break: break-word;">{{ isset($old_data[$key])?$old_data[$key]:''}}</td>
                            <td style="word-break: break-word;">{{ isset($new_data[$key])?$new_data[$key]:'' }}</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
                <?php if(!empty($activity_log) && $activity_log->action == 'Delete'){ ?>
                <div class="alert alert-danger">Data was deleted.</div>
                <?php } else { ?>
                <div class="alert alert-danger">No changes found.</div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>


<script>
    $('.moreless-button').click(function() {
      $('.moretext').slideToggle();
      if ($('.moreless-button').text() == "View More") {
        $(this).text("View Less")
      } else {
        $(this).text("View More")
      }
    });
</script>