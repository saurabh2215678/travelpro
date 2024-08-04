@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
 <meta name="csrf-token" content="{{ csrf_token() }}">

<link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
@endslot

<div class="top_title_admin">
    <div class="title">
    <h2>{{$page_heading}}</h2>
    </div>
  
    </div>



    <div class="centersec">

    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    if(empty($back_url)){
        $back_url = 'admin/cities';
    }
    
    $name = (isset($city->name))?$city->name:'';
    $country_id = (isset($city->country_id))?$city->country_id:101;
    $state_id=(isset($city->state_id))?$city->state_id:'';
    $status = (isset($city->status))?$city->status:1;
    ?>

        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="row">

                    <?php // prd($country); ?>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <label class="control-label required">Country:</label>
                             
                             <select class="form-control" name="country" id="country">
                             <option value="">Please Select</option>
                             @foreach($country as $c)
                             <option value="{{$c->id}}" <?php echo ($c->id == $country_id) ? "selected" : ""; ?>>{{$c->name}}</option>
                             @endforeach
                             </select>
                             @include('snippets.errors_first', ['param' => 'country'])
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6">
                        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                            <label class="control-label required">State:</label>
                             
                            <select class="form-control" name="state" id="state">
                            <option value="">Please Select</option>
                                @foreach($state as $s)
                                <option value="{{$s->state_id}}" <?php echo ($s->state_id == $state_id) ? "selected" : ""; ?>>{{$s->name}}</option>
                                @endforeach
                            </select>

                              @include('snippets.errors_first', ['param' => 'state'])
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label required">Name:</label>

                            <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                        <label class="control-label">Status:</label>
                        <br>
                        <input class="" type="checkbox" <?php if($status==1) { echo 'checked';  } ?> name="status" id="status" value="1">
                        @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>
                    <div class="clearfix"></div>

                </div>

                <br>
                <br>

                    <div class="form-group">
                        <input type="hidden" name="back_url" value="{{ $back_url }}" >
                        <button type="submit" class="btn btn-success" title="Create this new product"><i class="fa fa-save"></i> Submit</button>

                        <a href="{{ url($back_url) }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                    </div>
                </form>
        </div>

    </div>


</div>
@slot('bottomBlock')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $('#country').on('change',function(e) {
            var country_id = e.target.value;
            $.ajax({
                url:"{{ route('common.ajax_load_states') }}",
                type:"POST",
                data: {
                    country_id: country_id
                },
                success:function (data) {
                    let text = "";
                    $('#state').empty();
                    text +=  `<option value="">---Select state---</option>`
                    text += data.options;
                    /*data.states.forEach(function(item, index){
                        text +=  `<option value="${item.id}">${item.name}</option>`
                    })*/
                    $("#state").html(text)
                    }
                })
            });
        });
</script>
@endslot
@endcomponent