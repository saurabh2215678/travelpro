<template>
     <destination-tab-wrapper class="tab-panel">
        <div class="xl:container xl:mx-auto">
            <div class="tab">
                <button class="tablinks uppercase" :class="activeId == 'dest_about' ? 'active' : ''" @click="handleActive('dest_about')">About {{destination?.name}}</button>
                <template v-if="destination.destinationTypes" v-for="destinationType in destination.destinationTypes">
                    <button class="tablinks uppercase" :class="activeId == destinationType.slug ? 'active' : ''" @click="handleActive(destinationType.slug)">{{destinationType?.name}}</button>
                </template>
                <button v-if="destination.images && destination.images.length > 0" class="tablinks uppercase" :class="activeId == 'dest_gallery' ? 'active' : ''" @click="handleActive('dest_gallery')">Photo Gallery</button>
            </div>
            <div id="dest_about" class="tabcontent" v-if="activeId == 'dest_about'">
                <div class="">
                    <div class="destination_about_content">
                        <div class="desti_about_img" v-if="destination.imageSrc">
                            <img :src="destination.imageSrc" :alt="destination.name">
                        </div>
                        <div class="destination_txt_area text-justify font-normal p-1" v-html="destination.description">
                        </div>
                    </div>
                </div>
            </div>

            <template v-if="destination.destinationTypes" v-for="destinationType in destination.destinationTypes">
                <div :id="destinationType.slug" class="tabcontent" v-if="activeId == destinationType.slug">
                    <template v-if="destinationType.destination_info" v-for="info in destinationType.destination_info">
                        <destination-accordian-box :id="info.id" :isOpened="activeAccordian == info.id" :setActiveAccordian="setActiveAccordian" :info="info"/>
                    </template>
                </div>
            </template>
            <FancyboxWrapper>
            <div id="dest_gallery" class="tabcontent" v-if="activeId == 'dest_gallery' && destination.images">
                <div class="flex flex-wrap">
                    <template v-for="image in destination.images">
                        <a :href="image.imageSrc" class="gallery_popup" data-fancybox="destination-gallery"><img :src="image.imageSrc" :alt="image.name"></a>
                    </template>
                </div>
            </div>
            </FancyboxWrapper>
        </div>
    </destination-tab-wrapper>
</template>

<script>
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import DestinationAccordianBox from '../../components/destination/DestinationAccordianBox.vue';
import FancyboxWrapper from '../../components/FancyboxWrapper.vue';


const DestinationTabWrapper = styled.section`
& .tabcontent{display:block;padding-top: 0;}
& {padding: 1rem;}
& .desti_about_img {
    /* max-width: 100%; */
    margin: 1rem 0 1rem 2rem;
}
& #dest_gallery a {
    width: 33%;
    height: 110px;
}
& .tabcontent {
    margin-top: 1rem;
}
& .tab button{
    font-size: 1rem;
}
`;

export default {
    name:'DestinationTab',
    created() {

    },
    mounted(){

    },
    data() {
        return {
            store,
            activeId : 'dest_about',
            activeAccordian : '',
        }
    },
    methods:{ 
        handleActive(_activeId){
            this.activeId = _activeId;
        },
        setActiveAccordian(_activeId){
            this.activeAccordian = _activeId;
        }
    },
    components:{
        FancyboxWrapper,
        'destination-tab-wrapper' : DestinationTabWrapper,
        'destination-accordian-box' : DestinationAccordianBox
    },
    props: ["destination"],
}
</script>