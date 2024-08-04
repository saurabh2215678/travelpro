<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <CmsPageWrapper>
        <div class="container">
            <div class="flex justify-between mt-7">
                <h1 class="title text-2xl">{{cms?.title}}</h1>
                <Breadcrumb v-if="this.breadcrumb" :data="this.breadcrumb"/>
            </div>

            <div class="cms_image" v-if="cms.imageSrc">
                <img class="w-full" :src="cms.imageSrc" :alt="cms?.title" >
            </div>

            <div class="cms_content" v-if="cms.brief || cms.content">
                <template v-if="cms.brief">
                    <p v-html="cms.brief"></p>
                </template>
                <template v-if="cms.content">
                    <div v-html="cms.content"></div>
                </template>
            </div>

            <div class="container_no" v-if="cms.child_data && cms.child_data.length > 0">
                <div class="slidecourses owl-carousel">
                    <div class="listbox" v-for="child in cms.child_data">
                        <div class="border_box">
                            <template v-if="child.heading || child.brief">
                                <div class="courseimg" v-if="child.thumbSrc">
                                    <img :src="child.thumbSrc" :alt="child.title">
                                </div>

                                <div class="titles">
                                    <h3 v-if="child.heading">{{child.heading}}</h3>

                                    <template v-if="child.brief">
                                        <p v-html="child.brief"></p>
                                    </template>
                                </div>

                            </template>

                            <template v-if="child.content" v-html="child.content">
                            </template>                

                            <div v-if="child.images && child.images.length > 0">
                                <div class="flex flex-wrap gall_cms">
                                    <template v-for="image in child.images">
                                        <a :href="image.imageSrc" data-fancybox="destination-gallery"><img :src="image.thumbSrc" :alt="image.title"></a>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div v-if="cms.images && cms.images.length > 0">
                <div class="flex flex-wrap gall_cms">
                    <template v-for="image in cms.images">
                        <a :href="image.imageSrc" data-fancybox="destination-gallery"><img :src="image.thumbSrc" :alt="image.title"></a>
                    </template>
                </div>
            </div>
        </div>
    </CmsPageWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import Breadcrumb from './components/Breadcrumb.vue';
import SearchFormWithBanner from './components/SearchFormWithBanner.vue';
import Layout from './components/layout.vue';
import { store } from './store';

const CmsPageWrapper = styled.section`
padding-bottom: 3.5rem;
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    color: var(--theme-color);
}
& .cms_image {
    margin: 1.5rem 0 1rem;
    width: 100%;
    margin-right: 1rem;
    & img{
        width: 100%;
        object-fit: cover;
        max-height: 28rem;
    }
}
`

export default {
    name: 'cms page',
    created() {
        store.seoData = this.seo_data;
        this.breadcrumb = this.breadcrumbData;
    },
    data(){
        return {
            breadcrumb: [],
        }
    },
    components: {
        CmsPageWrapper,
        SearchFormWithBanner,
        Breadcrumb
    },
     layout: Layout,
    props: ["seo_data","cms","breadcrumbData"],
}
</script>