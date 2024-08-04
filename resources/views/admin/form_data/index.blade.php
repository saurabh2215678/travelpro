@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Forms Data - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    @slot('headerBlock')

   
    @endslot

    <?php 
    $range_filters = [
'custom' => 'Custom',
'yesterday' => 'Yesterday',
'today' => 'Today',
'tomorrow' => 'Tomorrow',
'this_week' => 'This Week',
'last_week' => 'Last Week',
'this_month' => 'This Month',
'last_month' => 'Last Month',
'last_7_days' => 'Last 7 days',
'last_30_days' => 'Last 30 days',
'this_year' => 'Current Year',
'last_year' => 'Previous Year',
];

$range = old('range', request()->range);
$dateType = old('dateType', request()->dateType);

if(empty($from)) {
$from = old('from', request()->from);
}
if(empty($to)) {
$to = old('to', request()->to);
}

    $back_url = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();

    $addBtn = 'Add Form';
    $title = 'Manage Forms Data';

    $formID = (request()->has('formID'))?request()->formID:'';
  //$searchDateType=array('M.created_at'=>'Created On','M.updated_at'=>'Updated On');
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2>{{$title}}         
            </h2>
            @include('snippets.errors')
            @include('snippets.flash')
               <div class="row">

            <div class="col-md-12">
                <div class="bgcolor">

                    <div class="table-responsive">

                        <form  method="GET">
                            <input type="hidden" name="formID" value="{{$formID}}">
    <div class="col-sm-2 mb-0">
       <label>Range</label><br/>
       <select name="range" class="form-control range">
        <option value="">Select</option>
        @foreach($range_filters as $k => $v)
        <option value="{{$k}}" {{(!empty($range) && $range == $k)?'selected':''}} >{{$v}}</option>
        @endforeach
    </select>    
</div>

<div class="col-sm-2 {{ (!empty($range) && $range=='custom') ? '' : 'd-none' }} dateDiv">
  <label>From Date</label><br/>
  <input type="text" id="from" name="from" class="form-control from_date datePicker" placeholder="From Date" value="{{$from}}" autocomplete="off">
  
</div>
<div class="col-sm-2 {{ (!empty($range) && $range=='custom') ? '' : 'd-none' }} dateDiv">
  <label>To Date</label><br/>
  <input type="text" id="to" name="to" class="form-control to_date datePicker" placeholder="To Date" value="{{$to}}" autocomplete="off">
  
</div>
<br>
   <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn_admin">Search</button>
                            <a href="{{url($routeName.'/forms_data?formID='.$formID)}}" class="btn resetbtn btn-primary btn_admin2" >Reset</a>
                        </div>
</form>
</div>
</div></div></div>

            <?php    $dashbordFormElements  = CustomHelper::getDashbordFormElements($formID,1); 

            #prd($dashbordFormElements);
            if(!empty($forms) && count($forms) > 0){
                ?>
                <div class="table-responsive mt50">
                    <table class="table table-bordered">
                        <tr>
                            @foreach($dashbordFormElements as $fr)

                            <th>{{$fr->label}}</th>
                            @endforeach
                           <th>Submited Date</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        foreach($forms as $rec){
                             $unserializeData = unserialize($rec->data);
                            #prd($unserializeData);

                            //$content = CustomHelper::wordsLimit($blog->content,35);
                            //$category_name = (isset($blog_category->name))?$blog_category->name:'';
                            ?>
                            <tr>
                                @foreach($dashbordFormElements as $fr)

                                <td>
                              <?php 
                             

          if($fr->type=='datepicker'){
            if(isset($unserializeData['input'.$fr->id])){
              if(isset($unserializeData['input'.$fr->id])){
             $unserializeData['input'.$fr->id]= CustomHelper::DisplayDateFormat($unserializeData['input'.$fr->id]); 
            }
        }
          }else if($fr->type=='timepicker'){
            if(isset($unserializeData['input'.$fr->id])){
             if(isset($unserializeData['input'.$fr->id])){
             $unserializeData['input'.$fr->id]= CustomHelper::DisplayTimeFormat($unserializeData['input'.$fr->id]); 
            }
        }
          }else if($fr->type=='datetimepiker'){
            if(isset($unserializeData['input'.$fr->id])){
             $unserializeData['input'.$fr->id]= CustomHelper::DisplayDateTimeFormat($unserializeData['input'.$fr->id]); 
                #prd($unserializeData['input'.$fr->id]);
            }
          }else if($fr->type=='checkbox'){
            if(isset($unserializeData['input'.$fr->id])){
             $unserializeData['input'.$fr->id]=implode(', ',$unserializeData['input'.$fr->id]); 
                #prd($unserializeData['input'.$fr->id]);
            }
          }else if($fr->type=='file'){
            if(isset($unserializeData['input'.$fr->id])){
                        $imageExt=array('jpg','jpeg','png');   
                        $ext = pathinfo($unserializeData['input'.$fr->id], PATHINFO_EXTENSION);
                          if(in_array($ext,$imageExt)){
                        $unserializeData['input'.$fr->id]='<a href="'.asset($unserializeData['input'.$fr->id]).'" target="_blank"><img src="'.url($unserializeData['input'.$fr->id]).'" style="width:75px;"></a>';
                          }else{
             $staticImage='/assets/img/document.png';
             $unserializeData['input'.$fr->id]='<a href="'.asset($unserializeData['input'.$fr->id]).'" target="_blank"><img src="'.url($staticImage).'" style="width:75px;"></a>';   
                          }               
            }
          }


      echo  (isset($unserializeData['input'.$fr->id]))?$unserializeData['input'.$fr->id]:'';  ?>
                                </td>
                                  @endforeach
                               <td>{{  CustomHelper::DisplayDateTimeFormat($rec->created_at)}}</td>   

                                <td>
                                    <a href="{{ route($routeName.'.forms_data.showDetail', ['id'=>$rec->id] ) }}" title="View Event"><i class="fas fa-eye"></i></a></li>
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
            <div class="alert alert-warning">There are no Records found.</div>
            <?php
        }
            ?>

        </div>

    </div>


@slot('bottomBlock')
<link rel="stylesheet" type="text/css" href="{{ url('css/jquery-ui.css') }}"/ >
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ url('datePicker/jquery-ui-timepicker-addon.js') }}"></script>

<link rel="stylesheet" type="text/css"href="{{url('assets/admin/css/jquery.datetimepicker.css')}}">
    <script type="text/javascript"src="{{url('assets/admin/js/jquery.datetimepicker.js')}}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });


    $( function() {

$('.date_field').on('change', function() {
//alert( this.value );
if(this.value != ''){
$('.rangediv').removeClass('d-none');
}else{
$('select[name=range]').val('');
$('input[name=from]').val('');
$('input[name=to]').val('');
$('.rangediv').addClass('d-none');
$('.dateDiv').addClass('d-none');
}
});

$('.range').on('change', function() {

if(this.value == 'custom'){
$('.dateDiv').removeClass('d-none');
}else{
$('.dateDiv').addClass('d-none');
$('input[name=from]').val('');
$('input[name=to]').val('');
}
});
});
</script>
<script type="text/javascript">
            $(function () {
                var from = '{{$from}}';
                var to = '{{$to}}';

                $('#from').datetimepicker({
                    maxDate: '0',
                    date: new Date(from),
                    format: 'd-m-Y',
                    timepicker: false,
                    lang: 'en'
                });
                $('#to').datetimepicker({
                    maxDate: '0',
                    date: new Date(to),
                    format: 'd-m-Y',
                    timepicker: false,
                    lang: 'en'
                });
            });
        </script>

@endslot

@endcomponent