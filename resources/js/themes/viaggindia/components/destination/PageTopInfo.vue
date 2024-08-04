<template>
    <InfoWrapper>
        <div class="container">
            <ul class="breadcrumb">
                <li><Link :href="store.websiteSettings?.FRONTEND_URL">Home</Link></li>
                <li><Link :href="store.websiteSettings?.DESTINATION_URL">Destinations</Link></li>
                <li v-if="destination">{{destination?.name}}</li>
            </ul>
            <div class="top_title" v-if="destination && viewAllUrl">
                <h1>{{destination?.name}}</h1>
                <Link :href="viewAllUrl">View All</Link>
            </div>
        </div>
    </InfoWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';
const InfoWrapper = styled.section`
padding: 1.3rem 0;
& .breadcrumb{
    display: flex;
    margin-left: auto;
    justify-content: flex-end;
    color: var(--black500);
    & li:after {
        content: "";
        width: 1px;
        height: 1ch;
        display: inline-block;
        background-color: currentColor;
        margin: 0 0.6rem;
        transform: rotate(22deg);
    }
    & li:last-child:after {
        display:none;
    }
}
& .top_title{
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    & h1{
        font-size: 2.5rem;
        font-weight: 700;
        font-family: 'PT Serif',serif;
        color: var(--theme-color);
        text-transform: capitalize;
        &:after{
            content: "";
            display: block;
            background-color: var(--secondary-color);
            height: 3px;
            width: 40px;
        }
    }
    & a{
        background-color: var(--theme-color);
        padding: 0.36rem 1rem;
        color: var(--white);
        border-radius: 5rem;
        font-size: 0.9rem;
    }   
} 
`
export default {
    name: 'PageTopInfo',
    data() {
        return {
            store,
        }
    },
    components: {
        InfoWrapper,
        Link
    },
    props : ['destination', 'viewAllUrl']
}
</script>