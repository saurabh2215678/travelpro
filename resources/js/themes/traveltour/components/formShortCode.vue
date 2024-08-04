<template>
    <formWrapper class="form_wrapper">
        <div class="flash-message"></div>
        <div v-if="this.formHtml" v-html="this.formHtml" ref="formref"></div>
    </formWrapper>
</template>
<script>
import { store } from '../store.js';
import {showErrorToast} from '../utils/commonFuntions.js';
import axios from "axios";

import {formWrapper} from './FormWrapperStyles';


export default {
    name:"formShortCode",
    created() {
        this.getFormShortCode();
    },
    data() {
        return {
            store,
            processing:false,
            formHtml:false,
        }
    },
    methods:{
        getFormShortCode() {
            var currentObj = this;
            axios.post('/getFormShortCode', {
                slug: this.slug,
                class: this.class,
                hidden: this.hidden,
            })  
            .then(function (resp) {
                let response = resp.data;
                if(response.success) {
                    currentObj.formHtml = response.formHtml;
                    
                } else {
                    currentObj.showErrorToast(response.message,10);
                    currentObj.processing = false;
                }
            })
            .catch(function (error) {
                
            });
        },
        handleFormSubmit(e) {
            let currentObj = this;
            let form = e.target;
            e.preventDefault();
            e.stopPropagation();
            if (form.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
            } else {
                var formID = form.id;
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
                e.preventDefault();
                e.stopPropagation();
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
          }
    },
    watch:{
        formHtml : {
            handler: function(formHtml) {
                let currentObj = this;
                setTimeout(()=>{
                    currentObj.$refs.formref.getElementsByTagName('form')[0].addEventListener('submit', this.handleFormSubmit);
                });
                
            }
        }
    },
    components:{
        formWrapper
    },
    props: ["slug", "class", "hidden"],
}
</script>