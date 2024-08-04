<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <CmsPageWrapper>
        <FancyboxWrapper class="gallery">
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
import axios from "axios";
export default {
    name: 'cms page',
    created() {
        store.seoData = this.seo_data;
        this.breadcrumb = this.breadcrumbData;
    },
    data(){
        return {
            breadcrumb: [],
            processing:false,
        }
    },
    components: {
        CmsPageWrapper,
        SearchFormWithBanner,
        Breadcrumb,
        FancyboxWrapper
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
    layout: Layout,
    props: ["seo_data","cms","breadcrumbData"],
}
</script>