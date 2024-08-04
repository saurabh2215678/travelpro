<template>
    <ShareWrapper class="social_share">
        <ul>
            <li class="fb" v-if="this.parseShareUrl('FACEBOOK_SHARE')">
                <a :href="this.parseShareUrl('FACEBOOK_SHARE')" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
            </li>
            <li class="twitter" v-if="this.parseShareUrl('TWITTER_SHARE')">
                <a :href="this.parseShareUrl('TWITTER_SHARE')" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            </li>
            <li class="instagram" v-if="this.parseShareUrl('INSTAGRAM_SHARE')">
                <a :href="this.parseShareUrl('INSTAGRAM_SHARE')" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            </li>
            <li class="whatsapp" v-if="this.parseShareUrl('WHATSAPP_SHARE')">
                <a :href="this.parseShareUrl('WHATSAPP_SHARE')" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
            </li>
        </ul>
    </ShareWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import { store } from '../store.js';
const ShareWrapper = styled.div`
& ul{
    display: flex;
    & li {
        margin-right: 0.5rem;
        & a{
            width: 2.3rem;
            height: 2.3rem;
            display: grid;
            place-items: center;
            background-color: var(--black600);
            border-radius: 50%;
            font-size: 1.063rem;
            color: var(--white);
        }
    }
}
& .fb a{background-color: #1877F2;}
& .twitter a{background-color: #00acee;}
& .instagram a{
    background: #f09433; 
    background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); 
    background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); 
    background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); 
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09433', endColorstr='#bc1888',GradientType=1 );
  }
& .whatsapp a{background-color: #075e54;}
`

export default {
    name:'SocialShare',
    created() {
    },
    methods: {
        parseShareUrl(shareUrlType) {
            let parsedUrl = '';

            
            if(this.shareUrl && store?.websiteSettings) {
                if(store.websiteSettings[shareUrlType]){
                    parsedUrl = `${store.websiteSettings[shareUrlType]}`.replace('{current_url}',this.shareUrl);
                }
            }
            return parsedUrl;
        },
    },
    components: {
        ShareWrapper
    },
    props:['shareUrl']
}
</script>