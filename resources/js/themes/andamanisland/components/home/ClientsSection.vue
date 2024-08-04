<template>
    <ClientsWrapper>
        <div class="container">
            <h3 class="clients_heading">Adventure Travel is associated with the<br>following organizations</h3>
            <div class="clients_slider_wrapper">
                <div class="swiper clients_slider" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="client in clients">
                            <div class="client_item">
                                <img :src="client.image" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider_pagination" ref="sliderPagination"></div>
            </div>
        </div>
    </ClientsWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const ClientsWrapper = styled.section`
& .clients_heading {
    text-align: center;
    font-family: 'PT Serif', serif;
    font-size: 2.125rem;
    font-weight: 800;
    line-height: 1.4;
    margin-bottom: 3.2rem;
    margin-top: 1.2rem;
}
& .clients_slider_wrapper { padding: 0 8rem; }
& .slider_pagination {
    text-align: center;
    position: static;
    margin: 2.3rem 0;
}
& .slider_pagination .swiper-pagination-bullet.swiper-pagination-bullet-active {
    background-color: var(--secondary-color);
}  
& .client_item img{
    margin: auto;
}
@media (max-width: 1024px){
    & .clients_heading{
        line-height: 2.3rem;
        margin-bottom: 2rem;
    }
    & .clients_slider_wrapper {
        padding: 0 2rem;
    }
    & .slider_pagination {
        margin: 0.5rem;
    }
}   
@media (max-width: 767px){
    & .clients_heading{
        margin: 0;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        line-height: 1.2;
    }
}            
`

export default {
    name: 'ClientsSection',
    components: {
        ClientsWrapper
    },
    mounted() {
            var sliderElem = this.$refs.sliderRef;
            var sliderPagination = this.$refs.sliderPagination;

            new Swiper(sliderElem, {
                slidesPerView: 6,
                slidesPerGroup: 6,
                spaceBetween: 0,
                loop: false,
                speed:1000,
                breakpoints: {
                    0: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                    },
                    640: {
                    slidesPerView: 3,
                    slidesPerGroup: 3,
                    },
                    768: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                    },
                    1024: {
                    slidesPerView: 6,
                    slidesPerGroup: 6,
                    },
                },
                pagination: {
                    el: sliderPagination,
                    clickable: true
                },
                watchSlidesVisibility: true
          });

    },
    props : ['clients']
}
</script>