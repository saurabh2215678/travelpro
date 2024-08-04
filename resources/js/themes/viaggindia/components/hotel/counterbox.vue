<template>
    <div class="selc_btn">
        <span><i class="fa fa-minus" @click="removeOne"></i></span>
            <span class="pax_counter">{{ count }}</span>
        <span><i class="fa fa-plus" @click="addOne"></i></span></div>
</template>
<script>
export default {
    created() {
        this.count = this.value
    },
    data() {
        return {
            count : 1
        }
    },
    methods:{
        addOne(){
            if(this.count < 6){
                this.count = this.count + 1
            }
        },
        removeOne(){
            if(this.count > this.minValue){
              this.count = this.count - 1
            }
        },

    },
    props: ['value', 'minValue']
}
</script>