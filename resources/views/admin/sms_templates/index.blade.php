@component('admin.layouts.main')
@slot('title')
Admin - Manage Sms Template - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php 
$back_url=CustomHelper::BackUrl();
// $BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_title = app('request')->input('title');
$old_status = app('request')->input('status') ?? 1;
?>
<div class="top_title_admin">
    <div class="title">
        <h2>Manage SMS Templates ({{ $records->total()}})</h2>
    </div>
    <div class="add_button">
        @if (CustomHelper::checkPermission('sms_templates', 'add'))
            <a href="{{ route('admin.sms-templates.add', ['back_url'=>$back_url]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add SMS Template</a>
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
               <a href="{{url('admin/sms-templates')}}" class="btn_admin2">Reset</a>
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
                    <th>Status</th>
                    @if (CustomHelper::checkPermission('sms_templates', 'edit'))
                    <th>Action</th>
                    @endif    
                </tr>
                <?php foreach($records as $record){?>

                    <tr>
                        <td>{{$record->title}}</td>
                        <td><?php if($record->status == 1){ ?>
                            <span style="color:green">Active</span>
                                <?php }else{ ?><span style="color:red">Inactive</span><?php } ?>
                    </td>
                    @if (CustomHelper::checkPermission('sms_templates', 'edit'))
                        <td>
                            <a href="{{ route($routeName.'.sms-templates.edit', $record->id.'?back_url='.$back_url) }}" title="Edit Data"><i class="fas fa-edit"></i></a>
                        </td>
                    @endif    
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        {{ $records->appends(request()->query())->links() }}
    <?php
    }else{ ?>
        <div class="alert alert-warning">No record found.</div>
        <?php
    }
    ?>
</div>
@endcomponent