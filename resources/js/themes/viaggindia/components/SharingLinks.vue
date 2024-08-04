<template>
    
    <div class="share-section w-6/12">
        <div class="flex py-0 pt-3 pl-1.5 items-center">
            <div class="bg_share">
                <p class="mr-3">Share It On:</p>
                <div class="footer_bottom_right">
                    <ul class="social_icon">
                        <li class="facebook" v-if="sharing_links.facebook"><a
                            :href="sharing_links.facebook"
                            target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li class="twitter" v-if="sharing_links.twitter"><a
                            :href="sharing_links.twitter"
                            target="_blank"><i class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li class="whatsapp" v-if="sharing_links.whatsapp"><a
                            :href="sharing_links.whatsapp"
                            target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                        </li>
                        <li class="instagram" v-if="sharing_links.instagram"><a
                            :href="sharing_links.instagram"
                            target="_blank"><i class="fa-brands fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</template>
<script>
import {hidePopup} from '../utils/commonFuntions';
import { store } from '../store.js';
import FormTopMenu from './FormTopMenu.vue';

export default {
    name:"SharingLinks",
    created() {
        // console.log('store', store.popupActive);
    },
    data() {
        return {
            store
        }
    },
    methods:{
        closeClick(){
            hidePopup();
            
        },

    },
    components: {
      FormTopMenu
    },

    props: ["sharing_links"],
}
</script>