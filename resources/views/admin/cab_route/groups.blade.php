@component('admin.layouts.main')
@slot('title')
   Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endslot
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';
$old_status = (request()->has('status'))?request()->status:1;
$storage = Storage::disk('public');
?>
<div class="top_title_admin">
    <div class="title">
    <h2>Route Group List ({{ $res->total() }})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('cabs','add'))
       <a href="{{ url($routeName.'/cab_route/addgroup') }}" class="btn_admin"><i class="fa fa-plus"></i>Add Route group</a>
    @endif
    </div>
    </div>
<div class="centersec">
    <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
                        <label>Route Group Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>

                    <div class="col-md-2">
                    <label>Status</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                
                    <div class="col-md-6">
                    <label></label><br/>
                        <button type="submit" class="btn_admin">Search</button>
                        <a href="{{url($routeName.'/cab_route/groups')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        @include('snippets.errors')
       @include('snippets.flash')
    </div>
   <?php
   if(!empty($res) && $res->count() > 0){
       ?>
       <div class="table-responsive mt5">
       {{ $res->appends(request()->query())->links('vendor.pagination.default') }}
           <table class="table table-bordered">
               <tr>
                   <th class="">Name</th>
                   <th class="">Route Count</th>
                   <th class="">Cab</th>
                   <th class="">Inclusions</th>
                   <th class="">Exclusions</th>
                   <th class="">Terms</th>
                   <th class="">Sharing</th>
                   <th class="">Status</th>
                   <th class="">Action</th>
               </tr>

                <?php foreach ($res as $rec) { 
                    $inclusion = $rec->inclusion ?substr($rec->inclusion,0,30): '';
                    $exclusion = $rec->exclusion ?substr($rec->exclusion,0,30): '';
                    $term = $rec->term ?substr($rec->term,0,30): '';
                    
                    ?>
                   <tr>
                       <td>{{$rec->name}}</td>
                       <td><a href="{{route($routeName.'.cab_route.index',['cab_route_group_id'=> $rec->id])}}"></i>{{$rec->cab_routes_count}}</a></td>
                        <td>
                        <?php 
                        $cab_json_data = $rec->cab_data;
                        $cab_arr = [];
                        if(isset($cab_json_data) && !empty($cab_json_data)){
                        $cabArr =json_decode($cab_json_data)??'';
                        foreach ($cabArr as $cabData) {
                        $cab_arr[] = $cabData->name;
                        }
                        echo implode(', ', $cab_arr);
                        }
                        ?> 
                        </td>
                        <td>{!!$inclusion!!}...</td>
                        <td>{!!$exclusion!!}...</td>
                        <td>{!!$term!!}...</td>
                         <td><?php  if($rec->sharing == 1){ ?>
                             <i class="fas fa-check" style="color:green"></i>
                         <?php } ?>
                        </td>
                       <td><?php if($rec->status == 1){ ?>
                        <span style="color:green">Active</span>
                        <?php   }else{  ?><span style="color:red">Inactive</span>
                        <?php } ?>
                       </td>
                       <td>
                           @if(CustomHelper::checkPermission('cabs','edit'))
                           <a href="{{route($routeName.'.cab_route.groupedit', ['id'=>$rec->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                           @endif
                       </td>
                   </tr>
               <?php } ?>

           </table>
       </div>
       {{ $res->appends(request()->query())->links('vendor.pagination.default') }}
       <?php }else{ ?>
           <div class="alert alert-warning">There are no Records at the present.</div>
       <?php } ?>
       </div>
   </div>
@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
$(".tooltip_title").tooltip();

$( function() {
   $( ".to_date, .from_date" ).datepicker({
       dateFormat:'dd/mm/yy',
       changeMonth:true,
       changeYear: true,
       yearRange:"1950:0+"
   });
});

$(document).on("click", ".product_status", function(){

   var curr_sel = $(this);

   var product_id = $(this).attr('data-id');
   var curr_status = $(this).attr('data-status');
   
   var conf = confirm("Are you sure to change status of this Product?");
   
   if(conf){

       _token = '{{csrf_token()}}';
       
       $.ajax({
           url: "{{ url($routeName.'/products/ajax_change_status') }}",
           method: 'POST',
           data:{product_id, curr_status},
           dataType:"JSON",
           headers:{'X-CSRF-TOKEN': _token},
           beforeSend:function(){},
           success: function(resp) {
               if(resp.success == true){
                   curr_sel.parent().html(resp.status_html);
                   //curr_sel.remove();
               } else {

               }
           },
           error: function(resp) {

           }
       });
   }
});

</script>
@endslot

@endcomponent