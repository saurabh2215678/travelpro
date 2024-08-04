<template>
    <NewsLetterWrapper>
        <div class="container">
            <!-- <img src="../../assets/images/banner-car.png" class="news_letter_car" alt=""> -->
            <form @submit="handleFormSubmit" id="newsletter_form">
                <h5 class="newsletter_heading text-lg">Sign up to our newsletter</h5>
                <input type="text" id="email" placeholder="Enter your email here..." name="email" >
                <div class="validation_email"><span id="email_error" class="validation_error"></span></div>
                <div class="submit_btn">
                    <button type="submit" class="btnSubmit">Submit</button>
                </div>
            </form>
        </div>
    </NewsLetterWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import {store} from '../../store';
import {showErrorToast} from '../../utils/commonFuntions';
import axios from "axios";

const NewsLetterWrapper = styled.section`
padding-bottom: 4rem;
& form { 
    display: flex;
    /* box-shadow: 0px 0px 43px 0px rgba(0, 0, 0, 0.08); */
    width: fit-content;
    align-items: center;
    margin: auto;
    border-radius: 1.375rem;
    & input {
        width: 25.688rem;
        padding: 0.6rem 1rem;
        border-radius: 0.3rem 0 0 0.3rem;
        background:transparent;
        border:1px solid var(--white500);
        &:focus{
            outline:none;
            background:var(--white);
            color:var(--black)
        }
    }
    & .submit_btn button.btnSubmit {
        background-color: var(--secondary-color);
        font-size: 1rem;
        color: var(--white);
        border-radius: 0 0.3rem 0.3rem 0;
        padding: 0.6rem 2rem;
        transition:0.5s;
        &:hover{
            background-color: var(--secondary-color-dark);
        }
    }
}
& .validation_email {
    display: inline-block;
    position: absolute;
    width: 100%;
    left: 0;
    right: 0;
    text-align: center;
    top: 3rem;
}
&>.container {
    position: relative;
}
& .news_letter_car {
    position: absolute;
    right: 15rem;
    top: 0;
    width : 6.5rem;
}
& .newsletter_heading { 
    text-align: center; 
    /* padding: 7rem 0 3rem; 
    font-family: 'PT Serif', serif; 
    font-size: 2.938rem; 
    font-weight: 600; */
    padding-right: 1rem;
    color: var(--white); 
}
@media (max-width: 767px){
    padding-bottom: 3.5rem;
    & form {
        border-radius: 8px;
        width: 100%;
        display: inline-block;
        text-align: center;
        & input{
            width: 100%;
            padding: 0.5rem 1.2rem ;
        }
        & .submit_btn{
            font-size: 1.15rem;
            border-radius: 0.3rem;
            color: #fff;
            margin-top: 1rem;
            height:auto !important;
            /* padding:0.5rem 1.2rem; */
        }
    }
    & .newsletter_heading {
        padding: 0rem 0 1rem;
        line-height: 1.2;
        font-size: 1.9rem;
    }
    & .news_letter_car {
        right: 1rem;
        width: 4rem;
        top: 0.5rem;
    }
}
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
        NewsLetterWrapper
    },
}
</script>