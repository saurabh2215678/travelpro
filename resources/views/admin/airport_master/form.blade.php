@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
    <?php
    $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $id = (isset($record->id))?$record->id:'';
    $code = (isset($record->code))?$record->code:'';
    $name = (isset($record->name))?$record->name:'';
    $citycode = (isset($record->citycode))?$record->citycode:'';
    $city = (isset($record->city))?$record->city:'';
    $country = (isset($record->city))?$record->country:'';
    $countrycode = (isset($record->countrycode))?$record->countrycode:'';
    $sort_order = (isset($record->sort_order))?$record->sort_order:'';
    $featured = (isset($record->featured))?$record->featured:'';
    $status = (isset($record->status))?$record->status:1;
    ?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">

        <div class="col-md-6">
        <h2 class="top_title_admin enquiries-top">{{ $page_heading }} <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        </div>
        <div class="col-md-6"><a href="{{ route($ADMIN_ROUTE_NAME.'.airport_master.index')}}" class="btn_admin btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?></h2></div>
</div>
        <div class="top_title_admin enquiries-top" style="min-height: auto; padding-left:0; padding-right:0;">

<div class="col-md-12">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Airport Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label for="code" class="control-label required">Code (3 Char):</label>
                            <input type="text" id="code" class="form-control" name="code" value="{{ old('code', $code) }}" />
                            @include('snippets.errors_first', ['param' => 'code'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="control-label required">City:</label>
                            <input type="text" id="city" class="form-control" name="city" value="{{ old('city', $city) }}" />
                            @include('snippets.errors_first', ['param' => 'city'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('citycode') ? ' has-error' : '' }}">
                            <label for="citycode" class="control-label required">City Code (3 Char):</label>
                            <input type="text" id="citycode" class="form-control" name="citycode" value="{{ old('citycode', $citycode) }}" />
                            @include('snippets.errors_first', ['param' => 'citycode'])
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <label for="country" class="control-label required">Country:</label>
                            <input type="text" id="country" class="form-control" name="country" value="{{ old('country', $country) }}" />
                            @include('snippets.errors_first', ['param' => 'country'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('countrycode') ? ' has-error' : '' }}">
                            <label for="countrycode" class="control-label required">Country Code (2 Char):</label>
                            <input type="text" id="countrycode" class="form-control" name="countrycode" value="{{ old('countrycode', $countrycode) }}" />
                            @include('snippets.errors_first', ['param' => 'countrycode'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:</label>
                            
                            <input type="text" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            &nbsp;&nbsp;
                            Show: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Hide: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                            &nbsp;&nbsp;
                            Featured: <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> >
                            @include('snippets.errors_first', ['param' => 'featured'])
                        </div>
                    </div>
                </div>
            

            <div class="col-full">
                <div class="form-group">
                  <!-- <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  /> -->
                    <input type="hidden" name="back_url" value="{{ $back_url }}" >
                    <button type="submit" class="btn btn-success btn_admin2" title="Submit"><i class="fa fa-save"></i> Submit</button>
                </div>
            </div>
              
        </div>
        
    </div>
</form>
</div> 
        </div>
<div class="clearfix"></div>
</div>
@slot('bottomBlock')


@endslot
 

@endcomponent