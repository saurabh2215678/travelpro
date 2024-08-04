@component('themes.theme-1.layouts.main')

@slot('title')
   Blox
@endslot

@slot('headerBlock')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css">


@endslot

<!-- to attach class on body tag of page -->
@slot('bodyClass')
  index-page
@endslot

<?php
// $storage = Storage::disk('public');

?>
      <section class="p60 account">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="account-sec">
                <div class="as-left">
                  <div class="login">
                  <div class="account-form">
                    <!-- <span class="arrow"></span> -->
                    <div class="title">Payment details</div>

                 <div class="alert alert-danger print-error-msg" style="display:none">
                      <ul></ul>
                  </div>

                    <form method="post" id="paymentFrm">

                      {{ csrf_field() }}

                      <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="number" name="phone" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" name="amount" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea name="comment" class="form-control" rows="3"></textarea>
                      </div>

                      <div class="btn-sec">
                        <button type="submit" id="email_continue_btn" class="btn sub-btn text-left">Submit <span></span></button>
                      <!--  -->
                      </div>
                    </form>

                  </div>

                </div>               

                </div>
                

              </div>


              


            </div>
          </div>
        </div>
      </section>



@slot('footerBlock')
 
@endslot


@endcomponent