<template>
    <BreadcrumbWrapper class="breadCrumb">
        <li v-for="item in data">
            <Link v-if="item.url" :href="item.url">{{ item.label }}</Link>
            <span v-else>{{ item.label }}</span>
        </li>
    </BreadcrumbWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { BreadcrumbWrapper } from '../styles/BreadcrumbWrapper';

export default {
    name:'Breadcrumb',
    components:{
        BreadcrumbWrapper,
        Link
    },
    props: ['data']
}
// data will be like this
// [
//     {url: '/', label: 'home'},
//     {url: '/destination', label: 'destination'},
//     {label: 'Ladakh'},
// ]
</script>