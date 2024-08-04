@component('admin.layouts.main')

@slot('title')
Admin - Manage Email Settings - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>
.heightform .form-control {
    max-width: 100%;
    width: 100%
}

textarea {
    width: 100%
}
</style>
@endslot

<div class="row p20">
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if (request()->has('back_url')) {$back_url = request('back_url');?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                Back</a><?php } ?>
        </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if (session()->has('alert-' . $msg))
                <div class="alert alert-{{ $msg }}">
                    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! session('alert-' . $msg) !!}
                </div>
                @endif
                @endforeach
            </div>

            <!-- @include('snippets.errors') -->
            <!-- @include('snippets.flash') -->

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" id="id" class="form-control" name="id" value="{{old('id',$smtp_data->id)}}" />

                <div class="form-group col-md-6{{$errors->has('mail_gateway')?' has-error':''}}">
                    <label for="mail_gateway" class="control-label required">Active Gateway:</label>
                    <input type="radio" id="SMTP" class="form-control" name="mail_gateway" value="smtp"
                        @if(old('mail_gateway',$smtp_data->mail_gateway) == 'smtp') checked @endif/> SMTP
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" id="PHPMAIL" class="form-control" name="mail_gateway" value="phpmail"
                        @if(old('mail_gateway',$smtp_data->mail_gateway) == 'phpmail') checked @endif /> PHP Mail
                    @include('snippets.errors_first', ['param' => 'mail_gateway'])
                </div>
                
                <div class="form-group col-md-6{{$errors->has('is_queue')?' has-error':''}}">
                    <label for="is_queue" class="control-label">Send email using Queue:</label>
                    <input type="checkbox" id="is_queue" class="form-control" name="is_queue" value="1" 
                    @if(old('is_queue',$smtp_data->is_queue) == '1') checked @endif />
                    @include('snippets.errors_first', ['param' => 'is_queue'])
                </div><br><br>
              
                <div id="PHPMailDiv">
                    <div class="form-group col-md-6{{$errors->has('sender_name')?' has-error':''}}">
                        <label for="sender_name" class="control-label required">Sender Name :</label>
                        <input type="text" id="sender_name" class="form-control" name="sender_name"
                            value="{{old('sender_name',$smtp_data->sender_name)}}" />
                        @include('snippets.errors_first', ['param' => 'sender_name'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('sender_mail')?' has-error':''}}">
                        <label for="sender_mail" class="control-label required">Sender Email :</label>
                        <input type="text" id="sender_mail" class="form-control" name="sender_mail"
                            value="{{old('sender_mail',$smtp_data->sender_mail)}}" />
                        @include('snippets.errors_first', ['param' => 'sender_mail'])
                    </div>
                </div>

                <br>
                <div id="SMTPMailDiv">
                    <div class="form-group col-md-6{{$errors->has('from_name')?' has-error':''}}">
                        <label for="from_name" class="control-label required">From Name :</label>
                        <input type="text" id="from_name" class="form-control" name="from_name"
                            value="{{old('from_name',$smtp_data->from_name)}}" />
                        @include('snippets.errors_first', ['param' => 'from_name'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('from_mail')?' has-error':''}}">
                        <label for="from_mail" class="control-label required">From Mail :</label>
                        <input type="text" id="from_mail" class="form-control" name="from_mail"
                            value="{{old('from_mail',$smtp_data->from_mail)}}" />
                        @include('snippets.errors_first', ['param' => 'from_mail'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('mail_host')?' has-error':''}}">
                        <label for="mail_host" class="control-label required">Mail Host :</label>
                        <input type="text" id="mail_host" class="form-control" name="mail_host"
                            value="{{old('mail_host',$smtp_data->mail_host)}}" />
                        @include('snippets.errors_first', ['param' => 'mail_host'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('mail_port')?' has-error':''}}">
                        <label for="mail_port" class="control-label required">Mail Port :</label>
                        <input type="text" id="mail_port" class="form-control" name="mail_port"
                            value="{{old('mail_port',$smtp_data->mail_port)}}" />
                        @include('snippets.errors_first', ['param' => 'mail_port'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('mail_username')?' has-error':''}}">
                        <label for="mail_username" class="control-label required">Mail Username :</label>
                        <input type="text" id="mail_username" class="form-control" name="mail_username"
                            value="{{old('mail_username',$smtp_data->mail_username)}}" />
                        @include('snippets.errors_first', ['param' => 'mail_username'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('mail_password')?' has-error':''}}">
                        <label for="mail_password" class="control-label">Mail Password :</label>
                        <input type="password" id="mail_password" class="form-control" name="mail_password" value="" />
                        @include('snippets.errors_first', ['param' => 'mail_password'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('mail_encryption')?' has-error':''}}">
                        <label for="mail_encryption" class="control-label">Mail Encryption :</label>
                        <select class="form-control select2" name="mail_encryption" id="mail_encryption">
                            <option value="">--Select--</option>
                            <option value="ssl"
                                {{(old('mail_encryption',$smtp_data->mail_encryption) == 'ssl') ? 'selected' : '' }}>SSL
                            </option>
                            <option value="tls"
                                {{(old('mail_encryption',$smtp_data->mail_encryption) == 'tls') ? 'selected' : '' }}>TLS
                            </option>
                        </select>
                        @include('snippets.errors_first', ['param' => 'mail_encryption'])
                    </div>

                    <div class="form-group col-md-6{{$errors->has('email_charset')?' has-error':''}}">
                        <label for="email_charset" class="control-label">Email Charset :</label>
                        <input type="text" id="email_charset" class="form-control" name="email_charset"
                            value="{{old('email_charset',$smtp_data->email_charset)}}" />
                        @include('snippets.errors_first', ['param' => 'email_charset'])
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <p></p>
                        <button type="submit" class="btn btn-success" title="Save"><i class="fa fa-save"></i>
                            Submit</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="top_title_admin">
                <div class="centersec">
                    <div class="bgcolor">
                        <form method="POST" action="{{route('admin.settings.mailsmtp')}}" accept-charset="UTF-8"
                            role="form" enctype="multipart/form-data" id="sendMail">
                            {{ csrf_field() }}

                            <div class="form-group col-md-6{{$errors->has('email_to')?' has-error':''}}">
                                <label for="email_to" class="control-label required">Send a test email to :</label>
                                <input type="text" id="email_to" class="form-control" name="email_to"
                                    value="{{old('email_to')}}" />
                                @include('snippets.errors_first', ['param' => 'email_to'])
                            </div>

                            <div class="form-group col-md-6{{$errors->has('mail_text')?' has-error':''}}">
                                <label for="mail_text" class="control-label required">Mail text :</label>
                                <input type="text" id="mail_text" class="form-control" name="mail_text"
                                    value="{{old('mail_text')}}" />
                                @include('snippets.errors_first', ['param' => 'mail_text'])
                            </div>

                            <div class="col-md-12 text-center">
                                <div class="form-group">
                                    <p></p>
                                    <button type="submit" class="btn btn-success" title="Send mail" id="sendMailBtn"><i
                                            class="fa fa-envelope"></i> Send test mail</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @slot('bottomBlock')

    <script>
    var id = $('input[name="mail_gateway"]:checked').attr('id');

    if (id == "SMTP") {
        $('#SMTPMailDiv').show();
        $('#PHPMailDiv').hide();
    } else if (id == "PHPMAIL") {
        $('#PHPMailDiv').show();
        $('#SMTPMailDiv').hide();
    }

    $('#SMTP').click(function() {
        $('#SMTPMailDiv').show();
        $('#PHPMailDiv').hide();
    });

    $('#PHPMAIL').click(function() {
        $('#PHPMailDiv').show();
        $('#SMTPMailDiv').hide();
    });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script>
    jQuery.support.cors = true;
    if ($("#sendMail").length > 0) {
        $("#sendMail").validate({
            submitHandler: function(form) {
                $("#sendMailBtn").html(
                    'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
                );
                $("#sendMailBtn").attr("disabled", true);
                form.submit();
            }
        })
    }
    </script>

    @endslot


    @endcomponent