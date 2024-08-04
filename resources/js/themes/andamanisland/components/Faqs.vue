<template>
    <FaqsWrapper v-if="this.faqs_sections && this.faqs_sections.length > 0">
        <div class="faqlist" :class="collapsed ? 'collapsed' : ''">
            <ul>
                <li v-for="(faq, i) in this.faqs_sections" :class="i == activeid ? 'active' : ''">
                    <div class="faq_item">
                        <div itemscope itemtype="https://schema.org/Question" itemprop="mainEntity">
                        <h3 class="ftitle" @click="handleFaqActive(i)">
                            <span><strong>Q{{ i+1 }}.</strong></span>
                            &nbsp; {{ faq.question }}
                        </h3>
                        <div itemscope itemtype="https://schema.org/Answer" itemprop="acceptedAnswer">
                        <SlideUpDown :modelValue="this.activeid == i">
                            <div class="faq_desc">
                                <div class="fqcont" v-html="faq.answer"></div>
                            </div>
                        </SlideUpDown>
                    </div>
                    </div>
                    </div>
                </li>
                <li>
                 <div class="read_option" @click="collapsed = !collapsed"> 
                  {{ collapsed ? 'Read Less' : 'Read More' }}
                  <i class="fa-solid fa-angle-down"></i>
                 </div>
                </li>
            </ul>
        </div> 
    </FaqsWrapper>
</template>
<script>
import {FaqsWrapper} from '../styles/FaqsWrapper';
import SlideUpDown from 'vue3-slide-up-down';
import { store } from '../store.js';
import axios from "axios";

export default{
    name : 'Faqs',
     created() {
        //this.loadFaqData();
        //console.log("Abhishek",this.faqs_sections);
    },
    data(){
        return{
            faqs : [],
            activeid : null,
            collapsed : false
        }
    },
    methods:{
        handleFaqActive(i){
            if(this.activeid != i){
                this.activeid = i;
            }else{
                this.activeid = null;
            }
        },

        /*loadFaqData(){
            var currentObj = this;
            let form_data = {};
            form_data['identitfer'] = this.identitfer;
            axios.post('/ajaxFaqsData',form_data)
            .then(function (response) {
                if(response.data.success) {
                    currentObj.faqs = response.data?.faqs;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },*/
    },
    components:{
        FaqsWrapper,
        SlideUpDown
    },
    props: ["identitfer","faqs_sections"],
}
</script>