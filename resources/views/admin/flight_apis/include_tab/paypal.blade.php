 <?php
 $show= $paypal->show ?? 0;

  ?>
 <form action="" method="POST">
     {{ csrf_field() }}

    <div class="config_sec">
        <span>Paypal Configuration</span>
        <img src="{{ url('/img/paypal.png') }}" alt="">
    </div>
 <input type="hidden" class="form-control" name="id" value="{{$paypal->id}}" />
    <div class="radio_wrapper">
        <label class="radio-item">
            <input type="radio" value="sandbox" <?php if($paypal->perameter_1 == 'sandbox'){ echo 'checked';} ?> name="perameter_1">
            <span>Sandbox</span>
        </label>
        <label class="radio-item">
            <input type="radio" value="live" <?php if($paypal->perameter_1 == 'live'){ echo 'checked';} ?>  name="perameter_1">
            <span>Live</span>
        </label>
    </div>

    <div class="form-group">
        <label class="control-label required">Paypal Client ID</label>
        <input type="text" class="form-control" name="perameter_2" value="{{$paypal->perameter_2}}" placeholder="Paypal Client ID"  />
    </div>
    <div class="form-group">
        <label class="control-label required">Paypal Client Secret Key</label>
        <input type="text" class="form-control" name="perameter_3" value="{{$paypal->perameter_3}}" placeholder="Paypal Client Secret Key"  />
    </div>
    
  <!--   <div class="form-gateway">
        <div class="form-group">
            <label class="control-label required">GATEWAY LOGO (400X166)PX</label>
            <label class="input_gateway">
                <input type="file" name="image" />
                <span class="gateway_placeholder">Gateway Logo</span>
                <span class="gateway_btn">Browse</span>
            </label>
        </div>
        <div class="gateway-img">
            <img src="{{ url('/img/paypal.png') }}" alt="">
        </div>
    </div> -->

    <div class="form-group">
        <label class="control-label">Sort Order</label>
        <input type="text"  name="sort_order"  value="{{$paypal->sort_order}}" class="form-control" placeholder="sort order"  />
    </div>

    <div class="form-group">
        <label class="control-label"> Description </label>
        <textarea name="details" id="details" class="form-control" >{{$paypal->details}}</textarea>    
    </div>
                            
    <div class="form-group{{ $errors->has('show') ? ' has-error' : '' }}">
                <label class="control-label">show :</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="show" value="1" <?php echo ($show == '1')?'checked':''; ?> >
                &nbsp;
                Inactive: <input type="radio" name="show" value="0" <?php echo ( strlen($show) > 0 && $show == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'show'])
            </div>
    <div class="submit_btn_box">
        <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
    </div>
</form>