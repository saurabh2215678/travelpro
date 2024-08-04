<template>
    <div class="slide-item">
        <button class="slide_btn" @click="isActive = !isActive" :class="isActive ? 'active' : ''" :disabled="isDisabled">
            {{ btnTitle }}
            <i v-if="!isDisabled" class="fa-solid fa-chevron-down"></i>
        </button>
        <SlideUpDown v-model="isActive" :duration="400" responsive>
            <div class="slide_content"><slot/></div>
        </SlideUpDown>
    </div>
</template>
<script>
import SlideUpDown from 'vue3-slide-up-down';
export default {
    name:"SlideBox",
    data(){
        return{
            isActive: this.active ? true : false,
            isDisabled: this.disabled ? true : false
        }
    },
    components:{
        SlideUpDown,
    },
    props:['btnTitle', 'active', 'disabled', 'responsive']
}
</script>