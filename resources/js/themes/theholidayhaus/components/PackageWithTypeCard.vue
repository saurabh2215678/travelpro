<template>
    <PackageWithTypeCardWrapper>
        <Link :href="data.url">

            <span class="package_type" v-if="data.package_type">{{ data.package_type }}</span>
            <span class="package_type" v-else-if="data.package_tour_type_text">{{ data.package_tour_type_text }}</span>

            <img class="skeleton_loading" :src="data?.thumbSrc" >
            <div class="featured_content">
                <p class="package_title">{{ data.title }}</p>
                <p class="package_price" v-if="data?.search_price && data?.search_price > 0"> Starting From :  <strong>{{showPrice(data.search_price,true)}}</strong></p>
            </div>
        </Link>
    </PackageWithTypeCardWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { PackageWithTypeCardWrapper } from '../styles/PackageWithTypeCardWrapper';
import {showPrice} from '../utils/commonFuntions';

export default {
    name:'PackageWithTypeCard',
    methods: {
        showPrice,
    },
    components:{
        PackageWithTypeCardWrapper,
        Link
    },
    props: ['data']
}
</script>