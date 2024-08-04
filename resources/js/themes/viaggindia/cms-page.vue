<template>
    <!-- <SearchFormWithBanner
    title="Search For Place"
    /> -->
    <template v-if="cms.bannerImageSrc">
        <img class="" :src="cms.bannerImageSrc"  >
    </template>
    <CmsPageWrapper>
        <FancyboxWrapper class="gallery fotogallery">
            <div class="container" ref="cmsPageRef">
                <div class="flex justify-between mt-7 mb-5">
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
        </FancyboxWrapper>
    </CmsPageWrapper>
</template>
<script>
import Breadcrumb from './components/Breadcrumb.vue';
import SearchFormWithBanner from './components/SearchFormWithBanner.vue';
import Layout from './components/layout.vue';
import { store } from './store';
import { CmsPageWrapper } from './styles/CmsPageWrapper';
import FancyboxWrapper from './components/FancyboxWrapper.vue';


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
        Breadcrumb,
        FancyboxWrapper
    },
    mounted(){
        const cmsContainer = this.$refs.cmsPageRef;
        const galleryItems = cmsContainer.querySelectorAll('.blocks-gallery-item');
        galleryItems.forEach((item, index)=>{
            item.querySelector('a').setAttribute('data-fancybox', 'cms-gallery');
        });

        const dslides = cmsContainer.querySelectorAll('.dslides');
        if(dslides?.length > 0){
            dslides.forEach(item =>{
                $(item).owlCarousel({
                        items: 1,
                        nav: true,
                        dots: false,
                        loop:true,
                        autoplay:true,
                        autoWidth:false,
                        
                    });
            })
        }
    },
     layout: Layout,
    props: ["seo_data","cms","breadcrumbData"],
}
</script>