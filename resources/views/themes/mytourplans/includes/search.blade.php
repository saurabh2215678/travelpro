<?php
$sdest = (request()->has('sdest')) ? request()->sdest : '';
$sno_of_days = (request()->has('sno_of_days')) ? request()->sno_of_days : '';
$smonth = (request()->has('smonth')) ? request()->smonth : '';

$noOfDays = array('1-4'=>'1-4 Days','5-8'=>'5-8 Days','9-15'=>'9-15 Days','16-30'=>'16-30 Days');
$route_name = $route_name ?? 'packageListing';
?>
<div class="headsearch">
        <div class="container">
           <div class="headsearchbg">
            <form action="{{ route($route_name) }}" method="GET" name="searchForm" class="max-lg:flex-wrap"> 
               <div class="selectoption pr-0.5 md:w-1/4 max-md:w-full">
                  <!-- <i class="mapicon"></i> -->
                  <select name="sdest" class=" pl-3 pr-7 text-sm"> 
                     <option value="">Where do you want to go? </option>
                     {!!CustomHelper::getDestinationOptions('', $sdest, ['show_active'=>true])!!}
                  </select>
               </div>
               
               <div class="selectoption pr-0.5 md:w-1/4 max-md:w-full">
                  <!-- <i class="themepackicon"></i> -->
                  <select name="sno_of_days" class=" pl-3 pr-5 text-sm">
                     <option value="">No. of Days?</option>
                     <?php if(!empty($noOfDays)){ 
                      foreach($noOfDays as $nod_k=>$nod_v){
                        $smw_selected = '';
                        if($sno_of_days==$nod_k)
                        {
                          $smw_selected = 'selected';
                        }
                        ?>
                        <option value="<?php echo $nod_k; ?>" <?php echo $smw_selected; ?> ><?php echo $nod_v; ?></option>
                      <?php }} ?>
                           
                  </select>
               </div>

               <div class="selectoption pr-0.5 md:w-1/4 max-md:w-full">
                  <!-- <i class="calendericon"></i> -->
                  <select name="smonth" class="pl-5 text-sm">
                    <option value="">Select Month</option>
                    <?php 
                    for ($i=0; $i < 12; $i++) {
                      $month = date('Y-m',strtotime('+'.$i.'month'));
                      $smw_selected = '';
                      if($smonth==$month) {
                        $smw_selected = 'selected';
                      }
                    ?>
                    <option value="{{$month}}" {{$smw_selected}} >{{CustomHelper::DateFormat($month,'M Y')}}</option>
                    <?php } ?>
                    <option value="not_decision" <?php if($smonth=='not_decision'){echo 'selected';} ?> >Not decided</option>                     
                  </select>
               </div>
               <div class="searchbtn">
                <input type="submit" class="btn btn-primary" value="SEARCH"> 
               </div>
               </form>
            </div>
        </div>
    </div>