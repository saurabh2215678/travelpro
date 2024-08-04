<template>
    <FlashMessageWrapper class="flash-message" v-if="flashMessages">
        <template v-for="flashMessage in flashMessages">
            <span :class="`help-block-${flashMessage.key}`" role="alert" v-if="flashMessage.value">
                {{flashMessage.value}}
            </span>
        </template>
    </FlashMessageWrapper>
</template>
<script>
import styled from 'vue3-styled-components';


const FlashMessageWrapper = styled.div`
& .help-block-success {
    background-color: #ceffce;
    padding: 1rem;
    font-size: 0.86rem;
    border-radius: 8px;
    color: green;
}
`

export default{
    name : 'FlashMessage',
    components: {
        FlashMessageWrapper 
    },
    props:['flashMessages']
}
</script>