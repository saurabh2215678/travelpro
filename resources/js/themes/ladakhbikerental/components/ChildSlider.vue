<template>
    <div v-if="child.images && child.images.length > 0" class="swiper" ref="sliderRef">
        <div class="swiper-wrapper">
            <template v-for="image in child.images">
                <a :href="image.imageSrc" data-fancybox="destination-gallery" class="swiper-slide">
                    <img :src="image.thumbSrc" :alt="image.title">
                </a>
            </template>
        </div>
        <div class="slider_btns" >
            <div class="theme-prev" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="theme-next" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ChildSlider',
        mounted(){
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                speed:1000,
                spaceBetween:30,
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                watchSlidesVisibility: true
          });
        },
        props:['child']
    }
</script>