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
    'name' : 'DestinationCard',
    mounted(){
        let currObj = this;
        const resizeObserver = new ResizeObserver(currObj.setCardStyle);
        resizeObserver.observe(this.$refs.cardRef?.querySelector('.detail_box'));

        // currObj.setCardStyle();
        // setTimeout(() => {
        //     currObj.setCardStyle();
        // }, 2000);
        // setTimeout(() => {
        //     currObj.setCardStyle();
        // }, 5000);
    },
    methods:{
        setCardStyle(){
            const cardElem = this.$refs.cardRef?.querySelector('.detail_box');
            const cardheight = cardElem?.offsetHeight;
            cardElem?.style.setProperty("--box-height", `${cardheight}px`);
        }
    },
    components:{
        Link
    },
    props:['className', 'item']
}
</script>