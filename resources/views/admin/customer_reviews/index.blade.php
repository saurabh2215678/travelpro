    @component('admin.layouts.main')

    @slot('title')
    Admin - Manage Customer Review Enquiries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_your_name = (request()->has('your_name'))?request()->your_name:'';
    ?>
    <div class="top_title_admin">
        <div class="title">
            <h2>Customer Review ({{ $customers->total() }})</h2>
        </div>

        <div class="add_button">
          @if(CustomHelper::checkPermission('customer_review','add'))
          <?php /* <a href="{{ route('admin.customer_reviews.customer_review_add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Review</a> */ ?>
          @endif
          @if(CustomHelper::checkPermission('customer_review','add'))
          <a href="{{route('customer-review')}}" target="_blank" class="btn_admin">Customer Review</a>
          @endif
      </div>
  </div>

  <!-- Start Search box section-->
  <div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Name:</label><br/>
                    <input type="text" name="your_name" class="form-control admin_input1" value="{{$old_your_name}}">
                </div>

                <!--  <div class="clearfix"></div> -->

                <div class="col-md-6">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.customer_reviews.index')}}" class="btn_admin2">Reset</a>

                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    <div class="centersec">

        @include('snippets.errors')
        @include('snippets.flash')

        <?php

        if(!empty($customers) && $customers->count() > 0){?>
            <div class="table-responsive">

                {{ $customers->appends(request()->query())->links('vendor.pagination.default') }}

                <table class="table table-bordered">
                    <tr>
                        <th class="">Name</th>
                        <th class="">Trip Name & Duration</th>
                        <th class="">Data</th>
                        <th class="">Action</th>
                    </tr>
                    <?php
                    
                    foreach ($customers as $customer){ ?>
                        <tr>
                            <td>{{$customer->your_name}}</td>
                            <td>{{ $customer->trip_name_duration }}</td>
                            <td>
                                <div><strong>Address: </strong>{{ $customer->address }}</div>
                                <div><strong>Email: </strong>{{ $customer->email }}</div>
                                <div><strong>Date: </strong>{{ date('d M,Y H:i A',strtotime($customer->created_at)) }}</div>
                            </td>
                            
                            <td>


                                {{-- <a href="{{route('admin.customer_reviews.customerView',[$customer['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>--}}

                                @if(CustomHelper::checkPermission('customer_review','view'))
                                <a href="javascript:void(0);" data-src="<?php echo route('admin.customer_reviews.customerView', [$customer['id']]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('customer_review','edit'))
                                <a href="{{ route('admin.customer_reviews.customer_review_edit', ['id'=>$customer['id'], 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                @endif

                                @if(CustomHelper::checkPermission('customer_review','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$customer->id}}"><i class="fas fa-trash-alt"></i></i></a> </br></br>

                                <form method="POST" action="{{ route('admin.customer_reviews.customerDelete', $customer->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Customer Review?');" id="delete-form-{{$customer->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="enquiry_id" value="<?php echo $customer->id; ?>">

                                </form>
                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $customers->appends(request()->query())->links('vendor.pagination.default') }}
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

