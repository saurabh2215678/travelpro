<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<style>
    .enquiries-top form {
    pointer-events: none;
}
</style>
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$id = (isset($record->id))?$record->id:'';
$status = (isset($record->status))?$record->status:1;
$markup = (isset($record->markup))?$record->markup:'';
$markup_type = (isset($record->markup_type))?$record->markup_type:0;
$admin_markup = (isset($admin_markup))?$admin_markup:0;
?>
<div class="enq-view fancybox-content">
<div class="top_title_admin2">
        <div class="title">
            <h2 style="padding: 5px 15px;"><strong>{{ $page_heading }}</strong></h2>
        </div>
    </div>

<div class="enquiries-top">

    <div class="col-md-12">
        <div class="bgcolor">
            @include('snippets.flash')
            <div class="ajax_msg"></div>
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Agent Name:</label> <br />
                            {{$record->userData->name??'System'}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Departure / Arrival Time:</label> <br />
                            {{CustomHelper::DateFormat($record->routeData->start_time,'h:i A')}} /
                            {{CustomHelper::DateFormat($record->routeData->end_time,'h:i A')}}
                            @if($record->routeData->is_arrival_next_day)
                            (Arrival Next Day)
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Source / Destination:</label> <br />
                            {{$record->routeData->source??''}} / {{$record->routeData->destination??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Airline / Flight No:</label> <br />
                            {{$record->routeData->airline??''}}-{{$record->routeData->flight_number??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">PNR / Class / Stops:</label> <br />
                            {{$record->pnr??''}} / {{CustomHelper::showCabinClass($record->flight_class??'')}} / {{$record->routeData->stops??''}} Stop(s)
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Refundable Status:</label> <br />
                            <?php if($record->is_refundable == 1){ ?>
                                <i class="" style="color:green">Yes</i>
                            <?php }else{ ?>
                                <i class="" style="color:red">No</i>
                            <?php } ?>
                        </div>
                    </div>

                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Tickets In Stock:</label> <br />{{$record->initial_inventory??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Tickets for Sale:</label> <br />{{$record->inventory??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Tickets Sold:</label> <br />
                            {{$record->inventorySoldCount()??''}}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Adult Price:</label> <br />
                            {{CustomHelper::getPrice($record->adult_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Child Price:</label> <br />
                            {{CustomHelper::getPrice($record->child_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Infant Price:</label> <br />
                            {{CustomHelper::getPrice($record->infant_price)??''}}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-8">
                        <div class="form-group{{ $errors->has('markup') ? ' has-error' : '' }} inline-markup">
                            <label for="markup" class="control-label">Markup:</label> <br />
                            <span>Default: <input type="radio" name="markup_type" value="0" {{($markup_type=='0')?'checked':''}} > {{CustomHelper::getPrice($admin_markup)}} </span>
                            <span>Custom: <input type="radio" name="markup_type" class="markup_price" value="1" {{($markup_type=='1')?'checked':''}} ></span>
                            <span><input type="text" name="markup" class="form-control" class="price_value" value="{{old('markup',$markup)}}"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="control-label">Status:</label>
                            <?php if($record->status == 1){ ?>
                                <i class="" style="color:green">Active</i>
                            <?php }else{ ?>
                                <i class="" style="color:red">Pending</i>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div> 
</div>
<div class="clearfix"></div>
</div>
</div>
@slot('bottomBlock')

@endslot
<script type="text/javascript">
$(document).ready(function() {
    show_markup('<?php echo $markup_type; ?>');
})
$('input[name=markup_type]').on('click',function() {
    var markup_type = $(this).val();
    show_markup(markup_type);
});
function show_markup(markup_type) {
    if(markup_type == 1) {
        $('input[name=markup]').show();
    } else {
        $('input[name=markup]').hide();
    }
}    


</script>