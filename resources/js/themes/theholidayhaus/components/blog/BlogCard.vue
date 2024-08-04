<template>
    <CardWrapper class="blog_card">
        <Link :href="data.url" class="blog_card_inner">
            <img v-if="data.blogThumbSrc" class="skeleton_loading" :src="data.blogThumbSrc" :alt="data.title">
            <p class="title" v-if="data.title">{{ data.title }}</p>
            <p v-if="isBreif && data.brief" class="blog_breif">{{ data.brief }}</p>
        </Link>
    </CardWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import { Link } from "@inertiajs/inertia-vue3";

const CardWrapper = styled.div`
.swiper-slide &{
    height: 100%;
    & .blog_card_inner{
        height: 100%;
    }
}
& .blog_card_inner {
    background-color: var(--grey-color);
    border-radius: 5px;
    overflow: hidden;
    width:100%;
    & img {
        height: 14rem;
        width: 100%;
        object-fit: cover;
    }
    & .title {
        margin: 0.8rem 1.2rem;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--theme-color);
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    & .blog_breif{
        margin: 0 1.2rem 0.8rem;
        font-size: 0.85rem;
    }
}
`

export default {
    name:'BlogCard',
    components:{
        CardWrapper,
        Link
    },
    props: ['data', 'isBreif']
}
</script>