    @component('admin.layouts.main')

    @slot('title')
    Admin - Manage Modules - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <style>
    .centersec {
    min-height: auto;
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';

    $identifier = (request()->has('identifier'))?request()->identifier:[];
    $identifier = old('identifier', $identifier);
    $old_status = (request()->has('status'))?request()->status:1;
    
    $seo_module_config_arr = config('custom.seo_module_config_arr');

    $storage = Storage::disk('public');
    $path = 'seo_tags/';
    ?>
    <div class="top_title_admin">
    <div class="title">
        <h2>Modules ({{ $res->count() }})</h2>
    </div>
</div>

   <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
                        <div class="table-responsive">

                            <form class="form-inline" method="GET">

                                <input type="hidden" name="id" value="{{$id}}">
                                
                                <div class="col-md-4{{$errors->has('identifier')?' has-error':''}}">
                                  <label for="FormControlSelect1">Identifier Name</label><br/>
                                  <select name="identifier[]" class="form-control admin_input1 select2" multiple="multiple">
                                    <option value="">--Select Identifier Name--</option>
                                    @foreach($seo_module_config_arr as $k => $v)
                                    <option value="{{$k}}" {{(!empty($identifier) && in_array($k,$identifier))?'selected':''}} >{{$v}}</option>
                                    @endforeach
                                  </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Status:</label><br/>
                                    <select name="status" class="form-control admin_input1">
                                        <option value="">--Select--</option>
                                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label></label><br>
                                    <button type="submit" class="btn btn-success">Search</button>
                                    <a href="{{route($routeName.'.module_config.index')}}" class="btn_admin2">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
              <!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')
            

        <?php
        if(!empty($res) && $res->count() > 0){
            ?>
           <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                    <tr><th colspan="10"><span style="font-size: 20px;">Major Modules</span></th></tr>
                    <tr>
                        <th>Image</th>
                        <th class="">Identifier</th>
                        <th class="">Identifier Description</th>
                        <th class="">Page Listing Url/Label</th>
                        <th class="">Page Listing Url/Link </th>
                        <th class="">Detail Page  Url/Link</th>
                        <th class=""> Page Title</th>
                        <th class=""> Meta Title</th>
                        <th class="">Status</th>
                        <th class="">Action</th>  
                    </tr>
                    <?php
                    // $storage = Storage::disk('public');
                    foreach ($res as $rec) { ?>
                        <tr>
                            <td>
                            <?php
                            $image = $rec->image;
                            if(!empty($image)){
                                if($storage->exists($path.$image)){
                                    ?>
                                    <div class="col-md-2 image_box">
                                        <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                    </div>
                                <?php }}else{
                                    echo "No Image";
                                } ?>
                            </td>
                            <td>{{$seo_module_config_arr[$rec->identifier??'']}}<br>({{$rec->module_type}}) 
                              
                                </td>
                            <td>{{$rec->description??''}}</td>
                            <td>{{$rec->page_url_label}}</td>
                            <td>{{$rec->page_url}}</td>
                            <td>{{$rec->page_detail_url}}</td>
                            <td>{{$rec->page_title}}</td>
                            <td>{{$rec->meta_title}}</td>

                            <td><?php if($rec->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                        </td>

                       
                        <td>
                            @if(CustomHelper::checkPermission('module_config','view'))
                            <a href="{{route($routeName.'.module_config.view',[$rec['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                            @endif
                            @if(CustomHelper::checkPermission('module_config', 'edit'))
                            <a href="{{route('admin.module_config.save', ['id'=>$rec->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif    
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        {{ $res->appends(request()->query())->links('vendor.pagination.custom') }}
        <?php
    }
    else{
        ?>
        <div class="alert alert-warning">There are no Major Records at the present.</div>
        <?php
    }
    ?>

    <?php
        if(!empty($res2) && $res2->count() > 0){ 
            ?>
           <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                <tr><th colspan="10"><span style="font-size: 20px;">Minor Modules</span></th></tr>
                    <tr>
                        <th>Image</th>
                        <th class="">Identifier</th>
                        <th class="">Identifier Description</th>
                        <th class="">Page Url/Label</th>
                        <th class="">Page Url/Link </th>
                        <th class="">Detail Page  Url/Link</th>
                        <th class=""> Page Title</th>
                        <th class=""> Meta Title</th>
                        <th class="">Status</th>
                        <th class="">Action</th>  
                    </tr>
                    <?php
                    // $storage = Storage::disk('public');
                    foreach ($res2 as $rec) { ?>
                        <tr>
                            <td>
                            <?php
                            $image = $rec->image;
                            if(!empty($image)){
                                if($storage->exists($path.$image)){
                                    ?>
                                    <div class="col-md-2 image_box">
                                        <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                    </div>
                                <?php }}else{
                                    echo "No Image";
                                } ?>
                            </td>
                            <td>{{$seo_module_config_arr[$rec->identifier]??''}}<br>({{$rec->module_type}}) 
                              
                                </td>
                            <td>{{$rec->description??''}}</td>
                            <td>{{$rec->page_url_label}}</td>
                            <td>{{$rec->page_url}}</td>
                            <td>{{$rec->page_detail_url}}</td>
                            <td>{{$rec->page_title}}</td>
                            <td>{{$rec->meta_title}}</td>

                            <td><?php if($rec->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                        </td>

                       
                        <td>
                            @if(CustomHelper::checkPermission('module_config','view'))
                            <a href="{{route($routeName.'.module_config.view',[$rec['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                            @endif
                            @if(CustomHelper::checkPermission('module_config', 'edit'))
                            <a href="{{route('admin.module_config.save', ['id'=>$rec->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif    
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        {{ $res->appends(request()->query())->links('vendor.pagination.custom') }}
        <?php
    }
    else{
        ?>
        <div class="alert alert-warning">There are no Minor Records at the present.</div>
        <?php
    }
    ?>
</div>

@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('.select2').select2({
        placeholder: "Select Identifier Name",
        allowClear: true
      });
</script>
@endslot

@endcomponent