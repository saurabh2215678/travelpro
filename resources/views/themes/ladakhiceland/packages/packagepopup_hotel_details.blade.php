<?php 
//prd($accommodation);
if(isset($accommodation) && !empty($accommodation)){
$accoRating = 0;
$storage = Storage::disk('public');
$accommodation_path = 'accommodations/';
$destination_id = (!empty($accommodation->accommodationDestination))? $accommodation->accommodationDestination->name : "";
$accommodation_facility = (isset($accommodation->accommodation_facility))?json_decode($accommodation->accommodation_facility):[];
$accommodation_name = isset($accommodation->name) ? $accommodation->name:'';
$accommodation_brief = isset($accommodation->brief) ? $accommodation->brief:'';
$accommodation_description = isset($accommodation->description) ? $accommodation->description:'';
$accommodation_star = isset($accommodation->star_classification) ? $accommodation->star_classification:'';
$accommodation_slug = isset($accommodation->slug) ? $accommodation->slug:'';
$accommodation_image = isset($accommodation->image) ? $accommodation->image:'';

$accommodationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

if(!empty($accommodation_image)){
   if($storage->exists($accommodation_path.$accommodation_image)){
      $accommodationSrc = asset('/storage/'.$accommodation_path.$accommodation_image);
   }
}

$accommFacObj = "";
    if(!empty($accommodation_facility)){
        $accommFacObj = App\Models\AccommodationFacility::whereIn('id',$accommodation_facility)->get();
    }

$accoRating = isset($accommodation->star_classification) ? $accommodation->star_classification:'';
$address = isset($accommodation->address) ? $accommodation->address:'';
    $i = 1;

?>
<div class="popup_inner hotel_detail_popup">
   <div class="row">
      <div class="col-md-6">
         <div class="hote_detail_box">
            <div class="title">
              {{ $accommodation_name }}
            </div>
            <div class="star_box">
                        
                           <ul class="rating_list">
                            <?php
                            if(is_numeric($accoRating) && $accoRating > 0){
                            for ($i = 0; $i < $accoRating; $i++) { ?>
                            <li rating="{{ $accoRating }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <!-- <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li> -->
                  <?php } }?>
                      </ul>
                     </div>
                    <p class="address_accm"><img class="map-i" src="{{asset(config('custom.assets').'/images/map.png') }}" style="width: 10px;position: relative;top: 2px;"> {{ $destination_id }}<br><br>
                        {{$address}}
                    </p>
                    <?php 
                       if(!empty($accommFacObj) && !($accommFacObj->isEmpty())){ ?>
                    <div class="facilities">
                       <div class="facility_title">Facilities : </div>
                       <span class="hotel_facilities" data-value="Bed Only">
                        <!-- <img class="map-i" src="{{asset(config('custom.assets').'/images/check-mark.png') }}" style="display: block;padding-bottom: 10px;"> -->
                        <?php 
                       if(!empty($accommFacObj) && !($accommFacObj->isEmpty())){
                                foreach ($accommFacObj as $fscility) {
                                    $fscilityArr[] = $fscility->title;
                                }
                                echo implode(', ', $fscilityArr);
                            }
                            ?> 
                        </span>
                    </div>
                <?php } ?> 
            <div class="hote_description table-responsive">
                {!! $accommodation_description !!}
               <!-- <table class="table table-bordered table theme_table flight_table">
                  <thead class="red_bg">
                     <tr>
                        <th colspan="4">Booking Informations</th>
                     </tr>
                  </thead>
                  <thead class="yellow_bg">
                     <tr>
                        <th>Type of Plan</th>
                        <th>Double</th>
                        <th>Single</th>
                        <th>Extra BED</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>American Plan(Breakfast, Lunch, Dinner &amp; Room)</td>
                        <td>23994/</td>
                        <td>18507/-</td>
                        <td>7130/-</td>
                     </tr>
                     <tr>
                        <td>MAP(Breakfast, Dinner &amp; Room)</td>
                        <td>18507/-</td>
                        <td>15763/-</td>
                        <td>5580/-</td>
                     </tr>
                     <tr>
                        <td>Continental Plan(Bed &amp; Breakfast)</td>
                        <td>15546/-</td>
                        <td>15546-</td>
                        <td>4030/-</td>
                     </tr>
                     <tr>
                        <td>European Plan(Room Only)</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                     </tr>
                  </tbody>
               </table> -->
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="popup_slider">
            <img class="img-responsive" src="{{ $accommodationSrc }}">
         </div>
      </div>
   </div>
</div>
<?php }?>
<style type="text/css">
body {
    font-family: 'SF-Pro-Display', sans-serif;
    padding-top: var(--header-height);
    color: var(--text-color);
    margin: 0;
    overflow-x: hidden;
    height: 100%;
}
.img-responsive {
    display: block;
    max-width: 100%;
    height: auto;
    width: 100%;
}
.row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: nowrap;
    flex-direction: row;
    align-content: start;
}
.col-md-6 {
    width: 50%;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
    overflow: hidden;
}
.hote_detail_box {
    height: 340px;
    overflow: auto;
}
.title {
    display: inline-block;
    font-size: 20px;
    color: #3e3e3e;
    font-weight: 700;
    margin-bottom: 0;
    padding-right: 10px;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
table.table thead th {
    border-top: 0;
    font-size: 16px;
    font-weight: 700;
}
table.table th, table.table td {
    padding-top: 1.1rem;
    padding-bottom: 1rem;
}
.table th {
    color: #fff;
    background-color: #01b3a7;
    border-color: #32383e;
}
table th {
    font-size: .9rem;
    font-weight: 400;
}
thead.red_bg tr th {
    font-size: 20px;
    padding: 0.75rem 1rem;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
    border-top: 0;
    border-left: 0;
}
tbody tr td:nth-child(1) {
    border-left: 1px solid #dee2e6;
}
.table-bordered td{
	padding:10px;
}
.rating_list {
    display: flex;
    list-style: none;
    /* margin: 0; */
    padding: 0;
}
/* .rating_list li:not(.active) svg {
    opacity: 0.4;
} */
.rating_list svg {
    height: 12px;
    fill: #01b3a7;
}
.hotel_facilities {
    display: inline-block;
    font-size: 11px;
    margin-right: 20px;
}
.facility_title {
    font-weight: 500;
    color: #000;
    margin-bottom: 10px;
}
.facilities {
    padding-bottom: 15px;
}

@media (min-width: 992px){
.row-cols-2{
    flex:0 0 auto;
    width:50%
}
.w-30{
    flex:0 0 auto;
    width:30%
}
.w-70{
    flex:0 0 auto;
    width:70%
}
.col-md-6 {
    width: 50%;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
    float: left;
}
}

@media (max-width: 767px){
.row {
    display: block;
}
.col-md-6 {
    width: 100%;
}

}
</style>
<script type="text/javascript">
   $('.rating_list').each(function(){
      var ratingValue = parseInt($(this).attr('rating'))
      $(this).children('li').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });
</script>