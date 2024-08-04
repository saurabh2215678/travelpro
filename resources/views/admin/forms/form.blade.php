@component('admin.layouts.main')
    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
        <link rel="stylesheet" type="text/css" href="{{ url('public/datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
    @endslot

    <?php
    $templates = CustomHelper::getTemplates('forms');
    $template = isset($form->template) ? $form->template : '';
    $id = isset($form->id) ? $form->id : '';
    $name = isset($form->name) ? $form->name : '';
    $thank_you_msg = isset($form->thank_you_msg) ? $form->thank_you_msg : '';
    $btnName = isset($form->btnName) ? $form->btnName : 'Submit';


    $captcha = isset($form->captcha) ? $form->captcha : 0;
    $is_reset = isset($form->is_reset) ? $form->is_reset : 0;
    $sendMail = isset($form->sendMail) ? $form->sendMail : 0;


    $status = isset($form->status) ? $form->status : 1;

    $thank_page_url = isset($form->thank_page_url) ? $form->thank_page_url : '';
    $to_email = isset($form->to_email) ? $form->to_email : '';
    $cc_email = isset($form->cc_email) ? $form->cc_email : '';
    $bcc_email = isset($form->bcc_email) ? $form->bcc_email : '';
    $subject = isset($form->subject) ? $form->subject : '';

    $email_template = isset($form->email_template)?$form->email_template:'';
   

    $storage = Storage::disk('public');
    $routeName = CustomHelper::getAdminRouteName();
    $replace_fields=array();
    //prd($form); 
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
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="control-label required">Name:</label>
                        <input type="text" id="name" data-validation="required length"
                            data-validation-length="3-128" class="form-control" name="name"
                            value="{{ old('name', $name) }}" />
                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('btnName') ? ' has-error' : '' }}">
                        <label for="btnName" class="control-label">Submit Button Name:</label>
                        <input type="text" id="btnName" class="form-control" name="btnName"
                            data-validation="required length" data-validation-length="3-128"
                            value="{{ old('btnName', $btnName) }}" />
                        @include('snippets.errors_first', ['param' => 'btnName'])
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('is_reset') ? ' has-error' : '' }}">
                        <label for="is_reset" class="control-label">Is Reset Button:</label>
                        <input type="checkbox" id="is_reset" class="is_reset" name="is_reset" value="1" <?php if($is_reset==1){ echo "checked";}?> />
                      
                    </div>
                </div>

                 <div class="col-md-3">
                    <div class="form-group{{ $errors->has('sendMail') ? ' has-error' : '' }}">
                        <label for="sendMail" class="control-label">Send Email:</label>
                        <input type="checkbox" id="sendMail" class="sendMail" name="sendMail" value="1" <?php if($sendMail==1){ echo "checked";}?> />
                      
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                        <label for="captcha" class="control-label">Verify Captcha:</label>
                        <input type="checkbox" id="captcha" class="captcha" name="captcha" value="1" <?php if($captcha==1){ echo "checked";}?> />
                      
                    </div>
                </div>
               
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                        <label class="control-label">Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="status" value="1" <?php echo $status == '1' ? 'checked' : ''; ?>>
                        &nbsp;
                        Inactive: <input type="radio" name="status" value="0" <?php echo strlen($status) > 0 && $status == '0' ? 'checked' : ''; ?>>
                        @include('snippets.errors_first', ['param' => 'status'])
                    </div>
                </div>


                 
            </div>

            <h2>Form Fields:</h2>

            <a href="javascript:void(0);" class="btn bluebg btn-info add_head_row btn_admin" style='float: right;'>
                <i class="fa fa-plus-circle"></i> Add Element
            </a>
            <div style="clear:both"></div>

            <?php $ii = 0; ?>
            <div class="input_box">
                @if (!empty($formElements) && count($formElements) > 0)
                    @foreach ($formElements as $fe)
                        @php
                            $showRemoveBtn = true;
                            if ($fe->is_static == '1') {
                                $showRemoveBtn = false;
                            }
                            $input_params = [];
                            $input_params['ii'] = $ii;
                            $input_params['element'] = $fe;
                            $input_params['form_attribute'] = $form_attribute;
                            $input_params['showRemoveBtn'] = $showRemoveBtn;

                            $replace_fields[]="[".str_replace(' ', '_', $fe->label).']'; 
                        @endphp
                        @include('admin.forms._input_row', $input_params)                        
                        <br><br><br>
                    @endforeach
                @endif

            </div>
            

            <h2>Email Fields:</h2>


            <div class="row">
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('thank_you_msg') ? ' has-error' : '' }}">
                        <label for="thank_you_msg" class="control-label">Thank You Message:</label>
                        <input type="text" id="thank_you_msg" class="form-control" name="thank_you_msg" value="{{ old('thank_you_msg', $thank_you_msg) }}" />
                        @include('snippets.errors_first', ['param' => 'thank_you_msg'])
                    </div>
                </div>
              
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('thank_page_url') ? ' has-error' : '' }}">
                        <label for="thank_page_url" class="control-label">Thank You Page Url:</label>
                        <input type="text" id="thank_page_url" class="form-control" name="thank_page_url" value="{{ old('thank_page_url', $thank_page_url) }}" />
                        @include('snippets.errors_first', ['param' => 'thank_page_url'])
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('to_email') ? ' has-error' : '' }}">
                        <label for="to_email" class="control-label">To Email:</label>
                        <input type="email" id="to_email" class="form-control" name="to_email"
                            value="{{ old('to_email', $to_email) }}" />
                        @include('snippets.errors_first', ['param' => 'to_email'])
                    </div>
                </div>

              
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('cc_email') ? ' has-error' : '' }}">
                        <label for="cc_email" class="control-label">CC Email:</label>
                        <input type="email" id="to_email" class="form-control" name="cc_email"
                            value="{{ old('cc_email', $cc_email) }}" />
                        @include('snippets.errors_first', ['param' => 'cc_email'])
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('bcc_email') ? ' has-error' : '' }}">
                        <label for="bcc_email" class="control-label">BCC Email:</label>
                        <input type="email" id="bcc_email" class="form-control" name="bcc_email"
                            value="{{ old('bcc_email', $bcc_email) }}" />
                        @include('snippets.errors_first', ['param' => 'bcc_email'])
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                        <label for="subject" class="control-label">Subject:</label>
                        <input type="text" id="subject" class="form-control" name="subject"
                            value="{{ old('subject', $subject) }}" />
                        @include('snippets.errors_first', ['param' => 'subject'])
                    </div>
                </div>

 <div class="col-md-12">
    <div class="form-group{{ $errors->has('email_template') ? ' has-error' : '' }}">
