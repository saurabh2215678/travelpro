<template>
    <HotelDetailGalleryWrapper class="HotelDetailGallery">
        <div class="container">
            <FancyboxWrapper>
                <ul class="gallery_box">
                    <li>
                        <a 
                        data-fancybox="main_gallery"
                        class="gallery_popup"
                        :data-caption="accommodation?.name"
                        :href="accommodation?.hotelSrc">
                            <img :src="accommodation?.hotelSrc" :alt="accommodation?.name"/>
                        </a>
                    </li>

                    <li v-for="item in accommodation?.accommodationImages">
                        <a 
                        data-fancybox="main_gallery"
                        class="gallery_popup"
                        :data-caption="item?.title"
                        :href="item?.src">
                            <img :src="item?.thumbSrc" :alt="item?.title"/>
                        </a>
                    </li>

                </ul>
            </FancyboxWrapper>
        </div>
    </HotelDetailGalleryWrapper>
</template>
<script>
import {HotelDetailGalleryWrapper} from '../../styles/HotelDetailGalleryWrapper'
import FancyboxWrapper from '../FancyboxWrapper.vue';
export default {
    name : 'HotelDetailGallerySection',
    components:{
        HotelDetailGalleryWrapper,
        FancyboxWrapper
    },
    props: ['accommodation']
}
</script>