<template>
    <div class="dest_card" :class="className" ref="cardRef">
        <Link :href="item.url">
            <img :src="item.imageSrc" alt="">
            <div class="detail_box">
                <p>{{ item.name }}</p>
                <div>{{ item.brief }}</div>
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