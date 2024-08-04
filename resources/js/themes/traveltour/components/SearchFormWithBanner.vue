<template>
    <SectionWrapper :class="title ? 'has_title':''" :data-title="title">
        <img src="../assets/images/inner_common_banner.jpg" class="inner_banner" alt="">
        <SearchForm :type="type"/>
    </SectionWrapper>
</template>
<script>

import SearchForm from './SearchForm.vue';
import {SectionWrapper} from '../styles/SearchFormWithBannerStyles';

export default {
    name: 'SearchFormWithBanner',
    components: {
        SearchForm,
        SectionWrapper
    },
    props:['title', 'type']
}
</script>