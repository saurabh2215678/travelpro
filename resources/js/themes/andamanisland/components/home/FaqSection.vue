<template>
    <FaqSectionWrapper class="faq_section">
        <div class="container">
            <h2 class="title font30 fw700">{{ title }}</h2>
            <Faqs :faqs_sections="faqs_sections"/>
        </div>
    </FaqSectionWrapper>
</template>
<script>
import {FaqSectionWrapper} from '../../styles/FaqSectionWrapper';
import Faqs from '../Faqs.vue';
export default{
    name : 'FaqSection',
    created() {
        //console.log("Abhishek",this.faqs);
    },
    components:{
        FaqSectionWrapper,
        Faqs
    },
    props:['title',"faqs_sections"]
}
</script>