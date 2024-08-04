@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? 'Order Details'}}
@endslot    
@slot('meta_description')
{{ $meta_description ?? 'Order Details'}}
@endslot
@slot('headerBlock')
@endslot
@slot('bodyClass')
Order_details_class
@endslot
<?php
$emailData = '';
$package_id = '';
?>

<section class="theme_form" style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg')}});">
   <div class="container">
      <div class="theme_form_wrap">
         <div class="form_title heading1 title_font">Booking Order Details</div>
         <div class="theme_form_inner">
           @include('snippets.front.flash')
            {{ Form::open(array('route' => 'order-details','method' => 'post','id'=>'order-details-frm','class' => '','autocomplete' => 'off')) }}


            <tr>
            <td width="806" valign="top" class="innersec">
              <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="50%">
                  <tr>
                      <td><b>Name : </b></td>
                      <td></td>
                  </tr>

                  <tr>
                      <td><b>Email : </b></td>
                      <td></td>
                  </tr>

                  <tr>
                      <td><b>Phone No: </b></td>
                      <td></td>
                  </tr>

                  <tr>
                      <td><b>Country: </b></td>
                      <td>
                      </td>
                  </tr>
                  <tr>
                      <td><b>Payment Details: </b></td>
                      <td>
                      </td>
                  </tr>
                  <tr>
                      <td><b>Payment Status: </b></td>
                      <td></td>
                  </tr>
                  <tr>
                      <td><b>Discount : </b></td>
                      <td></td>
                  </tr>

                  <tr>
                      <td><b>Discount Type : </b></td>
                      <td></td>
                  </tr>
                  <tr>
                      <td><b>Sub Total Ammount : </b></td>
                      <td></td>
                  </tr>
                 
                  <tr>
                      <td><b>Total Ammount: </b></td>
                      <td></td>
                  </tr>
              </table>
            </td>
            </tr> 

            <input type="hidden" name="package_id" id="package_id" value="{{ $package_id}}">
            <button type="submit" class="btn" name="submit">Submit</button>
            {{ Form::close() }}
         </div>
      </div>
   </div>

</section>
@slot('bottomBlock')

@endslot
@endcomponent