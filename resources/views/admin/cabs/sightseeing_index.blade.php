@component('admin.layouts.main')

@slot('title')
Admin -{{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';
$route_type = (request()->has('route_type'))?request()->route_type:'';
$old_route_type = old('route_type',$route_type);
$old_status = (request()->has('status'))?request()->status:1;
$origin = (request()->has('origin'))?request()->origin:'';
$origin = old('origin', $origin);
$destination = (request()->has('destination'))?request()->destination:'';
$destination = old('destination', $destination);
$cab_route_group_id = (request()->has('cab_route_group_id'))?request()->cab_route_group_id:'';
$cab_route_group_id = old('cab_route_group_id', $cab_route_group_id);
$storage = Storage::disk('public');
$cab_route_types = config('custom.cab_route_types');
?>
<div class="top_title_admin">
  <div class="title">
    <h2>{{ $page_heading }} ({{ $res->total() }})</h2>
  </div>
  <div class="add_button">
    @if(CustomHelper::checkPermission('cabs','add'))
    <a href="{{ route($routeName.'.cabs.sightseeing_add') }}" class="btn_admin"><i class="fa fa-plus"></i> Add Cab Sightseeing</a>
    @endif
  </div>
</div>
<div class="centersec">
  <style>
    .autoCounter_table tbody tbody{counter-reset: section;}
    .autoCounter_table tbody tr{counter-increment: section;}
    .autoCounter_table tbody td:nth-child(7):before{content: counter(section);}
  </style>
  <div class="bgcolor">
    <div class="table-responsive">
      <form class="form-inline" method="GET">
        <div class="col-md-2">
          <label>Sightseeing Name:</label><br/>
          <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
        </div>
        <div class="col-md-2{{$errors->has('origin')?' has-error':''}}">
          <label for="FormControlSelect1">Origin</label><br/>
          <select name="origin" class="form-control admin_input1 select2">
            <option value="">--Select Origin--</option>
            @if($cab_cities)
            @foreach($cab_cities as $row)
            <option value="{{$row->id}}" {{($row->id==$origin)?'selected':''}}>{{$row->name}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-2{{$errors->has('destination')?' has-error':''}}">
          <label for="FormControlSelect1">Destination</label><br/>
          <select name="destination" class="form-control admin_input1 select2">
            <option value="">--Select Destination--</option>
            @if($cab_cities)
            @foreach($cab_cities as $row)
            <option value="{{$row->id}}" {{($row->id==$destination)?'selected':''}}>{{$row->name}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <!-- <div class="clearfix"></div> -->
        <div class="col-md-2">
          <label>Status</label><br/>
          <select name="status" class="form-control admin_input1">
            <option value="">--Select--</option>
            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
          </select>
        </div>
        <!--  <div class="clearfix"></div> -->
        <div class="col-md-4" style="padding: 5px 15px;">
          <label></label>
          <br>
          <button type="submit" class="btn_admin">Search</button>
          <a href="{{route($routeName.'.cabs.sightseeing')}}" class="btn_admin2">Reset</a>
        </div>
      </form>
    </div>
    @include('snippets.errors')
    @include('snippets.flash')
    <br><div class="ajax_msg"></div>
  </div>
  <?php
  if(!empty($res) && $res->count() > 0){
    ?>
    <div class="table-responsive mt5">
      {{ $res->appends(request()->query())->links('vendor.pagination.default') }}
      <table class="table table-bordered">
        <tr>
          <th class="">Sort</th>
          <th class="">Sightseeing Name</th>
          <th class="">Origin</th>
          <th class="">Destination</th>
          <th class="">Places covered in sightseeing</th>
          <th class="">Duration/<br/>Distance</th>
          <th class="">Discount Group</th>
          <th class="">Desc</th>
          <th class="">Featured</th>
          <th class="">Sharing</th>
          <th class="">Status</th>
          <th class="">Action</th>
        </tr>
        <tbody class="row_position">
          <?php 
          $i=0;
          foreach ($res as $rec) {
            $CabRouteToGroup = $rec->CabRouteToGroup;
            $i++;
            ?>
            <tr class="ui-sortable-handle" id="{{$rec->id}}">
              <td><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></i></span> </span></td>
              <td>{{$rec->name}}</td>
              <td>{{$rec->originCity->name??''}}</td>
              <td>{{$rec->destinationCity->name??''}}</td>
              <td>{{$rec->places}}</td>
              <td>
                {{$rec->duration}} 
                <?php if(!empty($rec->distance)) { ?>
                  <br />({{$rec->distance}}km)
                <?php } ?>
              </td>
            <td>{{$rec->ModuleDiscountCategory ? $rec->ModuleDiscountCategory->name : $default_group}}</td>
            <td><?php  if($rec->description){ ?>
                <i class="fas fa-check" style="color:green"></i>
              <?php   } ?>
            </td>
            <td><?php  if($rec->featured == 1){ ?>
              <i class="fas fa-check" style="color:green"></i>
            <?php } ?>
          </td> 
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
        @if(CustomHelper::checkPermission('cabs','view'))
        <a href="{{route($routeName.'.cabs.sightseeing_view', [$rec['id'],'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
        @endif

        @if(CustomHelper::checkPermission('cabs','edit'))
        <a href="{{route($routeName.'.cabs.sightseeing_edit', ['id'=>$rec->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
        @endif
      </td>
    </tr>
  <?php } ?>
</tbody>
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
$( ".row_position" ).sortable({  
  delay: 150,
  handle: ".handle",
  stop: function(event, ui) {
    var selectedData = new Array();  
    $('.row_position>tr').each(function() {  
      selectedData.push($(this).attr("id"));  
    });  
    updateCabRoute(selectedData);  
  }  
});
</script>
<script>  
  function updateCabRoute(data) {  
    var _token = '{{ csrf_token() }}';
    $.ajax({  
      url : "{{ route('admin.cab_route.update_cab_route') }}",
      type : 'POST',  
      data:{data:data},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      beforeSend:function(){
        $(".ajax_msg").html("");
        $(".ajax_msg").show();
      },
      success: function(response) {
        $(".ajax_msg").html(response.msg).hide();
        $(".ajax_msg").html(response.msg).fadeIn();
        setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
      } 
    })  
  }
</script>
@endslot
@endcomponent