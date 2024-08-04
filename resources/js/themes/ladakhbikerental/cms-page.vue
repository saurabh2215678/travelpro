<template>
    <div class="cms_image" v-if="cms.bannerImageSrc">
        <img class="w-full" :src="cms.bannerImageSrc" :alt="cms?.title" >
    </div>
    <CmsPageWrapper>
        <FancyboxWrapper class="gallery">
            <div class="container" ref="cmsPageRef">
                <div class="flex justify-between mt-7 mb-5 cms_hdng">
                    <h1 class="title text-2xl">{{cms?.title}}</h1>
                    <Breadcrumb v-if="this.breadcrumb" :data="this.breadcrumb"/> 
                </div>
                <div class="cms_content" v-if="cms.brief || cms.content">
                    <div class="cms_image" v-if="cms.imageSrc">
                        <img class="w-full" :src="cms.imageSrc" :alt="cms?.title" >
                    </div>
                    <template v-if="cms.brief">
                        <p v-html="cms.brief"></p>
                    </template>
                    <template v-if="cms.content">
                        <div v-html="cms.content"></div>
                    </template>
                </div>

                <template v-if="moterbikepage">
                    <div class="container_no" v-if="cms.child_data && cms.child_data.length > 0">
                        <ul class="grid_views">
                            <li class="listbox" v-for="child in cms.child_data" :id="child.slug">
                                <div class="views_bike">
                                    <template v-if="child.heading || child.content">
                                        <div class="grid_img">
                                            <ChildSlider :child="child" />
                                        </div>
        
                                        <div class="grid_text">
                                            <h3 v-if="child.heading">{{child.heading}}</h3>
        
                                            <template v-if="child.content">
                                                <p v-html="child.content" clsss="font18"></p>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </li>
                        </ul>
                    </div>
                </template>
                <template v-else>
                    <div class="container_no" v-if="cms.child_data && cms.child_data.length > 0">
                        <div class="slidecourses owl-carousel">
                            <div class="listbox" v-for="child in cms.child_data" :id="child.slug">
                                <div class="border_box">
                                    <template v-if="child.heading || child.content">
                                        <div class="courseimg">
                                            <template  v-if="child.thumbSrc">
                                                <img :src="child.thumbSrc" :alt="child.title">
                                            </template>
                                        </div>
        
                                        <div class="titles">
                                            <h3 v-if="child.heading">{{child.heading}}</h3>
        
                                            <template v-if="child.content">
                                                <div v-html="child.content" class="child_content"></div>
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
                </template>

                <!-- Frequently Asked Questions -->
                <template v-if="faq_row && faq_row.length > 0">
                    <div class="flex justify-between mt-11 mb-5">
                    <h1 class="title text-2xl">Rental FAQ's</h1>
                </div>
                    <ul class="cms_faq">
                        <li class="faq_main" v-for="faq in faq_row">
                            <div>
                                <div class="faq_title heading6"><strong>Q </strong>{{faq.question}}</div>
                                <div class="faq_data" style="">
                                    <p v-html="faq.answer"></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </template>
                <!-- Frequently Asked Questions Closed -->
                <div v-if="cms.images && cms.images.length > 0">
                    <div class="flex justify-between mt-11 mb-5">
                    <h1 class="title text-2xl">Gallery</h1>
                </div>
                    <div class="gall_cms">
                        <div class="gallery_box">
                        <template v-for="image in cms.images">
                            <a class="figure" :href="image.imageSrc" data-fancybox="destination-gallery"><img :src="image.thumbSrc" :alt="image.title"></a>
                        </template>
                      </div>
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
import ChildSlider from './components/ChildSlider.vue';
import axios from "axios";


