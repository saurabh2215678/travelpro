<template>
    <MapItemWrapper>
        <div v-if="address" class="location w-1/2" v-html="address">
        </div>

        <div class="map_item_right w-1/2" v-if="map" v-html="map">
        </div>
    </MapItemWrapper>
</template>
<script>
import { MapItemWrapper } from '../../styles/MapItemWrapper';

export default {
    name:'MapItem',
     created() {
    },
    components:{
        MapItemWrapper
    },
    props : ['address','map']
}

</script>