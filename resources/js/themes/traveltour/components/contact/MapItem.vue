<template>
    <MapItemWrapper>
        <div class="map_item_left lg:w-2/5">
            <h5 class="text-base font-semibold pb-1">RESERVATION OFFICE:</h5>
            <h4 class="text-base font-semibold pb-1 theme_clr_text">OVERLAND ESCAPE</h4>
            <p class="text-sm mb-3">Skara Main Road, Skara Yokma <br>Leh, 194101, Ladakh, India</p>
            <p class="text-sm mb-3"><strong>Ticket Booking or Enquiry:</strong> +91 8491947052 / 8491947053</p>
            <p class="text-sm mb-3"><strong>Hotel Booking or Enquiry:</strong> +91 9858394405 / 8491947044</p>
            <p class="text-sm mb-3"><strong>Tour &amp; Trek Booking or Enquiry:</strong> +91 8491947039 / 8491947040</p>
            <p class="text-sm"><strong>Email:</strong>  <a href="mailto:info@overlandescape.com">info@overlandescape.com</a> / <a href="mailto:overland.escape@gmail.com">overland.escape@gmail.com</a></p>
            <p>Skara Main Road, Skara Yokma <br>Leh, 194101, Ladakh, India</p>
            <p><strong>Ph:</strong> +91 1982 252105 / +91 1982 252108</p>
            <p><strong>Mob:</strong>  +91 9858394400 </p>
            <p><strong>Email:</strong>  info@overlandescape.com / overland.escape@gmail.com</p>
        </div>

        <div class="map_item_right lg:w-3/5 md:w-full">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d28856.981911955812!2d82.9822685!3d25.30008!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x398e31fbea52d981%3A0x8dab35e88bd9ddb!2sBalaji%20Travels%20-%20Varanasi%20Tour%20Package%20%7C%20Best%20Travel%20Agency%20%7C%20Travel%20Agent%20%7C%20Tour%20Operator%20%7C%20Tour%20%26%20Travel!5e0!3m2!1sen!2sin!4v1691392264028!5m2!1sen!2sin" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </MapItemWrapper>
</template>
<script>
import { MapItemWrapper } from '../../styles/MapItemWrapper';

export default {
    name:'MapItem',
    components:{
        MapItemWrapper
    }
}
</script>