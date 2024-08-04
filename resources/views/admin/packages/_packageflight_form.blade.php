<?php
if(isset($package) && !empty($package)) {
    $package_id = $package->id;
    $airline_name = $record->airline_name??'';
    ?>
    <br><form id="packageflight_form" role="form" class="" onSubmit="return validate_flight_form();">
        <input type="hidden" name="message" id="message">
        <div class="sform col-sm-12">
            <div class="form-group">
                <label for="hotel_category" class="col-sm-3 required">Flight Number</label>
                <div class="col-sm-3">
                    <input type="text" name="flight_number"  class="form-control" value="{{$record->flight_number??''}}" placeholder="Flight Number" />
                </div>
            </div>
            <div class="form-group">
                <label for="hotel_category" class="col-sm-3">Flight Time</label>
                <div class="col-sm-3">
                    <input type="text" name="flight_time"  class="form-control" value="{{$record->flight_time??''}}" placeholder="Flight Time" />
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label for="hotel_category" class="col-sm-3 required">Airline Name</label>
                <div class="col-sm-3">
                    <select class="form-control" name="airline_name">
                        <option value="">Select Airline</option>
                        @if($airlines)
                        @foreach($airlines as $airline)
                        <option value="{{$airline->airline_name}}" {{($airline->airline_name==$airline_name)?'selected':''}} >{{$airline->airline_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="hotel_category" class="col-sm-3 required">Origin</label>
                <div class="col-sm-3">
                    <input type="text" name="flight_departure"  class="form-control" value="{{$record->flight_departure??''}}" placeholder="Origin" />
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label for="hotel_category" class="col-sm-3 required">Destination</label>
                <div class="col-sm-3 pb-10">
                    <input type="text" name="flight_arrival"  class="form-control" value="{{$record->flight_arrival??''}}" placeholder="Destination" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 pb-10">
                    <textarea name="flight_comment" class="form-control" placeholder="Flight Comment" rows="4">{{$record->flight_comment??''}}</textarea>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <div class="col-sm-5 pb-10">
                    <label>&nbsp;</label>
                    <input type="hidden" name="package_id" class="form-control" value="<?php echo $package_id;?>" />
                    <input type="submit" name="submit" class="btn btn-success btn_admin btnSubmit">
                    <!-- <button id="price_form_save" ><i class="fa fa-save"></i> Save</button> -->
                    <a href="javascript:void(0);" class="btn_admin2 slide_close" title="Cancel">Cancel</a>
                    <input type="hidden" name="data_id" value="{{$record->id??''}}" />
                </div>
            </div>

        </div>
</form>
</body>
</html>
<?php } ?>