@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('css/jquery-ui.css') }}"/ >
<link rel="stylesheet" type="text/css" href="{{ url('datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
@endslot
<style>
    .param_list { height: 300px; overflow-y: scroll; }
    .param_list .table > tbody > tr > td { padding: 6px; font-size: 12px; }
    .param_list .table > tbody > tr > td div { line-height: normal; }
</style>
<?php
$id = (isset($record->id))?$record->id:'';
$slug = (isset($record->slug))?$record->slug:'';
$title = (isset($record->title))?$record->title:'';
$subject = (isset($record->subject))?$record->subject:'';
$content = (isset($record->content))?$record->content:'';
$status = (isset($record->status))?$record->status:1;
$email_type = (isset($record->email_type))?$record->email_type:'';
$bcc_admin = (isset($record->bcc_admin))?$record->bcc_admin:0;
$manager_email = (isset($record->manager_email))?$record->manager_email:0;
$contact_email = (isset($record->contact_email))?$record->contact_email:0;

$storage = Storage::disk('public');
$path = 'email_templates/';
$old_image = 0;
$image_req = 'required';
$link_req = '';
?>

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
        <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a><?php } ?>
    </div>
</div>

<div class="centersec">
    <div class="bgcolor p10">

        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="title" class="control-label required">Title:</label>
                        <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" required  />
                        @include('snippets.errors_first', ['param' => 'title'])
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('email_type') ? ' has-error' : '' }}">
                        <label for="email_type" class="control-label required">Email Type:</label>
                        <!-- <input type="text" class="form-control" name="email_type" value="{{ old('email_type', $email_type) }}" required  /> -->
                        <select class="form-control" name="email_type" >
                            <option value="">Select</option>
                            <option value="customer" {{($email_type=='customer')?'selected':''}} >Customer</option>
                            <option value="admin" {{($email_type=='admin')?'selected':''}} >Admin</option>
                        </select>
                        @include('snippets.errors_first', ['param' => 'email_type'])
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group{{ $errors->has('bcc_admin') ? ' has-error' : '' }}">
                      <label for="bcc_admin" class="control-label ">bcc Admin:</label><br>
                      <input class="form-control" type="checkbox" name="bcc_admin" id="bcc_admin" value="1" <?php echo ($bcc_admin == '1')?'checked':''; ?> > Yes
                      @include('snippets.errors_first', ['param' => 'bcc_admin'])
                  </div>
              </div>

                <div class="col-md-2">
                    <div class="form-group{{ $errors->has('manager_email') ? ' has-error' : '' }}">
                      <label for="manager_email" class="control-label ">bcc Manager: <span  class="d-inline-block" tabindex="0" data-toggle="tooltip" title="The user managing the particular product/service"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><br>
                      <input class="form-control" type="checkbox" name="manager_email" id="manager_email" value="1" <?php echo ($manager_email == '1')?'checked':''; ?> > Yes
                      @include('snippets.errors_first', ['param' => 'manager_email'])
                  </div>
              </div>

                <div class="col-md-2">
                    <div class="form-group{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                      <label for="contact_email" class="control-label ">bcc Vendor Contact: <span  class="d-inline-block" tabindex="0" data-toggle="tooltip" title=" The user responsible for supporting the customer on behalf of vendor"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label><br>
                      <input class="form-control" type="checkbox" name="contact_email" id="contact_email" value="1" <?php echo ($contact_email == '1')?'checked':''; ?> > Yes
                      @include('snippets.errors_first', ['param' => 'contact_email'])
                  </div>
              </div>

              <div class="col-md-12">
                <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                    <label for="subject" class="control-label required">Subject:</label>

                    <input type="text" class="form-control" name="subject" value="{{ old('subject', $subject) }}" required  />

                    @include('snippets.errors_first', ['param' => 'subject'])
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                    <label for="link_name" class="control-label">Content:</label>

                    <textarea id="content" class="form-control " name="content">{{ old('content', $content) }}</textarea>

                    @include('snippets.errors_first', ['param' => 'content'])
                </div>
            </div>

            @if(isset($slug) && !empty($slug))
            <div class="param_list col-md-12">
                @include('admin.email_templates.parameter_use')
            </div>
            @endif

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <p></p>
                    <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                    <button type="submit" class="btn btn-success" title="Create this new email templates"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
</form>
</div>
<div class="clearfix"></div>
</div>
@slot('bottomBlock')
<script type="text/javascript" src="{{ url('js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
    //var editor = CKEDITOR.replace('content');
        var editor = CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: "{{url('public/storage/products/ck')}}",
            filebrowserImageUploadUrl: "<?php echo url('admin/ck_upload?type=email_templates&csrf_token='.csrf_token());?>"
        });
    });
</script>
@endslot
@endcomponent