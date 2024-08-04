    @component('admin.layouts.main')

    @slot('title')
        Admin - Manage Itinerary Enquiries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_package_id = (request()->has('package_id'))?request()->package_id:'';
    $old_form_type = (request()->has('form_type'))?request()->form_type:'';
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>Request Itinerary Package Details ({{ $request_details->count() }})</h2>
    </div>
    </div>

<!-- Start Search box section-->
<div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Package Name:</label><br/>
                        <input type="text" name="package_id" class="form-control admin_input1" value="{{$old_package_id}}">
                    </div>

                    <div class="col-md-3">
                        <label>Request Itinerary/Type:</label><br/>
                        <select name="form_type" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="enquire" {{ ($old_form_type == 'enquire')?'selected':'' }}>Enquire Now</option>
                            <option value="request_itinerary" {{ ($old_form_type == 'request_itinerary')?'selected':'' }}>Request Itinerary</option>
                        </select>
                    </div>
                     
                   <!--  <div class="clearfix"></div> -->

                    <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.enquiries.request_details')}}" class="btn_admin2">Reset</a>

                    </div>
                </form>
            </div>
        </div>
<!-- End Search box Section -->

    <div class="centersec">

            @include('snippets.errors')
            @include('snippets.flash')

        <?php

        if(!empty($request_details) && $request_details->count() > 0){?>
            <div class="table-responsive">

            {{ $request_details->appends(request()->query())->links('vendor.pagination.default') }}

                <table class="table table-bordered">
                    <tr>
                        <th class="">Package Name</th>
                        <th class="">Data</th>
                        <th class="">Action</th>
                    </tr>
                    <?php
                    
                    foreach ($request_details as $request_detail){ ?>
                        <tr>
                            <td>{{$request_detail->requestitineraryPackage->title}}</td>
                            <td>
                                <p><strong>Email: </strong>{{ $request_detail->email }}</p>
                                <p><strong>Phone: </strong>{{ $request_detail->phone }}</p>
                                <p><strong>Country: </strong>{{ $request_detail->country }}</p>
                                <p><strong>Date: </strong>{{ date('d M,Y H:i A',strtotime($request_detail->created_at)) }}</p>
                            </td>
                            
                            <td>

                                <a href="{{route('admin.enquiries.request_detail_view',[$request_detail['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>

                                @if(CustomHelper::checkPermission('enquiries','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$request_detail->id}}"><i class="fas fa-trash-alt"></i></i></a> </br></br>

                                <form method="POST" action="{{ route('admin.enquiries.request_detail_delete', $request_detail->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiry?');" id="delete-form-{{$request_detail->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="enquiry_id" value="<?php echo $request_detail->id; ?>">

                                </form>
                            @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $request_details->appends(request()->query())->links('vendor.pagination.default') }}
            <?php
                    }
                    else{
                ?>
                <div class="alert alert-warning">There are no Records at the present.</div>
                <?php
            }
            ?>
            </div>
      


        @slot('bottomBlock')

        <script type="text/javaScript">
            $('.sbmtDelForm').click(function(){
                var id = $(this).attr('id');
                $("#delete-form-"+id).submit();
            });
        </script>

        @endslot


@endcomponent

