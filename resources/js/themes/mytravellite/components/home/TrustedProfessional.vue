<template>
    <TrustedProffesionalWrapper>
        <div class="container">
            <div class="trusted_box">
                <img src="../../assets/images/destination-img.jpg" alt="">
                <div class="trusted_content">
                    <p>Trested &amp; Professional </p>
                    <h5>We're Trusted by more then <span>50,102</span> clinets</h5>
                    <div class="video-info">
                        <img src="../../assets/images/destination-icon.png" alt="image">
                        <ul>
                            <li>81%</li>
                            <li>Humidity !</li>
                        </ul>
                    </div>
                </div>

                <div class="destination-video-btn">
                    <a href="https://www.youtube.com/watch?v=kS0X-yIsB64" target="_blank" class="hv-popup-link">
                        <svg class="svg-inline--fa fa-play" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg=""><path fill="currentColor" d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"></path></svg>
                    </a>
                    <h5>watch the video</h5>
                </div>
            </div>
        </div>
    </TrustedProffesionalWrapper>
    <TrustedProfessionalCounter>
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="trust_item">
                    <div class="funfact-container">
                        <div class="funfact-inner-box">
                            <div class="count-box">
                                <span class="count-text" data-speed="1500" data-stop="19">19</span>
                                <span class="count-after">+</span>
                            </div>
                            <p>Guides</p>
                            <h6>Professional Guides</h6>
                        </div>
                    </div>
                </div>

                <div class="trust_item">
                    <div class="funfact-container">
                        <div class="funfact-inner-box">
                            <div class="count-box">
                                <span class="count-text" data-speed="1500" data-stop="50">50</span>
                                <span class="count-after">k+</span>
                            </div>
                            <p>Customers</p>
                            <h6>Happy Customers</h6>
                        </div>
                    </div>
                </div>

                <div class="trust_item">
                    <div class="funfact-container">
                        <div class="funfact-inner-box">
                            <div class="count-box">
                                <span class="count-text" data-speed="1500" data-stop="25">25</span>
                                <span class="count-after">+</span>
                            </div>
                            <p>Experience</p>
                            <h6>Traveling Experience </h6>
                        </div>
                    </div>
                </div>

                <div class="trust_item">
                    <div class="funfact-container">
                        <div class="funfact-inner-box">
                            <div class="count-box">
                                <span class="count-text" data-speed="1500" data-stop="64">64</span>
                                <span class="count-after">+</span>
                            </div>
                            <p>Completed</p>
                            <h6>Tours Are Completed</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </TrustedProfessionalCounter>
</template>
<script>
import styled from 'vue3-styled-components';

const TrustedProffesionalWrapper = styled.section`
background-image: url('../images/destination-map.png');
padding-top: 8.75rem;
padding-bottom: 12.75rem;
animation: slide 60s linear infinite;
& .trusted_box{position: relative;}
&>img {
    width: 100%;
}
& .trusted_content {
    width: 16.375rem;
    position: absolute;
    top: -3.125rem;
    left: 12.5rem;
    background: var(--white);
    box-shadow: 11px -10px 20px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    padding: 2.313rem 1.25rem;
    & p{
        font-family: 'PT Serif', serif;
        font-size: 1.2rem;
        color: var(--secondary-color);
    }
    & h5{
        font-family: 'Source Serif 4',serif;
        color: var(--theme-color);
        font-weight: 600;
        font-size: 1.5rem;
        line-height: 2.125rem;
        & span{
            font-family: 'Permanent Marker';
            color: var(--secondary-color);
        }
    }
}
& .video-info {
    display: flex;
    margin-top: 0.625rem;
    & img{
        width: 2.813rem;
        height: 2.813rem;
    }
    & ul{
        margin-left: 0.625rem;
        & li{
            font-family: 'Permanent Marker';
            font-weight: 400;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: var(--secondary-color);
        }
        & li:last-child{
            color: var(--theme-color);
        }
    }
}
& .destination-video-btn{
    position: absolute;
    top: 55%;
    left: 78%;
    transform: translate(-50%, -50%);
    z-index: 1;
    display: flex;
    gap: 20px;
    align-items: center;
    max-width: 17.813rem;
    width: 100%;
    & a{
        width: 5rem;
        height: 5rem;
        font-size: 1.5rem;
        position: relative;
        background-color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        &:after{
            content: '';
            position: absolute;
            border: 1.875rem solid var(--white);
            border-radius: 50%;
            top: -1.25rem;
            left: -1.25rem;
            bottom: -1.25rem;
            right: -1.25rem;
            animation: anim 1.5s linear infinite;
            opacity: 0;
        }
        & svg{
            height: 1.5rem;
            color: skyblue;
        }
    }
    & h5 {
        font-family: 'Permanent Marker';
        font-weight: 400;
        font-size: 1.5rem;
        line-height: 1.813rem;
        color: var(--white);
    }
}
@media (max-width: 1024px){
    padding-top: 6.5rem;
    padding-bottom: 8rem;
    & .trusted_content{
        width: 19rem;
    }
}
@media (max-width: 767px){
    padding-top: 5.4rem;
    & .trusted_box>img{
        min-height: 19rem;
        object-fit: cover;
        border-radius: 10px;
    }
    & .trusted_content{
        right: auto; left: 2rem; padding-top: 1rem; padding-bottom: 1.4rem;
    }
    & .destination-video-btn{
        top: auto;
        bottom: 2.1rem;
        right: 1rem;
        left: auto;
        transform: none;
        & a{
            width: 3.4rem;
            height: 3.4rem;
            & svg{
                height: 1rem;
            }
        }
    }
}
`
const TrustedProfessionalCounter = styled.section`
    margin-top: -4.3rem;
    & .funfact-container {
        display: flex;
        justify-content: center;
        font-family: 'Source Serif 4',serif;
        & .count-box span{
            display: inline-block;
            font-weight: 700;
            font-size: 2.25rem;
            line-height: 2.875rem;
            text-align: center;
            color: var(--theme-color);
        }
        & .count-box span.count-after{
            margin-left: 0.5rem;
        }
        & p{
            color: var(--secondary-color);
            font-size: 0.875rem;
        }
        & h6{
            color: var(--black500);
            font-size: 1.5rem;
            line-height: 2.125rem;
            font-weight: 500;
        }
    }
@media (max-width: 767px){
    & .funfact-container {
        justify-content: flex-start;
        padding: 0 7%;
        h6 {
            font-size: 1.25rem;
        }
    }
}
`
export default {
    name:"TrustedProffesional",
    components: {
        TrustedProffesionalWrapper,
        TrustedProfessionalCounter
    }
}
</script>