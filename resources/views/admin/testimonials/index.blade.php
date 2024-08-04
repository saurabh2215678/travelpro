@component('admin.layouts.main')

@slot('title')
Admin - Manage Testimonials - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot


<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_name = (request()->has('name'))?request()->name:'';
$old_status = (request()->has('status'))?request()->status:''; 
$old_featured = (request()->has('featured'))?request()->featured:''; 
$back_url = (request()->has('back_url'))?request('back_url'):'';

$storage = Storage::disk('public');
$path = 'testimonials/';

    //prd(url()->current());
?>

<div class="top_title_admin">
    <div class="title">
    <h2>Testimonials ({{$testimonials->total()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('testimonials','add'))
            <a href="{{ route($routeName.'.testimonials.add').'?back_url='.$BackUrl }}" class="btn_admin"><i class="fa fa-plus"></i> Add Testimonial</a>
            @endif
    </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                     <div class="col-md-3">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Featured:</label><br/>
                        <select name="featured" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_featured == '1')?'selected':'' }}>Active</option>
                        </select>
                    </div>

                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-3">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route($routeName.'.testimonials.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    
<!-- End Search box Section -->



        @include('snippets.errors')
        @include('snippets.flash')        

        <?php
        if(!empty($testimonials) && $testimonials->count() > 0){
            ?>
            <div class="table-responsive">           
                <?php /*{{ $testimonials->appends(request()->query())->links('vendor.pagination.default') }} */ ?>

                <table class="table table-striped table-bordered table-hover">
            
                    <tr>
                        <th>Package</th>
                        <th>Destination</th>
                        <th>Title</th>
                        <th style="width: 8%;">Name</th>
                        <th>Email</th>
                        <th>Date on</th>
                        <th>Image</th>
                        <th style="width: 5%;">Display Order</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th style="width: 8%;">Action</th>
                    </tr>
                    <?php
                    $payment_id = 0;
                    foreach($testimonials as $testimonial){
                        $name = (isset($testimonial->name))?$testimonial->name:'';

                        $description = (isset($testimonial->description))?$testimonial->description:'';

                        $description = strip_tags($description);

                        if(strlen($description) > 33){
                            $description = substr($description, 0, 30).'...';
                        }

                       // $featured = ($testimonial->featured == '1')?'Active':'Inactive';
                        //$status = ($testimonial->status == '1')?'Active':'Inactive';

                        $testimonial_id = $testimonial->id;

                        $date_on = CustomHelper::DateFormat($testimonial->date_on, 'd F Y');
                       
                        ?>

                        <tr>
                            <td><?php 

                        $package_ids = json_decode($testimonial->package_id,true)??[];
                        $showPackage = CustomHelper::showPackage($package_ids);
                        echo implode(', ',$showPackage);
                        ?>
                            
                        </td>
                            <td><?php 
                        $destination_ids = json_decode($testimonial->destination_id,true)??[];
                        $showDestination = CustomHelper::showDestination($destination_ids);
                        echo implode(', ',$showDestination);
                        ?></td>
                            <td>{{$testimonial->title}}</td>
                            <td>{{$testimonial->name}}</td>
                            <td>{{$testimonial->email}}</td>
                            <td>{{$date_on}}</td>
                            <td>
                                <?php
                                    $image = $testimonial->image;
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
                            <?php /*<td>{{$featured}}</td> */ ?>
                            <td>{{ $testimonial->sort_order}}</td>
                            <td><?php if($testimonial->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>
                            <td><?php  if($testimonial->featured == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   } ?> 
                            </td>

                            <td>
                                {{--<a href="{{ route($routeName.'.testimonials.view', [$testimonial_id, 'back_url'=>$BackUrl]) }}" title="View Testimonials" ><i class="fas fa-eye"></i></a>--}}
                                @if(CustomHelper::checkPermission('testimonials','view'))
                                <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.testimonials.view', [$testimonial_id]) ?>" data-fancybox data-type="ajax" title="View Testimonials"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('testimonials','edit'))
                                <a href="{{ route($routeName.'.testimonials.edit', [$testimonial_id, 'back_url'=>$BackUrl]) }}" title="Edit" ><i class="fas fa-edit"></i></a>
                                @endif

                                {{-- <a href="{{ route($routeName.'.images.upload',['tid'=>$testimonial->id,'module'=>'testimonials']) }}" target="_blank"><i class="fas fa-image"  title="Add Gallery Images"></i></a> --}}
                                
                                @if(CustomHelper::checkPermission('testimonials','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm" data-id="{{$testimonial_id}}" title="Delete" ><i class="fas fa-trash-alt"></i></a>

                                <form name="deleteForm{{$testimonial_id}}" method="POST" action="{{ route($routeName.'.testimonials.delete', $testimonial_id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to delete this testimonial?');" class="delForm">
                                    {{ csrf_field() }}
                                </form>
                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>

                {{ $testimonials->appends(request()->query())->links('vendor.pagination.default') }}
            </div>
            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">There are no testimonials at the present.</div>
            <?php
        }
        ?>

        

        <br /><br />

    </div>
</div>

@slot('bottomBlock')
<script style="text/javaScript">
	
  $(document).on("click", ".sbmtDelForm", function(){

    var testimonial_id = $(this).data("id");
    $("form[name='deleteForm"+testimonial_id+"']").submit();
});
</script>
@endslot

@endcomponent


