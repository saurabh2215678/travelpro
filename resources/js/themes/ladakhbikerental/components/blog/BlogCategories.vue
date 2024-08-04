<template>
    <CategoriesWrapper class="blog_categories">
        <h4>Categories</h4>
        <ul>
            <li v-for="item in data"><Link :href="item.cat_url">{{ item.category_name }}</Link></li>
        </ul>
    </CategoriesWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { CategoriesWrapper } from '../../styles/CategoriesWrapper';

export default {
    name:'BlogCategories',
    created(){
        console.log('category data=', this.data);
    },
    components:{
        CategoriesWrapper,
        Link
    },
    props: ['data']
}
</script>