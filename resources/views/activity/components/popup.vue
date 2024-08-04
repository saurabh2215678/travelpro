<template>
    <div class="popup_main_box" :class="className" v-if="store.popupActive" >
        <div class="backdrop" @click="closeClick"></div>
        <div class="popup_wrap">
             <button class="close_popup text-white/[.3] text-xl" @click="closeClick"><i class="fa-solid fa-xmark"></i></button>
            <slot/>
        </div>
    </div>
</template>


<script>
import {hidePopup} from '../utils/commonFuntions';
import { store } from '../store.js';

export default {
    name:"popup",
    created() {
        console.log('store', store.popupActive);
    },
    data() {
        return {
            store,
        }
    },
    methods:{
        closeClick(){
            hidePopup();
        }
    },
    props: ["className"],
}
</script>