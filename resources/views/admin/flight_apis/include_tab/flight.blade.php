<?php 
$RECAPTCHA_SITE_KEY ='';
$RECAPTCHA_SECRET_KEY ='';
$new_array =[];
     array_push($new_array,'TRIPJACK_API_KEY','TRIPJACK_API_URL');
    foreach ($settings as $key => $setting) {
        // echo $setting->name;
        // echo '<br>';
          foreach ($new_array as $name) {
             if($setting->name == $name){
                // echo $name ;
                // echo "<br>";
                 $$name = $setting->value;
                // echo $$name ;
            }
        }
    }
?>
    <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
           {{ csrf_field() }}
               <input type="hidden" class="form-control" name="id" value="1" />
            <div class="config_sec">
                <span>Tripjack Configuration</span>
                <!-- <img src="{{ url('/img/bank_payment.png') }}" alt=""> -->
            </div><br>
            <div class="form-group">
                <label for="title" class="control-label">TRIPJACK_API_KEY :</label>
                <input type="text" id="title" class="form-control" name="TRIPJACK_API_KEY" value="{{$TRIPJACK_API_KEY}}" />
            </div>
            <div class="form-group">
                <label for="title" class="control-label">TRIPJACK_API_URL :</label>
                <input type="text" id="title" class="form-control" name="TRIPJACK_API_URL" value="{{$TRIPJACK_API_URL}}"/>
            </div>
            <div class="submit_btn_box">
                <button type="submit" class="btn btn-success btn_admin" title="Submit"><i class="fa fa-save"></i>Submit</button>
            </div>
    </form>

