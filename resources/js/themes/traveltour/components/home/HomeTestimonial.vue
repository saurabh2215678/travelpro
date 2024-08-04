<template>
    <TestimonialWrapper>
        <img src="../../assets/images/testimonial_bg.jpg" class="bg_testimonial" alt="">
        <div class="testimonial_main_box">
            <div class="testimonial_top">
                <div class="testimonial_head_content">
                    <p class="font19 color_secondary">Happy Customer</p>
                    <h3 class="font36">What Our Custmer’s Say</h3>
                </div>
                <img src="../../assets/images/quote.png" alt="">
            </div>
            <div class="testimonial_slider_wrap">
                <div class="testimonial_slider_box swiper" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="item in testimonials">
                          <div class="testi_item">
                            <p class="font14" v-html="item.description"></p>
                            <div class="testimonial_item_bottom">
                                <div class="profile_left">
                                    <img :src="item.thumbSrc" alt="">
                                </div>
                                <div class="profile_right">
                                    <p class="font23 fw600">{{ item.name }}</p>
                                    <p class="font19">Los Angles, CA</p>
                                </div>
                            </div>
                          </div>  
                        </div>
                    </div>
                </div>
                <div class="testimonial_nav_dots" ref="sliderPagination"></div>
            </div>
        </div>
    </TestimonialWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
const TestimonialWrapper = styled.section`
position:relative;
& .bg_testimonial{
    width: 100%;
}
& .testimonial_main_box {
    width: 43%;
    position: absolute;
    bottom: 0;
    background-color: var(--white);
    padding: 3rem;
}
& .testimonial_top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    &>img {
        width: 3.688rem;
    }
}
& .testimonial_head_content h3 {
    font-family: 'PT Serif', serif;
    color: var(--black800);
    margin-bottom: 1.3rem;
    margin-top: 0.2rem;
}
& .testi_item>p {
    color: var(--black);
    padding-right: 2.5rem;
    line-height: 1.6;
    margin-bottom: 2.8rem;
}
& .testimonial_item_bottom {
    display: flex;
    align-items: flex-start;
}
& .profile_left {
    width: 3.313rem;
    height: 3.313rem;
    margin-right: 1.2rem;
    margin-top: 0.3rem;
    border-radius: 0.625rem;
    overflow: hidden;
    & img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}
& .testimonial_nav_dots, 
& .testimonial_nav_dots.swiper-pagination-bullets.swiper-pagination-horizontal {
    position: absolute;
    width: fit-content;
    right: 3.5rem;
    left: auto;
    bottom: 3.5rem;
    z-index: 1;
}
& .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet, 
& .swiper-pagination-bullet {
    background-color: var(--black500);
    opacity: 1;
    border-radius: 5rem;
    margin: 0 3px;
    transition: 0.5s;
}
& .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active {
    width: 1.5rem;
    background-color: var(--secondary-color);
}
@media (max-width: 1024px){
    & .testimonial_main_box{
        width: 50%;
    }
    & .testimonial_head_content h3{
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    & .testimonial_top>img{
        width: 2rem;
    }
}
@media (max-width: 767px){
    & .bg_testimonial {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        object-fit: cover;
        object-position: right;
    }
    & .testimonial_main_box {
        position: relative;
        width: 100%;
        background-color: var(--white900);
        padding: 1.5rem;
        padding-bottom: 3rem;
    }
    & .testimonial_nav_dots, 
    & .testimonial_nav_dots.swiper-pagination-bullets.swiper-pagination-horizontal{
        bottom: 1rem;
        left: 50%;
        width: 100%;
        transform: translateX(-50%);
        text-align: center;
    }
    & .swiper-slide {
        height: auto;
        & .testi_item {
            height: 100%;
            display: flex;
            flex-direction: column;
            &>p{
                word-break: break-all;
                margin-bottom: 1rem;
            }
            & .testimonial_item_bottom{
                margin-top: auto;
            }
        }
    }
    & .profile_right .font23{
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
                line-clamp: 1; 
        -webkit-box-orient: vertical;
    }
    & .testimonial_head_content h3{
        font-size: 2.1rem;
    }
}
`;
export default {
    name:"HomeTestimonial",
    created(){
        console.log('testimonialsss', this.testimonials);
    },
    mounted() {
            var sliderElem = this.$refs.sliderRef;
            var sliderPaginationElement = this.$refs.sliderPagination;

            new Swiper(sliderElem, {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                speed:1000,
                pagination: {
                    el: sliderPaginationElement,
                    clickable: true
                },
                breakpoints: {
                    0: {
                    slidesPerView: 1,
                    autoHeight: true,
                    },
                    640: {
                    slidesPerView: 1,
                    autoHeight: true,
                    },
                    768: {
                    slidesPerView: 1,
                    },
                    1024: {
                    slidesPerView: 1,
                    },
                },
                watchSlidesVisibility: true
          });

        },
    components:{
        TestimonialWrapper
    },
    props: ['testimonials']
}
</script>