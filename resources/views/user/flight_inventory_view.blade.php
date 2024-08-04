<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<style type="text/css">
label {display: inline-block;max-width: 100%;margin-bottom: 5px;font-weight: 700;font-size: 12px;}
.form-group {margin-bottom: 15px;font-size: 12px;}
.enq-view.fancybox-content {padding: 18px;}
h2.top_title_admin {font-size: 18px;padding-bottom: 15px;font-weight: 600;}
.fancybox__slide .fancybox__content{max-width: 50rem;padding: 15px;}
</style>
<?php
$id = (isset($record->id))?$record->id:'';
$status = (isset($record->status))?$record->status:1;
?>
<div class="enq-view fancybox-content">
<div class="row top_title_admin enquiries-top">
    <div class="col-md-6">
        <h2 class="top_title_admin enquiries-top">{{ $page_heading }}</h2>
    </div>
</div>
<div class="row top_title_admin enquiries-top centersec">

    <div class="col-md-12">
        <div class="bgcolor">
            @include('snippets.front.flash')
            <div class="ajax_msg"></div>
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <label class="control-label">Fare Type:</label> <br />
                            @if($record->fare_type==2)
                            <span>Instant Offer Fare</span>
                            @else
                            <span>Offer Fare</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Departure Date:</label> <br />
                            <strong>{{CustomHelper::DateFormat(($record->start_date??''),'d/m/Y')}}</strong>
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Actual Adult Price:</label> <br />
                            {{CustomHelper::getPrice($record->agent_adult_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Actual Child Price:</label> <br />
                            {{CustomHelper::getPrice($record->agent_child_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Actual Infant Price:</label> <br />
                            {{CustomHelper::getPrice($record->agent_infant_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Selling Adult Price:</label> <br />
                            {{CustomHelper::getPrice($record->adult_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Selling Child Price:</label> <br />
                            {{CustomHelper::getPrice($record->child_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Selling Infant Price:</label> <br />
                            {{CustomHelper::getPrice($record->infant_price)??''}}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Remark:</label> <br />
                            {!!$record->remark!!}
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3">
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