<label for="content" class="control-label">Replacment Fields:</label>
@if(!empty($replace_fields))
<ul>
@foreach($replace_fields as $fields)
<li>{{$fields}}</li>
@endforeach
</ul>
@endif

</div>
</div>

                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('email_template') ? ' has-error' : '' }}">
<label for="content" class="control-label">Email Template:</label>
            <textarea id="content" class="form-control" name="email_template">{{ old('email_template', $email_template) }}</textarea>
                        @include('snippets.errors_first', ['param' => 'email_template'])
                    </div>
                </div>
                
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <p></p>
                        <input type="hidden" id="id" class="form-control" name="id"
                            value="{{ old('id', $id) }}" />
                        <button type="submit" class="btn btn-success btn_admin" title="Submit"><i
                            class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>

        </form>
        <div class="clearfix"></div>
    </div>
                        </div>
@slot('bottomBlock')
<script type="text/javascript" src="{{ url('js/ckeditor/ckeditor.js') }}"></script>
<script>
    // CKEDITOR.replace( 'content',{
    //     height:350,
    // });

    $(document).ready(function(){
        var editor = CKEDITOR.replace('content', {
            height:350,
            filebrowserImageBrowseUrl: "{{url('public/storage/products/ck')}}",
            filebrowserImageUploadUrl: "<?php echo url('admin/ck_upload?type=email_templates&csrf_token='.csrf_token());?>"
        });
    });
</script>
@endslot
@endcomponent

<?php
$pattern = '/\n*/m';
$replace = '';
$input_params = [];
$input_params['ii'] = $ii;
$input_params['form_attribute'] = $form_attribute;
$input_params['showRemoveBtn'] = true;
$input_row_html = view('admin.forms._input_row', $input_params)->render();
$input_row = preg_replace($pattern, $replace, $input_row_html);
?>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
    $.validate();
    $(".add_head_row").click(function() {
        var rowBox_len = $(".rowBox").length;
        if (rowBox_len + 1 > 20) {
            alert('Maximum 20 Attributes are allowed.');
        } else {
            var input_row = '<?php echo $input_row; ?>';
            $(".input_box").append(input_row);
        }
    });

    $(document).on("click", ".remove_head_row", function() {
        var currSelector = $(this);
        var form_element_id = currSelector.parents(".rowBox").find("input[name='form_element_ids[]']").val();
        form_element_id = parseInt(form_element_id);
        if (!isNaN(form_element_id) && form_element_id > 0) {
            var conf = confirm("Are you sure to delete this record permanently?");
            if (!conf) {
                return false;
            }
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName . '.forms.ajax_delete_element') }}",
                type: "POST",
                data: {
                    form_element_id: form_element_id
                },
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                cache: false,
                beforeSend: function() {},
                success: function(resp) {
                    if (resp.success) {
                        currSelector.parents(".rowBox").remove();
                    }
                }
            });
        } else {
            currSelector.parents(".rowBox").remove();
        }
    });

    $(document).ready(function() {
        $(".rowBox").each(function(index) {
            var boxType = $(this).find(".field_type option:selected").val();
            if (boxType == 'checkbox' || boxType == 'select' || boxType == 'radio') {
                $(this).find('.dataBox').show();
            } else {
                $(this).find('.dataBox').hide();
            }
        });
    });


    $(document).on('click','.is_display',function(){
        if($(this).prop('checked') == true){
          //  alert( $(this).parent().parent().html());
          $(this).parent().parent().parent().find('.is_display').val(1);
        }else{
            $(this).parent().parent().parent().find('.is_display').val(0);
        }
    });
    $(document).on('click','.is_hide',function(){
        if($(this).prop('checked') == true){
          //  alert( $(this).parent().parent().html());
          $(this).parent().parent().parent().find('.is_hide').val(1);
        }else{
            $(this).parent().parent().parent().find('.is_hide').val(0);
        }
    });
</script>
