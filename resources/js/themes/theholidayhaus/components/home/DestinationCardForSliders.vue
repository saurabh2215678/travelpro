<template>
    <div class="dest_card" :class="className" ref="cardRef">
        <Link :href="item.url">
            <img :src="item.imageSrc" alt="">
            <div class="detail_box">
                <p class="fw700 theme-color-dark mt-3">{{ item.name }}</p>
                <div class="count-dist font14 text-center">{{item.package_count}} Tour Packages</div>
            </div>
        </Link>
    </div>
</template>
<script>

import { Link } from "@inertiajs/inertia-vue3";

export default{
    'name' : 'DestinationCardForSliders',
    components:{
        Link
    },
    props:['className', 'item']
}
</script>