@component('admin.layouts.main')

@slot('title')
Admin - Manage Enquiries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';
?>
<div class="top_title_admin">
    <div class="title">
        <h2>Enquiries ({{$enquiries->count()}})</h2>
        @if(CustomHelper::checkPermission('enquiries','add'))
        <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.enquiries.add')?>" title="Add Enquiry" data-fancybox data-type="ajax"><i class="fas fa-plus"></i>Add Enquiry</a>
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
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                </div>
                <div class="col-md-6">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.enquiries.index')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    <div class="centersec">

        @include('snippets.errors')
        @include('snippets.flash')

        <?php if(!empty($enquiries) && $enquiries->count() > 0){?>
            <div class="table-responsive">

                {{ $enquiries->appends(request()->query())->links('vendor.pagination.default') }}

                <table class="table table-bordered">
                    <tr>
                        <th class="">Name</th>
                        <th class="">Data</th>
                        <th class="">Action</th>
                    </tr>
                    <?php

                    foreach ($enquiries as $enquiry){ ?>
                        <tr>
                            <td>{{$enquiry->name}}</td>
                            <td>
                                <p><strong>Email: </strong>{{ $enquiry->email }}</p>
                                <p><strong>Phone: </strong>{{ $enquiry->phone }}</p>
                                <p><strong>Country: </strong>{{ $enquiry->country }}</p>
                                <p><strong>Message: </strong>{{ CustomHelper::wordsLimit($enquiry->content,100,true) }}</p>
                                <p><strong>Date: </strong>{{ date('d M,Y H:i A',strtotime($enquiry->created_at)) }}</p>
                            </td>

                            <td>
                                <!-- <a href="{{ route('admin.enquiries.view', $enquiry->id.'?back_url='.$BackUrl) }}"><i class="fa fa-search-plus"></i></a> | -->

                                <a href="{{route('admin.enquiries.view',[$enquiry['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>

                                @if(CustomHelper::checkPermission('enquiries','edit'))
                                <a href="javascript:void(0);" data-src="{{route('admin.enquiries.edit',[$enquiry['id'], 'back_url'=>$BackUrl])}}" title="Edot Enquiry" data-fancybox data-type="ajax"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('enquiries','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$enquiry->id}}"><i class="fas fa-trash-alt"></i></i></a> </br></br>

                                <form method="POST" action="{{ route('admin.enquiries.delete', $enquiry->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiry?');" id="delete-form-{{$enquiry->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->id; ?>">

                                </form>
                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $enquiries->appends(request()->query())->links('vendor.pagination.default') }}
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
    <link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> 
    <script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script type="text/javaScript">
        $('.sbmtDelForm').click(function(){
            var id = $(this).attr('id');
            $("#delete-form-"+id).submit();
        });
    </script>
    @endslot


    @endcomponent

