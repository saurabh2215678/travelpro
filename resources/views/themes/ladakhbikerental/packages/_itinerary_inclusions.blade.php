@if(!empty($inclusions))
<?php $i_path = 'inclusion/';
$itenary_pdf = $itenary_pdf??false;
?>
<ul class="include_list inclusions" style="clear: both;display: block;width: 100%;height: 80px;">
   @foreach($inclusions as $inclusion_key => $inclusion_val)

   <li data-text="<?php echo $inclusion_val;?>" style="float: left;text-align: center;margin-right: 15px;">
      <?php
      if($itenary_pdf) {
         $i_image = public_path(config('custom.assets').'/images/ico3.png');
      } else {
         $i_image = asset(config('custom.assets').'/images/ico3.png');
      }
      if(!empty($inclusion_key) && File::exists(public_path('storage/inclusion/'.$inclusion_key))){
         if($itenary_pdf) {
            $i_image = public_path('storage/'.$i_path.'thumb/'.$inclusion_key);
         }else {
            $i_image = url('storage/'.$i_path.'thumb/'.$inclusion_key);
         }
      } ?>
      <img style="width:50px;" src="{{$i_image}}"/>
      <span><?php echo ucwords($inclusion_val);?></span>
   </li>
   @endforeach
</ul>
@endif