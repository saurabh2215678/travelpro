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
                                        <div>{{isset($login_history->user_name) ? $login_history->user_name : '' }}</div>
                                    </td>
                                    <td>
                                        <div><b>User ID: </b></div>
                                        <div>{{isset($login_history->user_id) ? $login_history->user_id : '' }}</div>
                                    </td>
                                    <td>
                                        <div><b>IP Address: </b></div>
                                        <div><?php 
                                        if(!empty($login_history->ip_address)){?>
                                            {{isset($login_history->ip_address) ? $login_history->ip_address : '' }}
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div><b>Operating System: </b></div>
                                    <div><?php 
                                    if(!empty($login_history->os_details)){?>
                                        {{isset($login_history->os_details) ? $login_history->os_details : '' }}
                                    <?php } ?>
                                </div>
                            </td>
                            <td>
                                <div><b>Browser: </b></div>
                                <div><?php if(!empty($login_history->browser_details)){?>
                                    {{isset($login_history->browser_details) ? $login_history->browser_details : '' }}
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <div><b>Login Time: </b></div>
                            <div><?php echo $added_on = CustomHelper::DateFormat($login_history->created_at);?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div><b>Action: </b></div>
                            <div>{{isset($login_history->action) ? $login_history->action : '' }}
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
    <?php /*<div class="row activitylog">
        <div class="col-md-12">
            <h2>Updated Data Difference</h2>
            <?php if(!empty($diff)) { 
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th>Column Name</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                        </tr>
                        <?php foreach($diff as $key => $value) { 
                        <tr>
                            <td>{{$key}}</td>
                            <td style="word-break: break-word;">{{ isset($old_data[$key])?$old_data[$key]:''}}</td>
                            <td style="word-break: break-word;">{{ isset($new_data[$key])?$new_data[$key]:'' }}</td>
                        </tr>
                        <?php } 
                    </tbody>
                </table>
            </div>
            <?php } else { 
                <?php if(!empty($activity_log) && $activity_log->action == 'Delete'){ 
                <div class="alert alert-danger">Data was deleted.</div>
                <?php } else { 
                <div class="alert alert-danger">No changes found.</div>
                <?php } 
            <?php } 
        </div>
    </div>
    </div> */ ?>

<!-- <script>
    $('.moreless-button').click(function() {
      $('.moretext').slideToggle();
      if ($('.moreless-button').text() == "View More") {
        $(this).text("View Less")
      } else {
        $(this).text("View More")
      }
    });
</script> -->