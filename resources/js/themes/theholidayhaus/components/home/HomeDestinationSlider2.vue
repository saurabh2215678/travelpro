<template>
    <HomeDestiWrapper :class="bgClass">
        <div class="container">
            <div class="home_destinations2">
                <DestinationSlider2 :destinations="destinations" v-if="destinations.length > 0" :slider_type="slider_type" :viewAllUrl="viewAllUrl" :subTitle="subTitle" />
            </div>
        </div>
    </HomeDestiWrapper>
</template>
<script>
import {HomeDestiWrapper} from '../../styles/HomeDestiWrapper';
import DestinationSlider2 from '../../components/home/DestinationSlider2.vue';
import axios from "axios";
export default{
    name : 'HomeDestinationSlider2',
    created(){
        console.log('slider_typeere ==', this.slider_type);
        //console.log('bgClass ==', this.bgClass);
        this.loadDestinations();
    },
    data(){
        return{
            destinations : [],
            total_count : 0
        }
    },
    methods:{
        loadDestinations(){
            var currentObj = this;
            let form_data = {};
            form_data['featured'] = 1;
            if(this.slider_type == 'international'){
                form_data['flag'] = 'international';
            }else{
                form_data['flag'] = 'domestic';
            }
            axios.post('/destination/ajaxDestinationList',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let destinationsData = response.data?.results;
                    currentObj.destinations = destinationsData.data;
                    currentObj.total_count = destinationsData.total_count;
                    console.log('destinationsData>>', destinationsData);
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },

    },
    components:{
        HomeDestiWrapper,
        DestinationSlider2
    },
    props:['slider_type','viewAllUrl','subTitle','bgClass']
    
};
</script>