	@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Image Category - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />

@endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $parent_id = (request()->has('parent_id'))?request()->parent_id:'';

    

    $title = '';

    $title = 'Image Category List';
    $addBtn = 'Add Image Category';

    $module = (request()->has('module'))?request()->module:[];
    $module = old('module', $module);
    $old_company_id = app('request')->input('company_id');
    $old_name = (request()->has('name'))?request()->name:'';
    $old_language = app('request')->input('language');
    $old_status = (request()->has('status'))?request()->status:1; 
    $old_from = app('request')->input('from');
    $old_to = app('request')->input('to');

    $language_arr = config('custom.language_arr');
    $module_types = config('custom.module_level_arr');

    $storage = Storage::disk('public');
    $path = 'gallery/';
    ?>
    
   <div class="top_title_admin">
    <div class="title">
    <h2>{{$title}} ({{ $categories->total() }})</h2>
    </div>
    <div class="add_button">
            @if(CustomHelper::checkPermission('image_categories','add'))
            <a href="{{ route($routeName.'.image_categories.add', ['parent_id'=>$parent_id, 'back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> <?php echo $addBtn;?></a>
            @endif 
        </div>
    </div>
<!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor">
                        <div class="table-responsive">

                            <form class="form-inline" method="GET">

                                <input type="hidden" name="parent_id" value="{{$parent_id}}">
                                
                                 <div class="col-md-3">
                                    <label>Image Category Name:</label><br/>
                                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                                </div>

                                <div class="col-md-3{{$errors->has('module')?' has-error':''}}">
                                  <label for="FormControlSelect1">Module Name</label><br/>
                                  <select name="module[]" class="form-control admin_input1 select2" multiple="multiple">
                                    <option value="">--Select Module Name--</option>
                                    @foreach($module_types as $k => $v)
                                    <option value="{{$k}}" {{(!empty($module) && in_array($k,$module))?'selected':''}} >{{$v}}</option>
                                    @endforeach
                                  </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Status:</label><br/>
                                    <select name="status" class="form-control admin_input1">
                                        <option value="">--Select--</option>
                                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label></label><br>
                                    <button type="submit" class="btn btn-success">Search</button>
                                    <a href="{{url($routeName.'/image_categories?parent_id='.$parent_id)}}" class="btn_admin2">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
              <!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')

        <?php

        if(!empty($categories) && $categories->count() > 0){
            ?>
            <div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Module Name</th>
                        <th>Image Category Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    
                    
                    foreach ($categories as $category){

                        $created_at = CustomHelper::DateFormat($category->created_at, 'd M Y');

                        $child_url = 'javascript:void(0)';
                        $name = $category->name;

                        if(!empty($category->children) && $category->children->count() > 0 ){
                                $child_url = 'image_categories?parent_id='.$category->id.'&back_url='.rawurlencode($BackUrl);
                                $name = '<i class="fa fa-list"></i> <strong>'.$category->name.'</strong>';
                            }
                        ?>
                        <tr class="row_{{$category->id}}">

                        <td>{{$module_types[$category->module] ?? ""}}
                            </td>
                                
                            <td> <a href="{{$child_url}}"><?php echo $name;?> </a></td>
                            </td>
                            <td> <?php if($category->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>
                            <!-- <td><a href="{{ route($routeName.'.images.upload', ['vid'=>0, 'tbl'=>'gallery', 'category_id'=>$category->id]) }}" class="" title="View Image"> {{$category->images()->count()}} </a></td> -->
                            <td>
                                @if(CustomHelper::checkPermission('image_categories','add'))
                                <a href="{{ route($routeName.'.image_categories.add', ['parent_id'=>$category->id, 'back_url'=>$BackUrl]) }}" title="Add Child Page" ><i class="fas fa-plus"></i></a>
                                &nbsp;
                                @endif
                                
                                @if(CustomHelper::checkPermission('image_categories','edit'))
                                <a href="{{route($routeName.'.image_categories.edit', ['id'=>$category->id,'parent_id'=>$parent_id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                                @endif
                                &nbsp;

                                @if(CustomHelper::checkPermission('image_categories','delete'))
                                <?php 
                                if ($category->images() && $category->images()->count() == 0) { ?>
                                <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" ><i class="fas fa-trash-alt"></i></a>

                                <form method="POST" action="{{ route($routeName.'.image_categories.delete', $category->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this category?');" class="delForm">
                                    {{ csrf_field() }}
                                </form>
                                <?php } ?>
                                
                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div> 
                     {{ $categories->appends(request()->query())->links('vendor.pagination.default') }}

            <?php
                    }
                    else{
                ?>
                <div class="alert alert-warning">There are no Records at the present.</div>
                <?php
            }
            ?>
            </div>

@slot('bottomBlock')

<script type="text/javascript" src="{{ url('/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script type="text/javascript">
    $('.select2').select2({
        placeholder: "Select Module Name",
        allowClear: true
      });
</script>

<script>
    $(document).on("click", ".sbmtDelForm", function(e){
        e.preventDefault();
        $(this).siblings("form.delForm").submit(); 
    });

    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            'dateFormat':'dd/mm/yy'
        });
    });

    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });

</script>
@endslot

@endcomponent