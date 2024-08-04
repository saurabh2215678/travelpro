<template>
    <BlogFormWrapper>
        <h3>Please fill the form and our expert will get back to you with further details.</h3>
        <formShortCode slug="[home_page_form]" class="left_form" />
    </BlogFormWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import formShortCode from '../../components/formShortCode.vue';

const BlogFormWrapper = styled.div`
& h3{
    text-align: center;
    font-weight: 600;
    color: var(--theme-color900);
    font-size: 1.2rem;
    position: relative;
    line-height: 1.35;
    margin-bottom: 2.5rem;
    &:after{
        content: "";
        position: absolute;
        bottom: -0.4rem;
        left: 50%;
        transform: translateX(-50%);
        width: 5rem;
        height: 2px;
        background-color: var( --secondary-color);
    }
}
`
export default {
    name:'BlogForm',
    components:{
        BlogFormWrapper,
        formShortCode,
    },
    props: ['data']
}
</script>