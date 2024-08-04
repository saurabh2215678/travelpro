@component('admin.layouts.main')
@slot('title')
Admin - Manage Email Template - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php 
$back_url=CustomHelper::BackUrl();
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_title = app('request')->input('title');
$old_status = app('request')->input('status') ?? 1;
?>
<div class="top_title_admin">
    <div class="title">
        <h2>Manage Email Templates ({{ $records->total()}})</h2>
    </div>
    <div class="add_button">
        @if (CustomHelper::checkPermission('email_templates', 'add'))
        <a href="{{ route('admin.email-templates.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Email Template</a>
        @endif    
    </div>
</div>
<div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-3">
                    <label class="control-label">Title:</label><br/>
                    <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}" placeholder="Search Title">
                </div>
                <div class="col-md-3">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-5">
                 <label></label><br/>
                 <button type="submit" class="btn btn-success">Search</button>
                 <a href="{{url('admin/email-templates')}}" class="btn_admin2">Reset</a>
             </div>
         </form>
     </div>
 </div>
 @include('snippets.errors')
 @include('snippets.flash')
 <?php
 if(!empty($records) && count($records) > 0){ ?>
     <div class="table-responsive">           
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Bcc <br>Admin</th>
                <th>Bcc <br>Manager</th>
                <th>Bcc <br>Vendor <br>Contact</th>
                <th>Subject</th>
                <th>Status</th>
                @if (CustomHelper::checkPermission('email_templates', 'edit'))
                <th>Action</th>
                @endif    
            </tr>
            <?php foreach($records as $record){?>

                <tr>
                    <td>{{$record->title}}</td>
                    <td>{{$record->email_type ? $record->email_type :'customer'}}</td>
                    <td><?php if($record->bcc_admin == 1){ ?>
                        <i class="fas fa-check" style="color:green"></i>
                        <?php } ?></td> 

                        <td><?php if($record->manager_email == 1){ ?>
                        <i class="fas fa-check" style="color:green"></i>
                        <?php } ?></td>

                         <td><?php if($record->contact_email == 1){ ?>
                        <i class="fas fa-check" style="color:green"></i>
                        <?php } ?></td>
                        
                        <td>{{$record->subject}}</td>
                        <td><?php if($record->status == 1){ ?>
                            <span style="color:green">Active</span>
                            <?php   }else{  ?><span style="color:red">Inactive</span>
                        <?php } ?>
                    </td>
                    @if (CustomHelper::checkPermission('email_templates', 'edit'))
                    <td>
                        <a href="{{ route($routeName.'.email-templates.edit', $record->id.'?back_url='.$back_url) }}" title="Edit Data"><i class="fas fa-edit"></i></a>
                    </td>
                    @endif    
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    {{ $records->appends(request()->query())->links('vendor.pagination.default') }}
    <?php
}else{ ?>
    <div class="alert alert-warning">No record found.</div>
    <?php
}
?>
</div>
@endcomponent