@component('admin.layouts.main')

    @slot('title')
        Admin - Faq Categories - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl=CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $parent_id = (request()->has('parent_id'))?request()->parent_id:'';
    $old_title = (request()->has('title'))?request()->title:'';
    $old_status = (request()->has('status'))?request()->status:1; 
    ?>

<div class="top_title_admin">
    <div class="title">
    <h2>Faq Categories ({{ count($pages) }})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('faqs','add'))
        <a href="{{ route('admin.faq_categories.add', ['parent_id'=>$parent_id, 'back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Faq Categories</a>
     @endif
    </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
                        <label>Name:</label><br/>
                        <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
                    </div>
                     <div class="col-md-2">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-6">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route($routeName.'.faq_categories.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    
<!-- End Search box Section -->


            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($pages) && count($pages) > 0){
                ?>

               <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th width="35%">Title</th>
                            <!-- <th width="35%">Faq Category</th> -->
                            <th width="30%">Slug</th>
                            <th width="10%">Status</th>
                            <th width="10%">Date Created</th>
                            <th width="15%">Action</th>
                        </tr>

                        <?php
                        foreach($pages as $page){

                            $child_url = 'javascript:void(0)';
                            $title = $page->title;

                            if(!empty($page->children) && $page->children->count() > 0 ){
                                $child_url = 'faq_categories?parent_id='.$page->id.'&back_url='.rawurlencode($BackUrl);
                                $title = '<i class="fa fa-list"></i> <strong>'.$page->title.'</strong>';
                            }
                        ?>
                            <tr>
                                <td> <a href="{{$child_url}}"> <?php echo $title; ?></a>
                                   </td>
                                <td><?php echo $page->slug; ?></td>
                                <td> <?php if($page->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?>
                                </td>
                                <td>{{ CustomHelper::DateFormat($page->created_at, 'd/m/Y') }}</td>


                                <td>
                                   @if(CustomHelper::checkPermission('faqs','add'))
                                    <a href="{{ route($routeName.'.faq_categories.add', ['parent_id'=>$page->id, 'back_url'=>$BackUrl]) }}" title="Add Child Page" ><i class="fas fa-plus"></i></a>
                                    &nbsp;
                                    @endif 
                                    @if(CustomHelper::checkPermission('faqs','view'))
                                    <!-- <a href="{{route($routeName.'.faq_categories.view',[$page['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a> -->
                                    @endif

                                    @if(CustomHelper::checkPermission('faqs','edit'))
                                    <a href="{{ route($routeName.'.faq_categories.edit', $page->id) }}" class=""><i class="fas fa-edit"></i></a>
                                    @endif

                                    @if(CustomHelper::checkPermission('faqs','delete'))
                                    <?php
                                    if($page->children->count() == 0){
                                        ?>
                                        <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" ><i class="fas fa-trash-alt"></i></a>

                                        <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.faq_categories.delete', [$page['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this faq Categories?');" class="delForm">
                                            {{ csrf_field() }}
                                        </form>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <a href="javascript:void(0)" title="Delete" onclick="alert('please remove Child associated with this Parent first!')" ><i class="fas fa-trash-alt"></i></a>
                                        <?php
                                    } ?>

                                    @endif
                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                {{ $pages->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no CMS Pages at the present.</div>
        <?php

       }
           ?>

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