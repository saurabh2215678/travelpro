<template>
    <section class="single_package_details p-0">
        <div class="single_package_info row w-full">
            <div class="w-full">
                <div class="grid_gallery_row">
                    <div class="left_area w-1/2">
                    </div>
                    <div class="right_area w-1/2">
                    </div>
                    <div class="view-more-img">

                        <div class="view-more-text">See all photos <!--<span>3</span>--></div>

                    </div>
                    <div class="bottom_area">
                    </div>
                </div>
                <ul class="grid_gallery">
                    <li> 
                        <div class="gallery_images">
                            <div class="gallery_wrapper w-1/2 ">
                                <a data-fancybox="main_gallery"
                                :data-caption="accommodation.name"
                                class="gallery_popup"
                                :href="accommodation.hotelSrc">
                                <img
                                :src="accommodation.hotelSrc"
                                :alt="accommodation.name" class="img_responsive theme_radius">
                            </a>
                            <div class="gallery_text">
                                <div class="title para_lg0">{{accommodation.name}}</div>
                                <p class="para_md"></p>
                            </div>
                        </div>
                    </div>
                </li>
                <li v-for="accommodationImage in this.accommodationImages">
                    <div class="gallery_images">
                        <div class="gallery_wrapper w-1/2 ">
                            <a data-fancybox="main_gallery"
                            :data-caption="accommodationImage.title"
                            class="gallery_popup"
                            :href="accommodationImage.src">
                            <img
                            :src="accommodationImage.thumbSrc"
                            :alt="accommodationImage.title" class="img_responsive theme_radius">
                        </a>
                        <div class="gallery_text">
                            <div class="title para_lg0"></div>
                            <p class="para_md"></p>
                        </div>
                    </div>
                </div>
            </li>                
        </ul>
        <div class="left_side_inner">
        </div>
    </div>

</div>
</section>
</template>


<script>
import { store } from '../../store.js';

export default {
    created() {
        this.accommodationImages = this.accommodation.accommodationImages;
    },
    data() {
        return {
            store,
            accommodationImages:{},
        }
    },
    methods:{

    },
    props: ["className","accommodation"],
}
</script>