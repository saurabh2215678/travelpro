<template>
<FancyboxWrapper>
    <div  ref="destPageRef">
        <destination-tab-wrapper>
            <div class="detail_tab_section">
                <div class="container">
                    <ul class="scrollspy_btns" ref="navRef">
                        <li class="uppercase" @click="handleActive('dest_about')"><a href="#dest_about" class="active">About {{destination?.name}}</a></li>
                        <template v-if="destination.destinationTypes" v-for="destinationType in destination.destinationTypes">
                            <li class="uppercase" @click="handleActive(destinationType.slug)"><a :href="`#${destinationType.slug}`">{{destinationType?.name}}</a></li>
                        </template>
                        <li v-if="destination.images" class="uppercase" @click="handleActive('dest_gallery')"><a href="#photo_gallery">Photo Gallery</a></li>
                    </ul>
                </div>
            </div>
            <div class="inner_content_block">
                <div class="container">
                    <div id="dest_about" class="tabcontent" scrollspy-section>
                        <div class="text-3xl font-semibold pt-5 pb-5">About</div>
                        <div class="destination_about_content flex gap-x-3">
                            <div class="dest_desc w-full pr-5 text-gray-500">
                                <div class="desti_img" v-if="destination.imageSrc">
                                <img :src="destination.imageSrc" :alt="destination.name">
                                </div>
                                <div class="" v-html="destination.description"></div>
                            </div>
                            <!-- <div class="desti_img w-1/2" v-if="destination.imageSrc">
                                <img :src="destination.imageSrc" :alt="destination.name">
                            </div> -->
                        </div>
                    </div>
    
                    <template v-if="destination.destinationTypes" v-for="destinationType in destination.destinationTypes">
                        <div :id="destinationType.slug" class="tabcontent" scrollspy-section>
                            <div class="theme_title pt-5 pb-0">
                            <div class="top_title">{{destinationType?.name}}</div>
                            </div>
                            <template v-if="destinationType.destination_info" v-for="info in destinationType.destination_info">
                                <destination-accordian-box :id="info.id" :isOpened="activeAccordian == info.id" :setActiveAccordian="setActiveAccordian" :info="info"/>
                            </template>
                        </div>
                    </template>
    
                    <!-- <div id="photo_gallery" class="tabcontent" v-if="destination.images" scrollspy-section>
                        <div class="text-3xl font-semibold pt-5 pb-5">Gallery</div>
                        <FancyboxWrapper class="destinaton_images_fancy">
                                <div class="swiper" ref="sliderRef">
                                <div class="swiper-wrapper">
                                    <template v-for="image in destination.images">
                                    <a :href="image.imageSrc" class="gallery_popup swiper-slide" data-fancybox="destination-gallery"><img class="rounded-md" :src="image.imageSrc" :alt="image.name"></a>
                                </template>
                            </div>
                            </div>
                            <div class="slider_btns">
                        <div class="slider_btn" ref="sliderPrevRef"><i class="fa-solid fa-chevron-left"></i></div>
                        <div class="slider_btn" ref="sliderNextRef"><i class="fa-solid fa-chevron-right"></i></div>
                    </div>
                        </FancyboxWrapper>
                    </div> -->
                </div>
            </div>
        </destination-tab-wrapper>
    </div>
</FancyboxWrapper>
     
</template>
<style>
    .scroll-spy-active{scroll-padding: 7.5rem; scroll-behavior: smooth;}
</style>
<script>
import DestinationAccordianBox from '../../components/destination/DestinationAccordianBox.vue';
import FancyboxWrapper from '../../components/FancyboxWrapper.vue';
import { DestinationTabWrapper } from '../../styles/DestinationTabWrapper';

export default {
    name:'DestinationTab',
    
    data() {
        return {
            activeAccordian : '',
        }
    },
    methods:{ 
        setActiveAccordian(_activeId){
            this.activeAccordian = _activeId;
        },
        
    },
    mounted(){ 
        const destContainer = this.$refs.destPageRef;
        const galleryItems = destContainer.querySelectorAll('.blocks-gallery-item');
        galleryItems.forEach((item, index)=>{
            item.querySelector('a').setAttribute('data-fancybox', 'dest-gallery');
        });

        const navEl = this.$refs.navRef;
        const navLinks = navEl.querySelectorAll('a');
        const sections = document.querySelectorAll('[scrollspy-section]');

        function updateActiveNavItem() {
            const scrollPosition = window.scrollY;

            for (let i = 0; i < sections.length; i++) {
                const sectionTop = sections[i].offsetTop - 130;
                const sectionBottom = sectionTop + sections[i].offsetHeight - 70;

                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                navLinks.forEach((link) => link.classList.remove('active'));
                navLinks[i].classList.add('active');
                }
            }
        };

        document.documentElement.classList.add('scroll-spy-active');
        window.addEventListener('scroll', updateActiveNavItem);

        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderPrevRef;
        var sliderPrevBtn = this.$refs.sliderNextRef;

        new Swiper(sliderElem, {
            slidesPerView: 5,
            spaceBetween:20,
            loop: false,
            speed:1000,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                640: {
                    slidesPerView: 2.5,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
            navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
            watchSlidesProgress: true,
        });
    },
    beforeUnmount() {
        document.documentElement.classList.remove('scroll-spy-active');
    },

    components:{
        FancyboxWrapper,
        'destination-tab-wrapper' : DestinationTabWrapper,
        'destination-accordian-box' : DestinationAccordianBox
    },
    props: ["destination"],
}
</script>