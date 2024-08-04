<template>
    <CardWrapper class="swiper-slide" v-if="packageData">
        <div class="featured_box">
            <Link class="featured_content" :href="packageData.url">
                <div class="images">
                    <img :src="packageData.thumbSrc" :alt="packageData.title" />
                    <div class="pack_day_night" v-if="packageData.isActivity == 0 && (packageData.days || packageData.nights)">
                        <span class="" v-if="packageData.nights">{{packageData.nights}}N</span>
                        <span class="" v-if="packageData.days">{{packageData.days}}D</span>
                    </div>
                </div>
            </Link>
            <Link class="featured_content_text" :href="packageData.url">
                <div class="title text-base leading-normal">{{packageData.title}}</div>
                <div class="price" v-if="packageData.search_price">
                    <p class="text-base"><span>From</span><span class="amount font-bold">{{showPrice(packageData.search_price,true)}}</span></p>
                    <small></small>
                </div>
            </Link>
        </div>
    </CardWrapper>
</template>
<script>
import {showPrice} from '../../utils/commonFuntions';
import { store } from '../../store';
import { Link } from "@inertiajs/inertia-vue3";
import styled from 'vue3-styled-components';

const CardWrapper = styled.li`
& .featured_content_text {
    width: 100%;
    padding: 1rem;
    & .title {
        /* color: var(--theme-color); */
        font-weight: 500;
    }
    & .amount{
        color: var(--theme-color);
        font-weight: 500;
    }
}
& .featured_box {
    /* box-shadow: 1px 9px 20px #00000038;
    border-radius: 7px; */
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
    background: #fafafa;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
    z-index: 99;
    img{
        /* border-top-left-radius: 7px;
        border-top-right-radius: 7px; */
        height:13rem;
        object-fit: cover;
        width: 100%;
    }
}
`

export default {
    name:"PackageCard",
    created() {
        // console.log('PackageCard.package',this.package);
    },
    data() {
        return {
            store,
        }
    },
    methods:{        
        showPrice,
    },
    components:{
        Link,
        CardWrapper
    },
    props: ["packageData"],
}
</script>