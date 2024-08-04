 <?php
  $show= $tpsl->show ?? 0;

  ?>
 <form action="" method="POST">
     {{ csrf_field() }}

    <div class="config_sec">
        <span>{{$tpsl->name?? ''}} Configuration</span>
        <img src="{{ url('/img/tpsl.png') }}" alt="">
    </div>
 <input type="hidden" class="form-control" name="id" value="{{$tpsl->id}}" />
    <div class="radio_wrapper">
        <label class="radio-item">
            <input type="radio" value="test" <?php if($tpsl->perameter_1 == 'test'){ echo 'checked';} ?> name="perameter_1">
            <span>Test</span>
        </label>
        <label class="radio-item">
            <input type="radio" value="live" <?php if($tpsl->perameter_1 == 'live'){ echo 'checked';} ?>  name="perameter_1">
            <span>Live</span>
        </label>
    </div>
    <div class="form-group">
        <label class="control-label required">TPSL Merchant Code</label>
        <input type="text" class="form-control" name="perameter_2" value="{{$tpsl->perameter_2}}" placeholder="TPSL Client ID"/>
    </div>
    <div class="form-group">
        <label class="control-label required">TPSL Secret Key</label>
        <input type="text" class="form-control" name="perameter_3" value="{{$tpsl->perameter_3}}" placeholder="TPSL Secret Key"/>
    </div>
  <div class="form-group">
        <label class="control-label required">TPSL IV</label>
        <input type="text" class="form-control" name="perameter_4" value="{{$tpsl->perameter_4}}" placeholder="TPSL IV"/>
    </div>
<div class="form-group">
        <label class="control-label required">TPSL Scheme Code</label>
        <input type="text" class="form-control" name="perameter_5" value="{{$tpsl->perameter_5}}" placeholder="TPSL Scheme Code"/>
    </div>

    
      <div class="form-group">
        <label class="control-label">Sort Order</label>
        <input type="text"  name="sort_order"  value="{{$tpsl->sort_order}}" class="form-control" placeholder="sort order"  />
    </div>

    <div class="form-group">
        <label class="control-label"> Description </label>
        <textarea name="details" id="details" class="form-control" >{{$tpsl->details}}</textarea>    
    </div>
    
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
