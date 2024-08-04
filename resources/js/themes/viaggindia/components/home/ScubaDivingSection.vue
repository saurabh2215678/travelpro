<template>
    <ScubaDivingSectionWrapper>     
       <div class="container">
            <div class="excontent">
                <div class="md:w-9/12 mx-auto"> 
                    <!-- <div class="heading2 text-center">Pronto per un viaggio unico<br> in Su.India, Gujarat, Ladakh, Nepal.</div>
                    <p>Contattaci senza impegno per una consulenza o per un  preventivo su misura!</p> -->
                    <template v-if="widgetDetail.description">
                    <div v-html="widgetDetail.description"></div>
                    </template>
                    <ul class="cta_flex">
                    <li v-if="store.websiteSettings?.WHATSAPP1">
                    <a :href="`https://api.whatsapp.com/send?phone=${store.websiteSettings?.WHATSAPP1}`"><i class="fa-brands fa-whatsapp"></i>  {{showLangTitle('ChatWhatsapp')}}</a>
                    </li>
                    <li v-if="store?.websiteSettings?.SALES_PHONE">
                    <a :href="`tel:${store.websiteSettings?.SALES_PHONE}`"><i class="fa-solid fa-phone"></i> {{showLangTitle('DirectCall')}}</a>
                    </li>
                    <li v-if="store.websiteSettings?.SALES_EMAIL">
                    <a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`"><i class="fa-solid fa-envelope"></i> Email</a>
                    </li>
                    </ul>
                </div>

            </div>


           
        </div>
                <!-- <Link href="javascriptvaoi:(0)" class="readbtn">Read More</Link> -->
    </ScubaDivingSectionWrapper>
</template>
<script>
import { store } from '../../store';
import {ScubaDivingSectionWrapper} from '../../styles/ScubaDivingSectionWrapper';
import FancyboxWrapper from '../FancyboxWrapper.vue';
import { Link } from "@inertiajs/inertia-vue3";
import {showLangTitle} from '../../utils/commonFuntions';
export default {
    name : 'ScubaDivingSection',
    data() {
        return {
            store
        }
    },
    methods: {
        showLangTitle,
    },
    components:{
        ScubaDivingSectionWrapper,
        FancyboxWrapper,
        Link
    },
    props: ["widgetDetail"]

};
</script>