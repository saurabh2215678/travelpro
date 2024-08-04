<template>
    <div class="dest_card" :class="className" ref="cardRef">
        <Link :href="item.url" class="flex flex-wrap desti_link">
            <div class="detail_box_img lg:w-1/2 w-full">
                <img :src="item.imageSrc" alt="">
            </div>
            <div class="detail_box_txt lg:w-1/2 w-full">
                <h3>{{ item.name }}</h3>
                <div>{{ item.brief }}</div>
                <div class="font18 fw600 btn_theme"><span><img src="/assets/ladakhbikerental/images/arrow.png" alt=""></span> Learn More</div>
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