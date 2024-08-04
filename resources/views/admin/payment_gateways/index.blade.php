@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php

    $setting_id = 0;
    $name = (request('name'))?(request('name')):'';
    $old_status = (request()->has('status'))?request()->status:1; 
    // $setting_id = (isset($settingRow->id))?$settingRow->id:0;
    // $name = (isset($settingRow->name))?$settingRow->name:'';
    // $title = (isset($settingRow->title))?$settingRow->title:'';
    // $payment_key = (isset($settingRow->payment_key))?$settingRow->payment_key:'';
    // $payment_salt = (isset($settingRow->payment_salt))?$settingRow->payment_salt:'';
    // $payment_base_url = (isset($settingRow->payment_base_url))?$settingRow->payment_base_url:'';
    // $status = (isset($settingRow->status))?$settingRow->status:'';
    // $type = (isset($settingRow->type))?$settingRow->type:'text';

    $action_url = url('admin/payment-gateways');

    $name_readonly = '';

    $form_heading = 'Payment Gateways';

    if(is_numeric($setting_id) && $setting_id > 0){
        $action_url = url('admin/payment-gateways', $setting_id);
        $name_readonly = '';

        $form_heading = 'Update Payment Gateways';
    }

    ?>


    <div class="row p20">

    <div class="col-md-12">
            <!-- <h1>Payment Gateways</h1> -->

            @include('snippets.errors')
            @include('snippets.flash')
            </div>

                <div id="messages"></div>
                <div class="col-md-12">
                <div class="topsearch">

                <h4 style="padding: 0 15px;">{{ $form_heading }}</h4>
                    <form method="GET" action="{{ $action_url }}" accept-charset="UTF-8" role="form" class="form-inline heightform" enctype="multipart/form-data">
                       
                        <div class="col-md-3">
                            <label for="name" class="control-label"><b>Name:</b></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $name) }}" maxlength="255" />

                            <?php
                            /*@include('snippets.errors_first', ['param' => 'amount'])*/
                            ?>
                        </div>
               
                   

                        <div class="col-md-3">
                            <label class="control-label">Status:</label><br/>
                            <select name="status" class="form-control admin_input1">
                                <option value="">--Select--</option>
                                <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                                <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                            </select>
                        </div>

                    <div class="col-md-3">
                        <label></label><br>
                        <button type="submit" class="btn btn_admin">Search</button>
                        <a href="{{url('admin/payment-gateways') }}" class="btn_admin2">Reset</a>
                    </div>

                    </form>

                </div>
            </div>

        <div class="col-md-12">

            @if (count($settings) > 0)
                <table class="table table-striped">
                    <tr>
                        <th>Name</th>
                       <!--  <th>Payment Key</th>
                        <th>Payment Salt</th> -->
                        <th>Status</th>
                        <th>Edit</th>
                        <th>Display Name</th>
                    </tr>
                    @foreach ($settings as $setting)
                        <tr>
                            <td>
                            {{$setting['name']}}
                            </td>                            

                           <!-- <td>
                                {{(!empty($setting['payment_key']))?$setting['payment_key']:''}}
                            </td>

                            <td>
                                {{(!empty($setting['payment_salt']))?$setting['payment_salt']:''}}
                            </td> -->

                            <td>
                                <?php   $checked = '';
                                if ($setting['status'] == 1) {

                                $checked = 'checked'; ?>
                                        <span style="color:green">Active</span>
                                <?php } else { ?>
                                        <span style="color:red">Inactive</span>
                                <?php } ?>
                            </td>

                            <td>
                            <?php
                            /*
                            <a href="{{ route('admin.settings.show', $setting['id']) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                            
                            <a href="{{ url('admin/payment-gateways/add', $setting['id']) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>*/
                            ?>

                            <label class="switch">
                               <input type="checkbox" class="show_toggle" name="status" id="" value="{{$setting['status']}}" data-id="{{$setting['id']}}" {{$checked}}>
                               <span class="slider round"></span>
                            </label>
                        </td>
                            <td>
                            <form action=""  id="payment-gateways-form_{{$setting['id']}}" class="payment-gateways-form">
                                <input type="hidden" class="form-control" name="id" value="{{$setting['id']}}">
                                <input type="text" class="form-control" name="display_name" id="display_name" value="{{ $setting['display_name'] ? $setting['display_name'] : $setting['name']}}" placeholder="Display Name" style="width: auto; display: inline-block;">
                                <input type="button" rel="{{$setting['id']}}" id ='submit_ajax'class="bnt-submit btn_admin"  name="submit" value="Submit">
                            </form>

                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="alert alert-warning">There are no payment gateways at the present.</div>
            @endif
        </div>

    </div>

@endcomponent

<!-- Image Modal -->
  <div class="modal fade" id="imgModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
          <img src="" style="width:100%">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- End - Image Modal -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
      $(document).on("click", ".show_img", function(){
        //var setting_id = $(this).data("id");

        var img_src = $(this).find("img").attr("src");

        $("#imgModal .modal-body img").attr("src", img_src);
        $("#imgModal").modal();
      });

    // Product

    $('.switch input').each(function() {
    $(this).click(function(e) {
        e.preventDefault();
        var val = $(this).val();
        var elemt = $(this)
        var id = $(this).attr('data-id');
        var name = $(this).attr('name');
        var model_name = 'Project';
        var _token = '{{ csrf_token() }}';
        // alert(name);
        // return false;
            var text = 'Are you sure change status'
          
            Swal.fire({
                title: 'Are you sure?',
                text: "Changing the Payment Gateway status",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, I am sure!',
                cancelButtonText: "cancel",
            }).then((result) => {

                var checked = $(this).is(':checked');
                if (result.isConfirmed) {

                    var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{route('admin.payment-gateways.change_status')}}",
                        type: "POST",
                        data: {
                            status: val,
                            id: id
                        },
                        dataType: "JSON",
                        headers: {
                            'X-CSRF-TOKEN': _token
                        },
                        cache: false,
                        beforeSend: function() {},
                        success: function(response) {
                            if (response.success) {


                                if (response.status == 1) {
                                    Swal.fire({
                                        icon: 'success',
                                        text: 'Payment Gateway  Activated Successfully',
                                    });
                                    elemt.prop('checked', true);
                                    elemt.val('1');
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        text: 'Payment Gateway  Deactivated Successfully',
                                    });
                                    elemt.removeAttr('checked');
                                    elemt.val('0');
                                }
                                     window.location.reload();


                            }
                        }
                    });
                
            }

            })   
        
    });
});


  </script>
  <script type="text/javascript">

    $(document).on('click','#submit_ajax',function(){

        var id=$(this).attr('rel'); 

        console.log(id);
  var _token = '{{ csrf_token() }}';
 formulario =  $("#payment-gateways-form_"+id);
        url =  "{{route('admin.payment-gateways.submitDispalyName')}}";
            $.ajax({
            method: "POST",
            url: url,
             headers: {
                            'X-CSRF-TOKEN': _token
                        },
            data: formulario.serialize()
            })
        .done(function( response ) {
                msg = ' <div class="alert alert-success" role="alert">'+response.msg+'</div>';
                $("#messages").html(msg);
            //alert('hiugi');
        });

    });

</script>
<style>.heightform .form-control {max-width: 100%;width:100%}textarea{width:100%}</style>