<template>
    <FaqSectionWrapper class="faq_section">
        <div class="container">
            <h2 class="title">{{ title }}</h2>
            <Faqs/>
        </div>
    </FaqSectionWrapper>
</template>
<script>
import {FaqSectionWrapper} from '../../styles/FaqSectionWrapper';
import Faqs from '../Faqs.vue';
export default{
    name : 'FaqSection',
    components:{
        FaqSectionWrapper,
        Faqs
    },
    props:['title']
}
</script>