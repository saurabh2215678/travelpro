<template>
    <DestinationDetalisWrapper class="destinaton_images_fancy" id="photo_gallery">
        <div class="container">
        <div class="theme_title pt-5" v-if="title">
           <div class="font30 color_theme fw700 mb-8">{{ title }}</div> 
        </div>
        <FancyboxWrapper class="gallery">
            <ul class="blocks-gallery-grid">
                <li class="blocks-gallery-item" v-for="image in images">
                    <a :href="image.imageSrc" class="gallery_popup swiper-slide" data-fancybox="destination-gallery"><img class="rounded-md" :src="image.imageSrc" :alt="image.name"></a>
                </li>
           </ul>        
        </FancyboxWrapper>
    </div>
    </DestinationDetalisWrapper>
</template>
<script>

import { DestinationDetalisWrapper } from '../../styles/DestinationDetalisWrapper';
import FancyboxWrapper from '../FancyboxWrapper.vue';


export default {

    name:'DestinationDetalisGallery',
    created(){
        // console.log('ffff>', this.images);
    },
    components:{
    DestinationDetalisWrapper,
    FancyboxWrapper,
},
    props: ["images" , "title"],
}
</script>