    	@component('admin.layouts.main')

        @slot('title')
        Admin - Manage Roles - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
        @endslot
        <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
        $old_name = (request()->has('name'))?request()->name:''; 
        ?>    
        <div class="top_title_admin">
            <div class="title">
             <h2>Role List ({{ $roles->count() }})</h2>
         </div>
         <div class="add_button">
            @if(CustomHelper::checkPermission('roles','add'))
             <a href="{{ url($routeName.'/roles/save') }}" class="btn_admin"><i class="fa fa-plus"></i> Add Role</a>
             @endif
         </div>
     </div>
     <div class="centersec">
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-sm-4">
                        <label>Role Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                    <br>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{url($routeName.'/roles')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

            <?php
            if(!empty($roles) && $roles->count() > 0){
                ?>
                <div class="table-responsive bgcolor">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th class="">Name</th>
                            <th class="">Users</th>    
                            <th class="">Action</th>
                        </tr>
                        <?php
                        foreach ($roles as $role){
                            ?>
                            <tr>
                                <td>{{$role->name}}</td>
                                <td>  
                                    <?php 
                                    $total_user_by_roles = CustomHelper::total_user_by_roles($role->id);
                                    $total_user_by_roles;

                                   /* $vendor_id = 0;
                                    $users = auth()->guard('admin')->user();
                                    if($users){
                                        $vendor_id = $users->is_vendor??0;
                                    }

                                    $userUrl = route($routeName.'.users.index',['role_id'=>$role->id,'back_url'=>$BackUrl]);
                                    if($vendor_id == 1){
                                        $userUrl = route($routeName.'.vendors.index',['role_id'=>$role->id,'back_url'=>$BackUrl]);
                                    }*/
                                    ?>
                                    <a href="{{route($routeName.'.users.index',['role_id'=>$role->id,'back_url'=>$BackUrl])}}" title="Users">{{$total_user_by_roles}}</a> 
                                </td>   
                                <td>
                                   @if(CustomHelper::checkPermission('roles','view'))
                                   <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.roles.showRoleDetail',['id'=>$role->id])?>"  title="View role permission" data-fancybox data-type="ajax"><i class="fas fa-eye"></i></a>
                                   @endif

                                   @if(CustomHelper::checkPermission('roles','edit'))
                                    <a href="{{route($routeName.'.roles.roles_save', ['id'=>$role->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endif
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            else{
                ?>
                <div class="alert alert-warning">There are no Records at the present.</div>
                <?php
            }
            ?>
        </div>
    @endcomponent