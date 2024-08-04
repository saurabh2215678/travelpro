<template>
    <accordian-box-wrapper class="accordia_box" @click="this.handleClick">
        <div class="open_text flex">
            <i class="fa fa-angle-down" :class="isOpened ? 'upaarow' : ''"></i>
            <div class="w-4/5">
                <div class="brief p-3">
                <h2 class="text-lg font-semibold">{{info.title}}</h2>
                {{info.brief}}
            </div>
            </div>
            <div class="w-1/5">
                <div class="image p-3" v-if="info.imageSrc">
                <img :src="info.imageSrc" :alt="info.title">
            </div>
            </div>
        </div>
        <div class="description" v-html="info.description" v-if="isOpened">
        </div>
    </accordian-box-wrapper>
</template>

<script>
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import SlideUpDown from 'vue3-slide-up-down';

const AccordianBoxWrapper = styled.div`
& .description{
    display:flex;
    flex-direction: column;
    margin:0;
}
`;

export default {
    name:'DestinationAccordianBox',
    created() {
    },
    mounted(){
    },
    data() {
        return {
            store,
            isOpened: false
        }
    },
    methods:{ 
        handleClick(){
            this.isOpened = !this.isOpened;
            // this.setActiveAccordian(this.id)
        }
    },
    components:{
        'accordian-box-wrapper' : AccordianBoxWrapper,
        'slide-up-down' : SlideUpDown
    },
    watch:{
    },
    props: ["id", "info"],
}
</script>