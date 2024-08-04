@component('admin.layouts.main')

    @slot('title')
        Admin - Price Category - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
        $id = (request()->has('id'))?request()->id:'';
        $old_price_category_name = (request()->has('name'))?request()->name:'';
        $old_status = (request()->has('status'))?request()->status:1; 
    ?>

    <!-- Start Search box section -->
    <div class="top_title_admin">
    <div class="title">
    <h2>Manage Price Category ({{ $price_categories->total() }})</h2>
    </div>
    <div class="add_button">
          @if(CustomHelper::checkPermission('all_masters','add'))
    <a href="{{ route('admin.price_category.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Price Category</a>
    @endif
    </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Price Category Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_price_category_name}}">
                    </div>
                     <div class="col-md-2">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-6"><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.price_category.index')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Search box Section -->
 
            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!empty($price_categories) && $price_categories->count() > 0){ ?>
                <div class="table-responsive">           
        <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th>Price Category Name</th>
                            <th>No. of Packages</th>
                            <th>Display Title</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <th>Action</th>
                            @endif
                        </tr>

                        <?php foreach($price_categories as $price_category){ ?>
                            <tr>
                            <td>{{$price_category->name}}</td>
                            <td><a href="{{route($routeName.'.packages.index',['price_category'=> $price_category->id])}}">({{$price_category->packages_count}})</a>
                        </td>
                            <td>{{$price_category->display_title}}</td>
                                <td><?php if($price_category->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?>
                            </td>
                                <td>{{ CustomHelper::DateFormat($price_category->created_at, 'd/m/Y') }}</td>

                                @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                                <td>
                                @if(CustomHelper::checkPermission('all_masters','edit'))
                                    <a href="{{ route($routeName.'.price_category.edit', $price_category->id) }}" class="" title="Edit Price Category"><i class="fas fa-edit"></i> </a>
                                    @endif

                                    @if($price_category->packages_count < 1)
                                    @if(CustomHelper::checkPermission('all_masters','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete Price Category"><i class="fas fa-trash-alt"></i></a>
                                    @endif
                                    
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.price_category.delete', [$price_category['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                        {{ csrf_field() }}
                                    </form>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $price_categories->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no price category at the present.</div>
        <?php } ?>
        </div>

    @slot('bottomBlock')
    <script type="text/javascript">
        $(document).on("click", ".sbmtDelForm", function(e){
            e.preventDefault();

            $(this).siblings("form.delForm").submit();                
        });
    </script>
    @endslot

@endcomponent