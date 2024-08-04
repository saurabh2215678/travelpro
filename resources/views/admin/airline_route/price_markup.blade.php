@component('admin.layouts.main')

@slot('title')
Admin - Price Markup Settings - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$name = (request()->has('name'))?request()->name:'';
$status = (request()->has('status'))?request()->status:1;
$featured = (request()->has('featured'))?request()->featured:'';
$is_domestic = (request()->has('is_domestic'))?request()->is_domestic:'';
?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">
    <div class="col-md-4">
        <h5 class="title-text-left"> Price Markup Settings </h5>
    </div>
</div>

<div class="row top_title_admin enquiries-top centersec" style="min-height: auto;padding-left: 0;">

    <div class="col-md-12">
        <div class="bgcolor" style="padding-left: 0;">

            <div class="table-responsive">

                <form class="" method="GET">
                    <div class="col-sm-3">
                        <label>Airline:</label><br/>
                        <input type="text" name="name" class="form-control" value="{{$name}}" />
                    </div>

                    <?php /*
                    <div class="col-sm-3">
                        <label>Show:</label><br/>
                        <select name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1" {{($status == '1')?'selected':'1' }}>Show</option>
                            <option value="0" {{($status == '0')?'selected':'' }}>Hide</option>
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label>Featured:</label><br/>
                        <input type="checkbox" name="featured" class="form-control" value="1" {{($featured==1)?'checked':''}} />
                    </div>*/ ?>

                    <div class="col-sm-3">
                        <label>Is Domestic:</label><br/>
                        <input type="checkbox" name="is_domestic" class="form-control" value="1" {{($is_domestic==1)?'checked':''}} />
                    </div>

                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-success btn1search">Search</button>
                        <a href="{{url($ADMIN_ROUTE_NAME.'/airport_master/price-markup')}}" class="btn_admin2 btn resetbtn btn-primary btn1search">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


        @include('snippets.errors')
        @include('snippets.flash')

        <?php
        if(!empty($records) && $records->count() > 0){
            ?>
            <div class="bgcolor">
            <div class="table-responsive ">
                <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Airline</th>
                        <th>Markup Type</th>
                        <th>Markup Value</th>
                        <th>Markup Value (for Non-Comm. fares)</th>
                        <th>Max Base Price</th>
                        <th>Is Domestic</th>
                    </tr>
                    <?php foreach($records as $record) { ?>
                    <tr>
                        <td>
                            {{$record->name}}
                            <input type="hidden" name="airline_codes[]" value="{{$record->code}}">
                        </td>
                        <td>
                            <select name="markup_type[{{$record->code}}]" class="form-group">
                                <option value="fixed" {{($record->markup_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->markup_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="markup_value[{{$record->code}}]" class="form-group" value="{{$record->markup_value}}">
                        </td>
                        <td>
                            <input type="text" name="markup_value_nonc[{{$record->code}}]" class="form-group" value="{{$record->markup_value_nonc}}">
                        </td>
                        <td>
                            <input type="text" name="max_base_price[{{$record->code}}]" class="form-group" value="{{$record->max_base_price}}">
                        </td>
                        <td>
                            <input type="checkbox" name="is_domestic[{{$record->code}}]" class="form-group" value="1" {{($record->is_domestic==1)?'checked':''}} >
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($international){ ?>
                    <tr>
                        <th>International Price Markup Settings</th>
                    </tr>
                    <tr>
                        <td>
                            {{$international->name}}
                            <input type="hidden" name="airline_codes[]" value="{{$international->code}}">
                        </td>
                        <td>
                            <select name="markup_type[{{$international->code}}]" class="form-group">
                                <option value="fixed" {{($international->markup_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($international->markup_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="markup_value[{{$international->code}}]" class="form-group" value="{{$international->markup_value}}">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td colspan="3">
                            <input type="hidden" name="back_url" value="{{CustomHelper::BackUrl()}}">
                            <input type="submit" name="submit" value="Save">
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            {{ $records->appends(request()->query())->links('vendor.pagination.default') }}
        </div>
        <?php } else { ?>
        <div class="alert alert-warning top_title_admin">There is no dependent data present.</div>
        <?php } ?>
    </div>
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