<template>
    <div class="selc_btn">
        <span><i class="fa fa-minus" @click="removeOne"></i></span>
            <span class="pax_counter">{{ count }}</span>
        <span><i class="fa fa-plus" @click="addOne"></i></span></div>
</template>
<script>
export default {
    created() {
        this.count = parseInt(this.value)
    },
    data() {
        return {
            count : 1
        }
    },
    methods:{
        addOne(){
            if(parseInt(this.count) < 6){
                this.count = parseInt(this.count) + 1
            }
        },
        removeOne(){
            if(parseInt(this.count) > parseInt(this.minValue)){
              this.count = parseInt(this.count) - 1
            }
        },

    },
    props: ['value', 'minValue']
}
</script>