<template>
    <accordian-box-wrapper class="accordia_box">
        <div class="open_text flex">
            <!-- <i class="fa fa-angle-down" :class="isOpened ? 'upaarow' : ''"></i> -->
            <div class="brief pr-3" :class="!info.imageSrc ? 'w-full' :''">
                <h2 class="text-lg font-semibold">{{info.title}}</h2>
                <p class="text-sm text-gray-500">{{info.brief}}</p>
                <div class="description text-gray-500 text-sm pt-2" v-html="info.description"> </div>
            </div>
            <div class="image p-3" v-if="info.imageSrc">
                <h2 class="text-lg font-semibold">{{info.title}}</h2>
                <img class="rounded-md" :src="info.imageSrc" :alt="info.title">
            </div>

        </div>
        
    </accordian-box-wrapper>
</template>

<script>
import { AccordianBoxWrapper } from '../../styles/AccordianBoxWrapper';

export default {
    name:'DestinationAccordianBox',
    data() {
        return {
            // isOpened: false
        }
    },
    methods:{ 
        handleClick(){
            // this.isOpened = !this.isOpened;
            // this.setActiveAccordian(this.id)
        }
    },
    
    
    components:{
        'accordian-box-wrapper' : AccordianBoxWrapper
    },
    watch:{
    },
    props: ["id", "info"],
}
</script>