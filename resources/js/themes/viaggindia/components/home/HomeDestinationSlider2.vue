<template>
    <HomeDestiWrapper>
        <div class="container">
            <div class="home_destinations2">
                <DestinationSlider2 :destinations="destinations"/>
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
        this.loadDestinations();
    },
    data(){
        return{
            destinations : []
        }
    },
    methods:{
        loadDestinations(){
            var currentObj = this;
            let form_data = {};
            form_data['featured'] = 1;
            axios.post('/destination/ajaxDestinationList',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let destinationsData = response.data?.results;
                    currentObj.destinations = destinationsData.data;
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
    }
}
</script>