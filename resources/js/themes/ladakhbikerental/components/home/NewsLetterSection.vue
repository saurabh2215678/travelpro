<template>
    <NewsLetterWrapper>
            <img :src="'../assets/ladakhbikerental/images/fotter-bg.jpg'" alt="" class="w-full footer_img">
        <div class="container">
            <h3 class="title fw600 font30 color_dark mt_48">Our Newsletter</h3>
            <p class="mb-4 sub_title font17 color_secondary">Sign on to the Ladakh bike rental newsletter for our latest news,updates & annnoucements.</p>

            <form @submit="handleFormSubmit" id="newsletter_form" class="grid gap-2 md:grid-cols-4">

                <div class="form-floating mb-3 w-full">
                    <input type="text" placeholder="Name" class="form-control " datatypeinput="inputname" name="name">
                    <div class="validation_name"><span id="name_error" class="validation_error"></span></div>
                </div> 
                
                <div class="form-floating mb-3 w-full">
                    <input type="text" id="email" placeholder="Email*" class="form-control " datatypeinput="inputname" name="email">
                    <div class="validation_email"><span id="email_error" class="validation_error"></span></div>
                </div> 

                <div class="form-floating mb-3 w-full">
                    <input type="text" placeholder="Whatsapp No." id="phone" class="form-control " datatypeinput="inputname" name="phone">
                    <div class="validation_phone"><span id="phone_error" class="validation_error"></span></div>
                </div>
                <div class="form-floating mb-3 w-full">
                    <button type="submit" class="submit_btn fw600 font18 btnSubmit">Subscribe</button>
                </div> 
            </form>
        </div>
    </NewsLetterWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import {NewsletterWrapper} from '../../styles/NewsletterWrapper';
import {store} from '../../store';
import {showErrorToast} from '../../utils/commonFuntions';
import axios from "axios";

const NewsLetterWrapper = styled.section`
padding-bottom: 7.125rem;
& .validation_email {
    display: inline-block;
    width: 100%;
}
& .form-floating .form-control{height: 3.125rem!important;border:1px solid #979797;}
& .mt_48{margin-top:48px;}
`
export default {
    name: 'NewsLetterSection',
    created() {
      document.body.classList.add('NewsLetterSection');
    },
    beforeUnmount() {
      document.body.classList.remove('NewsLetterSection');
   },
    data() {
        return {
            store
        }
    },
     methods:{
       showErrorToast,
        handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            let form = e.target;
            var formID = 'newsletter_form';
            $('#'+formID).find('.ajax_msg').html('');
            $('#'+formID).find('.validation_error').html('');
            $('#'+formID).find('.error').html('');
            $('#'+formID).find('.btnSubmit').html('Please wait...');
            $('#'+formID).find('.btnSubmit').attr("disabled", true);
            axios.post('/subscriber-newsletter', new FormData($('#'+formID)[0]))  
                .then(function (resp) {
                    let response = resp.data;
                    if(response.success) {
                       window.location.href = response.redirect_url;
                    } else if(response.message) {
                       currentObj.showErrorToast(response.message);
                    } else {
                       currentObj.showErrorToast('Something went wront, please try again.');
                    }
                    $('#'+formID).find('.btnSubmit').html('submit');
                    $('#'+formID).find('.btnSubmit').attr("disabled", true);
                })
                .catch(function (e) {
                    var response = e.response.data;
                    if(response) {
                        currentObj.parseBookingNowErrorMessages(response, formID, 'Submit');
                    }
                });
                form.classList.add('was-validated');
        },
        parseBookingNowErrorMessages(response, formID, boxText) {
            let currentObj = this;
            $('#'+formID).find('.btnSubmit').html('Submit');
            $('#'+formID).find('.btnSubmit').attr("disabled", false);
            if(response.errors) {
                var errors = response.errors;
                var message='';
                $.each(errors, function (key, val) {
                    $('#'+formID).find("#" + key + "_error").text(val[0]);
                });
            }
            if(response.message){
                currentObj.showErrorToast(response.message);
            }
        }
    },
    mounted () {
    },
    components: {
        NewsLetterWrapper,
        NewsletterWrapper,
    },
}
</script>