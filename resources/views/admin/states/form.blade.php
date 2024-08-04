@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />

<div class="top_title_admin">
    <div class="title">
    <h2>{{$page_heading}}</h2>
    </div>
    
    </div>



<div class="row">

    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    if(empty($back_url)){
        $back_url = 'admin/states';
    }
    $name = (isset($rec->name))?$rec->name:'';
    $country_id = (isset($rec->country_id))?$rec->country_id:'';
    $status = (isset($rec->status))?$rec->status:1;
    ?>

    

<div class="centersec">
        <div class="bgcolor">
            @include('snippets.errors')
            @include('snippets.flash')

            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="row">
                    
                    <div class="col-sm-12 col-md-6">
                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <label class="control-label required">Country:</label>
                            <select class="form-control" name="country" id="country">
                                <option value="">Please Select</option>
                                <?php foreach($country as $c) {  ?>
                                <option <?php if($country_id==$c->id) { echo 'selected';    } ?> value="{{$c->id}}">{{$c->name}}</option>
                                <?php  }   ?>
                            </select>
                            @include('snippets.errors_first', ['param' => 'country'])
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
                    <button type="submit" class="btn btn-success" title="Create this new state"><i class="fa fa-save"></i> Submit</button>

                    <a href="{{ url($back_url) }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                </div>

            </form>
        </div>

    </div>


@slot('bottomBlock')




    
@endslot

@endcomponent