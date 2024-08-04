<template>
    <section class="content-section way_book_section home_featured" v-if="cms && cms.id">
        <div class="container">
            <div>
                <div class="text-center mb-6 padding_x" v-if="cms.title || cms.brief">
                    <h3 class="section_heading" v-if="cms.title">{{cms.title}}</h3>
                    <p class="sub_heading" v-if="cms.brief" v-html="cms.brief"></p>
                </div>
                <div v-if="cms.content" v-html="cms.content"></div>

                <div class="container_no" v-if="cms.child_data && cms.child_data.length > 0">
                    <h2 class="heading2" data-aos="fade-up" data-aos-duration="2000"></h2>
                    <div class="slidecourses owl-carousel">
                        <div class="listbox" v-for="child in cms.child_data">
                            <div class="border_box">
                                <!-- <div class="courseimg" v-if="child.thumbSrc">
                                    <img :src="child.thumbSrc" :alt="child.title">
                                </div> -->
                                <div class="titles" v-if="child.title || child.brief">
                                    <h3>{{child.title}}</h3>
                                    <div v-if="child.brief" v-html="child.brief"></div>
                                </div>
                                <div v-if="child.content" v-html="child.content"></div>
                                <div v-if="child.images">
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

                <div v-if="cms.images">
                    <div class="flex flex-wrap gall_cms">
                        <template v-for="image in cms.images">
                            <a :href="image.imageSrc" data-fancybox="destination-gallery"><img :src="image.thumbSrc" :alt="image.title"></a>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </section>    
</template>
<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
    name:'HomeCmsSlider',
    created() {
        this.loadCmsData();
    },
    data() {
        return {
            store,
            cms: {}
        }
    },
    methods:{
        loadCmsData(){
            var currentObj = this;
            let form_data = {};
            form_data['slug'] = this.slug;
            axios.post('/ajaxCmsData',form_data)
            .then(function (response) {
                if(response.data.success) {
                    currentObj.cms = response.data?.cms;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {
        Link
    },
    props: ["slug"],
};
</script>