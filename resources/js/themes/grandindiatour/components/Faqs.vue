<template>
    <FaqsWrapper v-if="this.faqs && this.faqs.length > 0">
        <div class="faqlist">
            <ul>
                <li v-for="(faq, i) in this.faqs" :class="i == activeid ? 'active' : ''">
                    <div class="faq_item">

                        <h3 class="ftitle" @click="handleFaqActive(i)">
                            <span><strong>Q{{ i+1 }}.</strong></span>
                            &nbsp; {{ faq.question }}
                        </h3>
                        <SlideUpDown :modelValue="this.activeid == i">
                            <div class="faq_desc">
                                <div class="fqcont" v-html="faq.answer"></div>
                            </div>
                        </SlideUpDown>
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
        this.loadFaqData();
    },
    data(){
        return{
            faqs : [],
            activeid : null
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

        loadFaqData(){
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
        },
    },
    components:{
        FaqsWrapper,
        SlideUpDown
    },
    props: ["identitfer"],
}
</script>