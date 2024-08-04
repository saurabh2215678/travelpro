<template>
     <div class="flight_form rental_search_form">
                
                <div class="select_from_wrap location_input ml-2">
                    <input type="text" name="bookingId" placeholder="Booking ID:" >
                    <!-- <ModelListSelect  :list="cityList" v-model="cityModal" placeholder="Where To ?" option-value="id" option-text="name"/> -->
                </div>
                <div class="search_btn">
                 <button type="submit">Search</button>
              </div>
            </div>
</template>

<script>
import { store } from '../../grandindiatour/store.js';
import 'v-calendar/dist/style.css';
import { DatePicker } from 'v-calendar';
import 'vue-search-select/dist/VueSearchSelect.css';
import { ModelListSelect } from 'vue-search-select';



export default {
    created() {

        

    },

    beforeUnmount() {
        //console.log(this.$el)
      },

      data() {
        return {
            store,
        }
    },
    methods : {
        
    },

    mounted () {
    },
    beforeDestroy (){

    },
    watch:{
        
    },
    components: {
        DatePicker,
        ModelListSelect,
    },
   props:[""]
}


</script>
