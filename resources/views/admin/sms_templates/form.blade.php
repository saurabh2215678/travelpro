@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('css/jquery-ui.css') }}"/ >
<link rel="stylesheet" type="text/css" href="{{ url('datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
@endslot
<?php
    $id = (isset($record->id))?$record->id:0;
    $slug = (isset($record->slug))?$record->slug:'';
    $title = (isset($record->title))?$record->title:'';
    $template_id = (isset($record->template_id))?$record->template_id:'';
    $content = (isset($record->content))?$record->content:'';
    $status = (isset($record->status))?$record->status:1;
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
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="title" class="control-label required">Title:</label>
                        <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" />
                        @include('snippets.errors_first', ['param' => 'title'])
                    </div>
                </div>

              <div class="col-md-6">
                <div class="form-group{{ $errors->has('template_id') ? ' has-error' : '' }}">
                    <label for="template_id" class="control-label required">Template id:</label>
                    <input type="text" class="form-control" name="template_id" value="{{ old('template_id', $template_id) }}" @if($id > 0) disabled @endif />
                    @include('snippets.errors_first', ['param' => 'template_id'])
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                    <label for="link_name" class="control-label">Content:</label>
                    <textarea id="content" class="form-control " name="content" @if($id > 0) disabled @endif>{{ old('content', $content) }}</textarea>
                    @include('snippets.errors_first', ['param' => 'content'])
                </div>
            </div>

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
                    <button type="submit" class="btn btn-success" title="Create this new sms templates"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
</form>
</div>
<div class="clearfix"></div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent