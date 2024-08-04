@component('admin.layouts.main')

@slot('title')
Admin - Manage Faq - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_question = (request()->has('question'))?request()->question:'';
$destination_id = (request()->has('destination'))?request()->destination:'';
$sub_destination_id = (request()->has('sub_destination'))?request()->sub_destination:'';
$old_status = (request()->has('status'))?request()->status:1;
$segment = (isset(request()->segment))?request()->segment:'';
?>



@if(!empty($tbl_name) && $tbl_name=='packages')
    <?php
        $package_id = $tbl_id;
        $active_menu = "packages".$package_id.'/'.$category;
    ?>
    @include('admin.includes.packageoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destinations')
    <?php
        $destination_id = $tbl_id;
        $active_menu = "destinations".$destination_id.'/'.$category;
    ?>
    @include('admin.includes.destinationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='accommodations')
    <?php
        $accommodation_id = $tbl_id;
        $active_menu = "accommodations".$accommodation_id.'/'.$category;
    ?>
    @include('admin.includes.accommodationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='cms_pages')
    <?php
        $id = $tbl_id;
        $active_menu = "cms".$id.'/'.$category;
    ?>
    @include('admin.includes.cmsmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='seo_meta_tags')
    <?php
        $module_config_id = $tbl_id;
        $active_menu = "module_config".$module_config_id.'/'.$category;
    ?>
    @include('admin.includes.modulemenu')
@endif
@if(!empty($tbl_name) && $tbl_name=='themes')
    <?php
        $theme_id = $tbl_id;
        $active_menu = "themes".$theme_id.'/'.$category;
    ?>
    @include('admin.includes.themefaqmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destination_packages')
    <?php
        $destination_id = $tbl_id;
        $active_menu = "destination_packages".$destination_id.'/'.$category;
    ?>
    @include('admin.includes.destinationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destination_activity')
    <?php
        $destination_id = $tbl_id;
        $active_menu = "destination_activity".$destination_id.'/'.$category;
    ?>
    @include('admin.includes.destinationoptionmenu')
@endif



<!-- Start Search box section     -->
<div class="top_title_admin">
    <div class="title">
        <h2>Manage Faqs({{$faqs->total()}})</h2>
    </div>
    <div class="add_button">
      @if(CustomHelper::checkPermission('faqs','add'))
      <a href="{{ route('admin.faqs.add', ['back_url'=>$BackUrl, 'tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Faq</a>
      @endif
  </div>
</div>


<div class="centersec">
        <div class="bgcolor ">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-4">
                    <label>Name:</label><br/>
                    <input type="text" name="question" class="form-control admin_input1" value="{{$old_question}}">
                </div>
                {{--<div class="col-md-3">
                    <label for="destination"> Destination:</label><br/>
                    <select class="form-control admin_input1" name="destination" id="destination">
                        <?php
                        $destination_id = old('destination',$destination_id);
                        if(!empty($destinations)){
                            $parent_destinations = $destinations->where('parent_id', 0);
                            ?>
                            <option value="">Select Destination</option>
                            <?php
                            if(!($parent_destinations->isEmpty())){
                                foreach ($parent_destinations as $destination_it){
                                    $selected = '';
                                    if($destination_it->id == $destination_id){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                                <?php }}}?>
                            </select>
                        </div>--}}
                        
                        {{--<div class="col-md-3">
                            <label for="sub_destination">Sub Destination:</label><br/>
                            <select class="form-control admin_input1" name="sub_destination" id="sub_destination">
                                <option value="">Select Sub Destination</option>
                            </select>
                        </div>--}}
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
                            <input type="hidden" name="tid" class="form-control admin_input1" value="{{$tbl_id}}">
                            <input type="hidden" name="module" class="form-control admin_input1" value="{{$tbl_name}}">
                            <input type="hidden" name="category" class="form-control admin_input1" value="{{$category}}">
                            <input type="hidden" name="segment" class="form-control admin_input1" value="{{$segment}}">
                            <button type="submit" class="btn btn-success">Search</button>
                            <a href="{{route($routeName.'.faqs.index',['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment])}}" class="btn_admin2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>


            <!-- End Search box Section -->
            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($faqs) && count($faqs) > 0){
                ?>
                <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th>Question</th>
                            {{--<th>Destination</th>--}}
                            <th>Sort order</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th width="120">Action</th>
                        </tr>

                        <?php
                        foreach($faqs as $faq){
                            ?>
                            <tr>
                                <td>{{$faq->question}}</td>
                                {{--<td><?php echo (!empty($faq->faqParentDestination)) ? $faq->faqParentDestination->name.' >> ' : ''; ?><?php echo (!empty($faq->faqDestination)) ? $faq->faqDestination->name : ''; ?></td>--}}
                                <td>{{$faq->sort_order}}</td>
                                <td>
                                     <?php if($faq->status == 1){ ?>
                                    <span style="color:green">Active</span>
                                    <?php   }else{  ?><span style="color:red">Inactive</span>
                                 <?php } ?>
                                </td>
                                <td>{{ CustomHelper::DateFormat($faq->created_at, 'd/m/Y') }}</td>

                                <td>
                                    {{--<a href="{{route($routeName.'.faqs.view',[$faq['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>--}}
                                    <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.faqs.view',[$faq['id']]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>

                                    @if(CustomHelper::checkPermission('faqs','edit'))
                                    <a href="{{ route($routeName.'.faqs.edit', ['id'=>$faq->id, 'tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    @endif
                                    @if(CustomHelper::checkPermission('faqs','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.faqs.delete', ['id'=>$faq->id, 'tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this faq?');" class="delForm">
                                        {{ csrf_field() }}
                                    </form>
                                    @endif
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $faqs->appends(request()->query())->links('vendor.pagination.default') }}
                <?php
            }else{
                ?>
                <div class="alert alert-warning">There are no faq at the present.</div>
            <?php } ?>
        </div>
    @slot('bottomBlock')
    <script type="text/javascript">
    var _token = '{{ csrf_token() }}';

    $(document).ready(function () {

        var destinationId = $('#destination').val();
        var subDestinationId = '{{ $sub_destination_id }}';

        populateSubDestination(destinationId,subDestinationId);

        $('#destination').on('change',function(e) {
            var destination_id = e.target.value;
            populateSubDestination(destination_id);
        });
    });

    function populateSubDestination(destination_id,setDestination=""){
        $.ajax({
            url:"{{ route('common.ajax_load_sub_destinations') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
                destination_id: destination_id
            },
            success:function (data) {
                let text = "";
                $('#sub_destination').empty();
                text +=  `<option value="">---Select Sub Destination---</option>`
                text += data.options;
                /*data.states.forEach(function(item, index){
                    text +=  `<option value="${item.id}">${item.name}</option>`
                })*/
                $("#sub_destination").html(text)
            },complete:function(){
                $('#sub_destination').val(setDestination);
            }
        });
    }
</script>
    <script type="text/javascript">
        $(document).on("click", ".sbmtDelForm", function(e){
            e.preventDefault();

            $(this).siblings("form.delForm").submit();                
        });
    </script>

    @endslot
    @endcomponent