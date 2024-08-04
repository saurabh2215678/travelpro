    @component('admin.layouts.main')

    @slot('title')
        Admin - Manage Package Enquiries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_package_id = (request()->has('package_id'))?request()->package_id:'';
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>Customize Packages ({{ $customize_packages->total() }})</h2>
    </div>

    </div>

    <!-- Start Search box section-->
<div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Package Name:</label><br/>
                        <input type="text" name="package_id" class="form-control admin_input1" value="{{$old_package_id}}">
                    </div>
                     
                   <!--  <div class="clearfix"></div> -->

                    <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.enquiries.customize_packages')}}" class="btn_admin2">Reset</a>

                    </div>
                </form>
            </div>
        </div>
<!-- End Search box Section -->

    <div class="centersec">

            @include('snippets.errors')
            @include('snippets.flash')

        <?php

        if(!empty($customize_packages) && $customize_packages->count() > 0){?>
            <div class="table-responsive">

            {{ $customize_packages->appends(request()->query())->links('vendor.pagination.default') }}

                <table class="table table-bordered">
                    <tr>
                        <th class="">Package Name</th>
                        <th class="">Data</th>
                        <th class="">Action</th>
                    </tr>
                    <?php
                    
                    foreach ($customize_packages as $customize_package){ ?>
                        <tr>
                            <td>{{(!empty($customize_package->customizePackage->title)) ? $customize_package->customizePackage->title:"" }}</td>
                            <td>
                                <p><strong>Email: </strong>{{ $customize_package->email }}</p>
                                <p><strong>Phone: </strong>{{ $customize_package->phone }}</p>
                                <p><strong>Country: </strong>{{ $customize_package->country }}</p>
                                <p><strong>Comments: </strong>{{ CustomHelper::wordsLimit($customize_package->content,100,true) }}</p>
                                <p><strong>Date: </strong>{{ date('d M,Y H:i A',strtotime($customize_package->created_at)) }}</p>
                            </td>
                            
                            <td>
                                

                                <a href="{{route('admin.enquiries.customize_this_trip_view',[$customize_package['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>

                                @if(CustomHelper::checkPermission('enquiries','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$customize_package->id}}"><i class="fas fa-trash-alt"></i></i></a> </br></br>

                                <form method="POST" action="{{ route('admin.enquiries.customize_this_trip_delete', $customize_package->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiry?');" id="delete-form-{{$customize_package->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="enquiry_id" value="<?php echo $customize_package->id; ?>">

                                </form>
                            @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $customize_packages->appends(request()->query())->links('vendor.pagination.default') }}
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

