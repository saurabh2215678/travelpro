<form action="" method="POST">
     {{ csrf_field() }}

    <div class="config_sec">
        <span>RazorPay Configuration</span>
        <img src="{{ url('/img/razorpay.png') }}" alt="">
    </div>
     <input type="hidden" class="form-control" name="id" value="{{$razorpay->id}}" />

    <div class="form-group">
        <label class="control-label required">razor_key</label>
        <input type="text" class="form-control" name="perameter_1"  value="{{$razorpay->perameter_1}}" placeholder="razor_key"  />
    </div>
    <div class="form-group">
        <label class="control-label required">Razor Pay Secret Key</label>
        <input type="text" class="form-control"  name="perameter_2"  value="{{$razorpay->perameter_2}}" placeholder="Razor Pay Secret Key"  />
    </div>
 <!--    <div class="form-gateway">
        <div class="form-group">
            <label class="control-label required">GATEWAY LOGO (400X166)PX</label>
            <label class="input_gateway">
                <input type="file" name="image" />
                <span class="gateway_placeholder">Gateway Logo</span>
                <span class="gateway_btn">Browse</span>
            </label>
        </div>
        <div class="gateway-img">
            <img src="{{ url('/img/razorpay.png') }}" alt="">
        </div>
    </div> -->

     <div class="form-group">
        <label class="control-label">Sort Order</label>
        <input type="text"  name="sort_order"  value="{{$razorpay->sort_order}}" class="form-control" placeholder="sort order"  />
    </div>

    <div class="form-group">
        <label class="control-label"> Description </label>
        <textarea name="details" id="details" class="form-control" >{{$razorpay->details}}</textarea>    
    </div>
    
    <?php
 $show= $razorpay->show ?? 0;

  ?>
<div class="form-group{{ $errors->has('show') ? ' has-error' : '' }}">
                <label class="control-label">Show :</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="show" value="1" <?php echo ($show == '1')?'checked':''; ?> >
                &nbsp;
                Inactive: <input type="radio" name="show" value="0" <?php echo ( strlen($show) > 0 && $show == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'show'])
            </div>
    <div class="submit_btn_box">
        <button type="submit" class="btn btn-success btn_admin" title="Submit"><i class="fa fa-save"></i>Submit</button>
    </div>
</form>