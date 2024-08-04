<template>
    <HomeExperienceWrapper>
        <div class="container">
            <ul>
                <li>
                    <img src="https://www.andamanisland.in/assets/site1/theme3/images/strip-icon.png" alt="icon">
                    <div class="fstext"><strong>15</strong> <span>Years of <br>Experience</span></div>
                </li>
                <li>
                    <img src="https://www.andamanisland.in/assets/site1/theme3/images/strip-icon1.png" alt="icon">
                    <div class="fstext"><strong>0.5M</strong> <span>Itineraries Crafted</span></div>
                </li>
                <li>
                    <img src="https://www.andamanisland.in/assets/site1/theme3/images/strip-icon2.png" alt="icon">
                    <div class="fstext"><strong>50K</strong> <span>Happy Customers</span></div>
                </li>
                <li>
                    <img src="https://www.andamanisland.in/assets/site1/theme3/images/strip-icon3.png" alt="icon">
                    <div class="fstext"><strong>50</strong> <span>Destination <br>Experts</span></div>
                </li>
                <li>
                    <img src="https://www.andamanisland.in/assets/site1/theme3/images/strip-icon4.png" alt="icon">
                    <div class="fstext"><strong>308</strong> <span>Google Reviews</span></div>
                </li>
           </ul>
        </div>
    </HomeExperienceWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const HomeExperienceWrapper = styled.section`
padding: 2rem 0;
margin-top:2rem;
background:var(--orange);
color: #fff;
& ul li { 
    width: 20%;
    float: left;
    padding: 0.625rem;
    img {
    display: inline-block;
    vertical-align: middle;
    }
    .fstext {
    display: inline-block;
    vertical-align: middle;
    padding-left: 0.625rem;
    }
.fstext strong {
    font-size: 2rem;
    font-weight: 500;
    line-height: 1;
}
.fstext span {
    display: block;
    font-size: 1rem;
}
}


@media (max-width: 767px){
    & ul {
        display: flex;
        flex-wrap: wrap;
        & li{
            width: 33%;
            /* display: flex;
            flex-grow: 1;
            align-items: center; */
        }
    }
}
`
export default {
    name: 'HomeExperienceSection',
    components: {
        HomeExperienceWrapper
    },
}
</script>