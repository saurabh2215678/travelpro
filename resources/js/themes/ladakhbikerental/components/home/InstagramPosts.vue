<template>
    <Instawrapper>
        <div class="container">
            <div class="title_inner pb-5">
            <h4 class="text-center font20 color_theme fw600 mb-0" style=" display: flex; justify-content: center; align-items: center; ">@ladakhbikerental <svg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/> <g transform="matrix(0.42 0 0 0.42 12 12)" > <g style="" > <g transform="matrix(1 0 0 1 0 0)" > <polygon style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(66,165,245); fill-rule: nonzero; opacity: 1;" points="5.62,-21 9.05,-15.69 15.37,-15.38 15.69,-9.06 21,-5.63 18.12,0 21,5.62 15.69,9.05 15.38,15.37 9.06,15.69 5.63,21 0,18.12 -5.62,21 -9.05,15.69 -15.37,15.38 -15.69,9.06 -21,5.63 -18.12,0 -21,-5.62 -15.69,-9.05 -15.38,-15.37 -9.06,-15.69 -5.63,-21 0,-18.12 " /> </g> <g transform="matrix(1 0 0 1 -0.01 0.51)" > <polygon style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" points="-2.6,6.74 -9.09,0.25 -6.97,-1.87 -2.56,2.53 7,-6.74 9.09,-4.59 " /> </g> </g> </g> </svg></h4>
            <h3 class="text-center font30 color_dark fw600">Follow us on Instagram</h3>
        </div>
            <!-- <embed-instagram-feed url="https://v1.nocodeapi.com/bikerantal/instagram/oAHIAillsQrdEVlE?limit=16"></embed-instagram-feed> -->
            <div v-if="loading">Loading...</div>
                <div v-else id="instagram-feed">
                    <a v-for="post in instagramPosts.slice(0, 8)" :key="post.id" :href="post.permalink" target="_blank">
                    <img :src="post.media_url" :alt="post.caption" />
                </a>
                </div>
        </div>
    </Instawrapper>
</template>
<script>
import {Instawrapper} from '../../styles/Instawrapper';
import SvgRenderer from '../SvgRenderer.vue';
export default{
    name : 'InstagramPosts',
    data() {
    return {
      loading: true,
      instagramPosts: [],
    };
  },
    mounted() {
        // Replace with your Instagram Access Token
        const accessToken = 'IGQWRQbzFvTWJGTzZADSEhxOUdYNmFkV213VGtld2FzcDlJVk1jTzBlcjk3RnRFZA3ZAKSnM2ckNTMThRR1RscmVKR0hQRDFRWC1LTjdLTG1nT0ZASQ0xUd0lEZAzZAoNEtGWmRJbnVUcmJfUVFrdUdVSXNCQmZAGempINnMZD';

        fetch(`https://graph.instagram.com/v12.0/me/media?fields=id,caption,media_type,media_url,permalink,timestamp&access_token=${accessToken}`)
        .then(response => response.json())
        .then(data => {
            this.instagramPosts = data.data;
            this.loading = false;
        })
        .catch(error => console.error(error));
    },

    methods:{
        
    },


    components:{
        Instawrapper,
        SvgRenderer
}
}
</script>
<style scoped>
  /* Your styles go here */
  .nc-title {
    display: none;
  }
</style>