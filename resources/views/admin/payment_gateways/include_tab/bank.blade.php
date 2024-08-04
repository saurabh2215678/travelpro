<form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
     {{ csrf_field() }}
     <input type="hidden" class="form-control" name="id" value="{{$bank->id}}" />
                            <div class="config_sec">
                                <span>Bank Configuration</span>
                                <img src="{{ url('/img/bank_payment.png') }}" alt="">
                            </div>

                            <div class="form-group">
                                <label class="control-label required">Bank Details</label>
                                 <textarea name="perameter_1" id="perameter_1" class="form-control ckeditor" >{{$bank->perameter_1}}</textarea>    
                            </div>

                            <div class="form-group">
                                <label class="control-label">Sort Order</label>
                                <input type="text"  name="sort_order"  value="{{$bank->sort_order}}" class="form-control" placeholder="sort order"  />
                            </div>

                            <div class="form-group">
                                <label class="control-label"> Description </label>
                                <textarea name="details" id="details" class="form-control" >{{$bank->details}}</textarea>    
                            </div>
                            <!-- <div class="form-group">
                                <label class="control-label required">Bank Name</label>
                                <input type="text"  name="perameter_1"  value="{{$bank->perameter_1}}" class="form-control" placeholder="Bank Name"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label required">Branch Name</label>
                                <input type="text"  name="perameter_2"  value="{{$bank->perameter_2}}"  class="form-control" placeholder="Branch Name"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label required">Account Number</label>
                                <input type="text"  name="perameter_3"  value="{{$bank->perameter_3}}" class="form-control" placeholder="Account Number"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label required">Account Holder</label>
                                <input type="text"  name="perameter_4"  value="{{$bank->perameter_4}}"  class="form-control" placeholder="Account Holder"  />
                            </div> -->
                            <?php
                            $show= $bank->show ?? 0;
                            $path = 'payment-gateway/';
                            $image_name = $bank->image;
                            ?>
                           <!--  <div class="form-gateway">
                                <div class="form-group">
                                    <label class="control-label required">GATEWAY LOGO (400X166)PX</label>
                                    <label class="input_gateway">
                                        <input type="file" name="image" />
                                        <span class="gateway_placeholder">Gateway Logo</span>
                                        <span class="gateway_btn">Browse</span>
                                    </label>
                                </div>
                                <div class="gateway-img">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$image_name) }}" alt="">
                                </div>
                            </div> -->
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