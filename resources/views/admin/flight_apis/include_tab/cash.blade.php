<form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
     {{ csrf_field() }}
     <?php
     $show= $bank->show ?? 0;

     ?> <div class="config_sec">
        <span>Cash on Delivery</span>

    </div><br>
        <input type="hidden" class="form-control" name="id" value="{{$cash->id}}" />
        
        <div class="form-group">
            <label class="control-label">Sort Order</label>
            <input type="text"  name="sort_order"  value="{{$cash->sort_order}}" class="form-control" placeholder="sort order"  />
        </div>

        <div class="form-group">
            <label class="control-label"> Description </label>
            <textarea name="details" id="details" class="form-control" >{{$cash->details}}</textarea>    
        </div>
        <div class="form-group">
            <label class="control-label">Show :</label>
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