@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $page_heading ?? ''}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
.btn2.edit_pofile_btn { font-size: 14px; padding: 0.3rem 1rem; text-transform: none; }
.user_profile_inner .right_info .top_info {padding-bottom: 10px;}
/*.gst_Form{display: none !important;}
.gst_Form.show{display: block !important;}*/
</style>

@endslot
<?php
$gst_number = (request()->has('gst_number'))?request()->gst_number:'';
$old_gst_number = old('gst_number',$gst_number);

$gst_phone = (request()->has('gst_phone'))?request()->gst_phone:'';
$old_gst_phone = old('gst_phone',$gst_phone);

$gst_email = (request()->has('gst_email'))?request()->gst_email:'';
$old_gst_email = old('gst_email',$gst_email);
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

                    <div class="ihr">
                        <a href="{{ url('user/gst/add') }}" class="btn s-btn gst_btn"><i class="fa fa-plus"></i> Add GST</a>
                    </div>
                </div>
                <form action="" method="GET" class="mt-1">
                    <div class="row">
                        <div class="w-1/4">
                            <label for="city_id">GST Number</label>
                            <input type="text" class="form-control" name="gst_number" value="{{$old_gst_number}}">
                        </div>

                        <div class="w-1/4">
                            <label for="city_id">Phone</label>
                            <input type="text" class="form-control" name="gst_phone" value="{{$old_gst_phone}}">
                        </div>

                        <div class="w-1/4">
                            <label for="city_id">Email</label>
                            <input type="text" class="form-control" name="gst_email" value="{{$old_gst_email}}">
                        </div>
                        <div class="w-1/4">
                        <label>&nbsp</label>
                        <div class="mb-3">
                           <button type="submit" class="btn s-btn rounded-full">Search</button>
                           <a class="btn2 edit_pofile_btn" href="{{url('user/gst')}}">Reset</a>
                       </div>
                   </div>
                    
               </div>
            </form>
                @include('snippets.front.flash')
                <div class="wallet-wrapper mt-5">
                    <?php
                    if(!empty($gstQueries) && count($gstQueries) > 0){
                        ?>
                <div class="table-responsive">
                    <table id="" class="table adults table-striped table-hover dataTable no-footer">
                        <thead>
                            <tr role="row">
                            <th>GST No</th>
                            <th>GST Company</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                         <tbody>
                            <?php 
                            // prd($gst);
                            foreach ($gstQueries as $gst){ ?> 
                           <tr>
                               <td>{{$gst->gst_number??''}}</td>
                               <td>{{$gst->gst_company??''}}</td>
                               <td>{{$gst->gst_phone??''}}</td>
                               <td>{{$gst->gst_email??''}}</td>
                               <td>
                                {{$gst->gst_address??''}}
                               </td>
                               <td>
                                <a href="{{ url('user/gst/edit', $gst->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                               </td>
                           </tr>
                           <?php } ?>
                        </tbody>
                    </table>
                <?php }else{
                    ?>
                    <div class="alert alert-warning">There are no GST Info at the present.</div>
                    <?php
                }
                ?>
                </div>
                </div>

                <div class="pagination-wrapper hotel-pagination mt-5">
                 {{ $gstQueries->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
          </div>
        </div>
    </section>
@slot('bottomBlock') 


@endslot
@endcomponent


