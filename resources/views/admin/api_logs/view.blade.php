<div class="enq-view view-role-permissions">
    <h2>{{$page_heading}}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="perdataSec">
                <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td>User/Agent</td>
                        <td>{{$api_log->User->name??''}}</td>
                    </tr>
                    <tr>
                        <td>API Name</td>
                        <td>{{$api_log->api_name??''}}</td>
                    </tr>
                    <tr>
                        <td>API URL</td>
                        <td>{{$api_log->api_url??''}}</td>
                    </tr>
                    <tr>
                        <td>Request</td>
                        <td><?php pr(json_decode($api_log->request_json,true)); ?></td>
                    </tr>
                    <tr>
                        <td>Response</td>
                        <td><?php pr(json_decode($api_log->response_json,true)); ?></td>
                    </tr>
                    <tr>
                        <td>IP Address</td>
                        <td>{{$api_log->ip_address??''}}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{CustomHelper::DateFormat($api_log->created_at,'d/m/y h:i A')??''}}</td>
                    </tr>                   
                </table>
            </div>
        </div>
    </div>
</div>