@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<style type="text/css">
.theme_footer:before {z-index: -1;}
.booking-lists thead tr th {
    border-right: 1px solid #dee2e6;
    background: var(--theme-color);
    color: #fff;
    text-align: left;
    font-size: 12px;
    line-height: normal;
}
.btn.s-btn {display: initial; background: #01b3a7; color: #ffffff !important; width:auto;cursor: pointer; border-color: #01b3a7; padding: 0.3rem 1rem;}
.btn.s-btn:hover {background: #282658;border-color: #282658; color: #ffffff !important;}
.btn2.edit_pofile_btn {font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
.user_profile_inner .right_info .top_info {padding-bottom: 10px;}
/*.gst_Form{display: none !important;}
.gst_Form.show{display: block !important;}*/
</style>

@endslot
<?php 
// prd($gstQuery);
$id = !empty($gst->id)?$gst->id:'';
$id = old('id',$id);

$gst_number = !empty($gst->gst_number)?$gst->gst_number:'';
$gst_number = old('gst_number',$gst_number);

$gst_company = !empty($gst->gst_company)?$gst->gst_company:'';
$gst_company = old('gst_company',$gst_company);

$gst_email = !empty($gst->gst_email)?$gst->gst_email:'';
$gst_email = old('gst_email',$gst_email);

$gst_phone = !empty($gst->gst_phone)?$gst->gst_phone:'';
$gst_phone = old('gst_phone',$gst_phone);

$gst_address = !empty($gst->gst_address)?$gst->gst_address:'';
$gst_address = old('gst_address',$gst_address);
?>
<section>
        <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info pt-1">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">{{ $page_heading ?? ''}}</h1>
                        </div>
                    </div>
                </div>
                  
                    <div class="top_info gst_Form pt-2">
                    <div class="left w-full">
                        <div class="theme_title">
                            <!-- <h3 class="title">Add Amount</h3> -->
                        </div>
                       @include('snippets.front.flash')
                        {{ Form::open(array('url' => 'user/gst/add','method' => 'post','class' => 'form','id' => 'gst_form','files'=>true,'autocomplete' => 'off','enctype'=>'multipart/form-data')) }} 

                        <input type="hidden" name="id" value="{{$id}}">  
                        <div class="w-full flex">
                         <div class="w-full dflex flex-wrap">
                            <div class="mb-1 p-2 pb-0 w-2/6">
                                <label for="city_id">GST Number<em>*</em></label>
                                <input type="text" class="form-control" name="gst_number" value="{{$gst_number}}">
                                <sapn class="validation_error">@include('snippets.errors_first', ['param' => 'gst_number'])</sapn>
                            </div>

                            <div class="mb-1 p-2 pb-0 w-2/6">
                                <label for="city_id">GST Company</label>
                                <input type="text" class="form-control" name="gst_company" value="{{$gst_company}}">
                            </div>

                            <div class="mb-1 p-2 pb-0 w-2/6">
                                <label for="city_id">Email<em>*</em></label>
                                <input type="text" class="form-control" name="gst_email" value="{{$gst_email}}">
                                <sapn class="validation_error"> @include('snippets.errors_first', ['param' => 'gst_email'])</span>
                            </div>

                             <div class="mb-1 p-2 pb-0 w-2/6">
                                <label for="city_id">Phone<em>*</em></label>
                                <input type="text" class="form-control" name="gst_phone" value="{{$gst_phone}}">
                                <sapn class="validation_error">@include('snippets.errors_first', ['param' => 'gst_phone'])</span>
                            </div>

                            <div class="mb-1 p-2 pb-0 w-2/6">
                                <label for="city_id">Address</label>
                                <input type="text" class="form-control" name="gst_address" value="{{$gst_address}}">
                            </div>

                            <div class="mb-1 p-2 pb-0 w-2/6">
                                <label>&nbsp</label>
                                <div class="mb-1">
                                   <button type="submit" class="btn s-btn rounded-full">Submit</button>
                                    <a href="{{ url('user/gst') }}" class="btn resetbtn btn-primary cancel-btn" title="Cancel" style="display:inline-block;">Cancel</a>
                                   
                               </div>
                           </div>

                        </div>
                     
                        
                   </div>

               {{ Form::close() }}
                    </div>

                </div>
                

                
            </div>
          </div>
        </div>
    </section>
@slot('bottomBlock') 


@endslot
@endcomponent