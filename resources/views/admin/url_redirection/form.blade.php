@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{ config('app.name') }}
    @endslot

    <?php
    // $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    $id = (isset($urlredirection->id))?$urlredirection->id:'';
    $source_url = (isset($urlredirection->source_url))?$urlredirection->source_url:'';  
    $destination_url = (isset($urlredirection->destination_url))?$urlredirection->destination_url:'';  
    $status_code = (isset($urlredirection->status_code))?$urlredirection->status_code:'301';
    $status_code = old('status_code', $status_code);

    $show = (isset($urlredirection->show))?$urlredirection->show:1;
    ?>    
    <h2>
    {{ $page_heading }} 
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
    <a href="{{ url($back_url)}}" class="btn_admin2 btn-sm" style='float: right;'>Back</a>
    <?php } ?>
    </h2>

        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')  

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data" class="urlFrm">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-5">
                        
                        <div class="form-group{{ $errors->has('source_url') ? ' has-error' : '' }}">
                            <label for="source_url" class="control-label required">Source URL:</label>
                            <div class="input-group">
                                <span class="input-group-addon">{{url('')}}/</span>
                                <input type="text" id="source_url" class="form-control" name="source_url" value="{{ old('source_url', $source_url) }}" />
                            </div>
                            
                            
                        </div>
                        @include('snippets.errors_first', ['param' => 'source_url'])
                    </div>

                    <div class="col-md-2">
                        <div class="form-group{{ $errors->has('status_code') ? ' has-error' : '' }}">
                            <label for="status_code" class="control-label required">Status Code:</label>                            
                            <?php /*
                                <input type="text" id="status_code" class="form-control" name="status_code" value="{{ old('status_code', $status_code) }}" readonly/>
                                <select name="status_code" class="form-control" disabled style="display:none">
                                    <!-- <option value="">--Select--</option> -->
                                    <option value="301" <?php if($status_code == '301'){ echo 'selected';}?>>301</option>
                                    <option value="302" <?php if($status_code == '302'){ echo 'selected';}?>>302</option>

                                </select>
                                */?>

                            <select name="status_code" class="form-control">
                                    <!-- <option value="">--Select--</option> -->
                                    <option value="301" <?php if($status_code == '301'){ echo 'selected';}?>>301</option>
                                    <option value="410" <?php if($status_code == '410'){ echo 'selected';}?>>410</option>
                                </select>
                            @include('snippets.errors_first', ['param' => 'status_code'])
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('destination_url') ? ' has-error' : '' }}">
                            <label id="destination_url_for" for="destination_url" class="control-label">Destination URL:</label>
                            <div class="input-group">
                                <span class="input-group-addon">{{url('')}}/</span>
                                <input type="text" id="destination_url" class="form-control" name="destination_url" value="{{ old('destination_url', $destination_url) }}" />
                            </div>
                 
                            
                        </div>
                        @include('snippets.errors_first', ['param' => 'destination_url'])
                    </div>

                    

                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('show') ? ' has-error' : '' }}">

                            Active: <input type="radio" name="show" value="1" <?php echo ($show == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="show" value="0" <?php echo ( strlen($show) > 0 && $show == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'show'])
                        </div> 
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <p></p>
                            <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                            <button type="submit" class="btn_admin" title="Submit"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <div class="clearfix"></div>
    </div>


@slot('bottomBlock')

<script type="text/javascript">
$(document).ready(function(){
    check_destination_url('{{$status_code}}');
});

$('select[name=status_code]').on('change',function(){
    var status_code = $(this).val();
    check_destination_url(status_code);
});
function check_destination_url(status_code) {
    if(status_code == 410) {
        $('#destination_url').val('');
        $('#destination_url').attr('disabled',true);
        //$('#destination_url_for').removeClass('required');
    } else {
        $('#destination_url').attr('disabled',false);
        //$('#destination_url_for').addClass('required');
    }
}
</script>
@endslot 

@endcomponent