export default {
    name: 'cms page',
    created() {
        store.seoData = this.seo_data;
        this.breadcrumb = this.breadcrumbData;
        console.log('123', this.cms);
    },
    data(){
        return {
            breadcrumb: [],
            processing:false,
            moterbikepage : false
        }
    },
    components: {
        CmsPageWrapper,
        SearchFormWithBanner,
        Breadcrumb,
        FancyboxWrapper,
        ChildSlider
    },
    methods:{
        handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            let form = e.target;
            var formID = form.id;
            if (form.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                setTimeout(() => {
                    if ($("#" + formID)) {
                        $("#" + formID)[0].scrollIntoView();
                    }
                }, 1);
            } else {                
                var _token = '{{ csrf_token() }}';
                var boxText = $('#'+formID).find('.btnSubmit').html(); 
                $('.btnSubmit').html('Please wait...');
                $(".btnSubmit"). attr("disabled", true);

                $('#'+formID).find('.ajax_msg').html('');
                $('#'+formID).find('.error').html('');
                $('#'+formID).find('.btnSubmit').html('Please wait...');
                axios.post('/forms', new FormData($('#'+formID)[0]))  
                .then(function (resp) {
                    let response = resp.data;
                    $('.btnSubmit').html('Submit');
                    $(".btnSubmit"). attr("disabled", false);
                    if(response.success) {
                        $('#'+formID).find(".ajax_msg").html(response.message);
                        $('#'+formID).find('.ajax_msg').addClass('success');
                        $('#'+formID).find('.form-floating').hide('');
                        $('#'+formID).find('.btn-book-space').hide('');
                        $('#'+formID).find('.ajax_msg').removeClass('error');
                        $('#'+formID)[0].reset();
                        $('#'+formID).removeClass('was-validated');
                        $('#'+formID).find(".error").hide();
                        $('#'+formID).find('.btnSubmit').html(boxText);
                        if(response.redirect_url) {
                            setTimeout(function(){
                                window.location.href = response.redirect_url;
                            },500);
                        }
                    } else {
                        currentObj.parseFormsErrorMessages(response, formID, boxText);
                    }
                })
                .catch(function (e) {
                    var response = e.response.data;
                    if(response) {
                        currentObj.parseFormsErrorMessages(response, formID, boxText);
                    }                
                });
                setTimeout(() => {
                    if ($("#" + formID)) {
                        $("#" + formID)[0].scrollIntoView();
                    }
                }, 1);
            }
            form.classList.add('was-validated');
        },
        parseFormsErrorMessages(response, formID, boxText) {
            if(response.message) {
              $('#'+formID).find('.ajax_msg').addClass('error');
              $('#'+formID).find('.ajax_msg').removeClass('success');
              $('#'+formID).find(".ajax_msg").html(response.message);
            }
            if(response.errors_fields) {
              var errors = response.errors_fields;//$.parseJSON(resp.errors_fields);
              var message='';
              $.each(errors, function (key, val) {
                $('#'+formID).find("." + key + "_error").text(val[0]);
              });
              $(".btnSubmit").attr("disabled", false);
              $('#'+formID).find('.ajax_msg').removeClass('success');
              $('#'+formID).find('.ajax_msg').addClass('error');
              $('#'+formID).find(".error").show();
              $('#'+formID).find('.btnSubmit').html(boxText);
              grecaptcha.ready(function () {
                grecaptcha.execute("{{$RECAPTCHA_SITE_KEY}}", { action: 'forms' }).then(function (token) {
                  $('.g-recaptcha-response').val(token);
                });
              });
            }
            setTimeout(() => {
                if ($("#" + formID)) {
                    $("#" + formID)[0].scrollIntoView();
                }
            }, 1);
          }

    },
    mounted(){
        $('.about_menu li').click(function (){
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
        });

        if(window.location.pathname.includes('motor-bikes')){
            this.moterbikepage = true;
        }

        // if(window.location.pathname.includes('motor-bikes') || window.location.pathname.includes('absdsd')){
        //     this.moterbikepage = true;
        // }

        var swiper = new Swiper(".featured_slider", {
        spaceBetween: 30,
        slidesPerView: 4,
        // autoplay: {
        //     delay: 2500,
        //     disableOnInteraction: false,
        // },
        });

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

        const forms = cmsContainer.querySelectorAll(".needs-validation");
        if (forms?.length > 0) {
            forms.forEach((form) => {
                // console.log('form=',form);
                form.addEventListener("submit", this.handleFormSubmit);
                const filesInputs = form.querySelectorAll(".file-input input");
                if (filesInputs.length > 0) {
                    filesInputs.forEach((fileInput) => {
                        fileInput.addEventListener("change", (e) =>
                            this.handleFileChange(e, form.id),
                            );
                    });
                }
            });
        }
    },
    /*mounted() {
        document.body.classList.add("cms-page");
    },
    beforeUnmount() {
        document.body.classList.remove("cms-page");
    },*/
    layout: Layout,
    props: ["seo_data","cms","breadcrumbData","faq_row"],
}
</script>