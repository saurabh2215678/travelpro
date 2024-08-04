@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>.heightform .form-control {max-width: 100%;width:100%}textarea{width:100%}</style>
@endslot
<?php
// $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
<div class="row p20">
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                Back</a><?php } ?>
            </div>
        </div>
        @include('snippets.errors')
        @include('snippets.flash')
        <?php 
        $B2B_HEADER ='';
        $B2B_FOOTER ='';
        $B2C_HEADER ='';
        $B2C_FOOTER ='';
        $new_array =[];
        array_push($new_array,'B2B_HEADER','B2B_FOOTER','B2C_HEADER','B2C_FOOTER');
        foreach ($settings as $key => $setting) {
            foreach ($new_array as $name) {
                if($setting->name == $name){
                    $$name = $setting->value;
                }
            }
        } ?>
        <div class="centersec">
            <div class="bgcolor p10">
              <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if(CustomHelper::isAllowedModule('agentuser'))
                <?php //<h4><span style="color:#b55603;">For B2B:</span></h4> ?>
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label for="title" class="control-label"><h3>B2B_HEADER </h3><span style="color:#b55603;">Variables: {agent_logo},{agent_name},{agent_phone},{agent_email}</span> :</label>

                        <textarea id="B2B_HEADER" class="form-control ckeditor" name="B2B_HEADER">{{ old('B2B_HEADER', $B2B_HEADER) }}</textarea>

                        @include('snippets.errors_first', ['param' => 'B2B_HEADER'])
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label for="title" class="control-label"><h3>B2C_HEADER </h3><span style="color:#b55603;">Variables: {company_logo},{company_name},{sales_phone},{sales_email}</span> :</label>

                        <textarea id="B2C_HEADER" class="form-control ckeditor" name="B2C_HEADER">{{ old('B2C_HEADER', $B2C_HEADER) }}</textarea>

                        @include('snippets.errors_first', ['param' => 'B2C_HEADER'])
                    </div>
                </div>

                @if(CustomHelper::isAllowedModule('agentuser'))
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label for="title" class="control-label"><h3>B2B_FOOTER </h3><span style="color:#b55603;">Variables: {agent_name},{agent_phone},{agent_email}</span> :</label>

                        <textarea id="B2B_FOOTER" class="form-control ckeditor" name="B2B_FOOTER">{{ old('B2B_FOOTER', $B2B_FOOTER) }}</textarea>

                        @include('snippets.errors_first', ['param' => 'B2B_FOOTER'])
                    </div>
                </div>
                @endif
                
                <?php //<h4><span style="color:#b55603;">For B2C:</span></h4> ?>    
                 
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        <label for="title" class="control-label"><h3>B2C_FOOTER </h3><span style="color:#b55603;">Variables: {company_name},{sales_phone},{sales_mobile},{sales_email},{company_title}</span> :</label>

                        <textarea id="B2C_FOOTER" class="form-control ckeditor" name="B2C_FOOTER">{{ old('B2C_FOOTER', $B2C_FOOTER) }}</textarea>

                        @include('snippets.errors_first', ['param' => 'B2C_FOOTER'])
                    </div>
                </div>                 
                <div class="col-md-12">
                    <div class="form-group">
                        <p></p>
                        <button type="submit" class="btn btn-success" title="Create this new package"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@slot('bottomBlock')
<script type="text/javascript" src="{{ url('js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    //$(document).ready(function(){
    var B2B_HEADER = document.getElementById('B2B_HEADER');
    CKEDITOR.replace(B2B_HEADER);

    var B2B_FOOTER = document.getElementById('B2B_FOOTER');
    CKEDITOR.replace(B2B_FOOTER);

    var B2C_HEADER = document.getElementById('B2C_HEADER');
    CKEDITOR.replace(B2C_HEADER);

    var B2C_FOOTER = document.getElementById('B2C_FOOTER');
    CKEDITOR.replace(B2C_FOOTER);
    //});
</script>
@endslot
@endcomponent 