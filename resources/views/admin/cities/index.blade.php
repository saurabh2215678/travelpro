	@component('admin.layouts.main')

    @slot('title')
        Admin - Manage States - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_name = (request()->has('name'))?request()->name:'';

   
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>City List ({{ $cities->total() }})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('cities','add'))
            <a href="{{ url($routeName.'/cities/save') }}" class="btn_admin"><i class="fa fa-plus"></i> Add City</a>
            @endif
    </div>
    </div>
    


    <div class="centersec">
    <div class="bgcolor">

            <div class="table-responsive">

                <form class="form-inline" method="GET">
                    <div class="col-md-4">
                        <label>City Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                   <!--  <div class="clearfix"></div> -->

                    <div class="col-md-6">
                        <label</label><br/>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{url($routeName.'/cities')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
 

            @include('snippets.errors')
            @include('snippets.flash')
        <?php

        if(!empty($cities) && $cities->count() > 0){
            ?>
            <div class="table-responsive">

            {{ $cities->appends(request()->query())->links('vendor.pagination.default') }}

                <table class="table table-bordered">
                    <tr>
                        <th class="">Name</th>
                        <th class="">State</th>
                        <th class="">Country</th>
                        <th class="">Status</th>
                        <th class="">Action</th>
                    </tr>
                    <?php
                    $storage = Storage::disk('public');
                    foreach ($cities as $city){

                        $stateData = "";
                        $stateName = "";
                        $countryData = "";
                        $countryName = "";

                        if(!empty($city->cityState)){
                            $stateData = $city->cityState;
                            $stateName = $stateData->name;
                        }

                        if(!empty($city->cityCountry)){
                            $countryData = $city->cityCountry;
                            $countryName = $countryData->name;
                        }
                        ?>

                        <tr>
                            <td>{{$city->name}}</td>
                            <td>{{$stateName}}</td>
                            <td>{{$countryName}}</td>
                            <td><?php  echo ($city->status==1)?'Active':'Inactive';  ?></td>
                            
                            <td>
                                @if(CustomHelper::checkPermission('cities','edit'))
                                <a href="{{route($routeName.'.cities.save', ['id'=>$city->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $cities->appends(request()->query())->links('vendor.pagination.default') }}
            <?php
                    }
                    else{
                ?>
                <div class="alert alert-warning">There are no Records at the present.</div>
                <?php
            }
            ?>
            </div>

        </div>
@endcomponent

 