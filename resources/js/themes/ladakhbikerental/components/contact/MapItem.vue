<template>
    <MapItemWrapper>
        <div v-if="address" class="location w-1/2" v-html="address">
        </div>

        <div class="map_item_right w-1/2">
            <ContactFormWrapper>
                <div class="form_box">
                <h4>Contact Form</h4>
                <formShortCode slug="[contact_us]" class="left_form" />
            </div>
            </ContactFormWrapper>
        </div>
    </MapItemWrapper>
    <div class="map_item_right w-full" v-if="map" v-html="map"></div>
</template>
<script>
import { MapItemWrapper } from '../../styles/MapItemWrapper';
import formShortCode from '../../components/formShortCode.vue';
import {ContactFormWrapper} from '../../styles/ContactFormWrapper';
export default {
    name:'MapItem',
     created() {
    },
    components:{
        formShortCode,
        MapItemWrapper,
        ContactFormWrapper
        
    },
    props : ['address','map']
}

</script>