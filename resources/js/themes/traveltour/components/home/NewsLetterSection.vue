<template>
    <NewsLetterWrapper>
        <div class="container">
            <img src="../../assets/images/banner-car.png" class="news_letter_car" alt="">
            <h3 class="newsletter_heading">Sign up to our newsletter</h3>
            <form>
                <input type="email" placeholder="Enter your email here..." required>
                <button type="submit" class="submit_btn">Submit</button>
            </form>
        </div>
    </NewsLetterWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const NewsLetterWrapper = styled.section`
padding-bottom: 5.5rem;
& form {
    display: flex;
    box-shadow: 0px 0px 43px 0px rgba(0, 0, 0, 0.08);
    width: fit-content;
    margin: auto;
    border-radius: 1.375rem;
    & input {
        width: 31.688rem;
        padding: 2.2rem;
        border-radius: 1.375rem 0 0 1.375rem;
        &:focus{
            outline:none;
        }
    }
    & .submit_btn {
        background-color: var(--secondary-color);
        font-size: 1.35rem;
        color: var(--white);
        border-radius: 0 1.375rem 1.375rem 0;
        padding: 0 4.3rem;
        transition:0.5s;
        &:hover{
            background-color: var(--secondary-color-dark);
        }
    }
}
&>.container {
    position: relative;
}
& .news_letter_car {
    position: absolute;
    right: 15rem;
    top: 0;
    width : 6.5rem;
}
& .newsletter_heading { 
    text-align: center; 
    padding: 7rem 0 3rem; 
    font-family: 'PT Serif', serif; 
    font-size: 2.938rem; 
    font-weight: 600; 
    color: var(--theme-color); 
}
@media (max-width: 767px){
    padding-bottom: 3.5rem;
    & form {
        border-radius: 8px;
        width: 100%;
        & input{
            width: 100%;
            padding: 1.2rem;
        }
        & .submit_btn{
            padding: 0 1.5rem;
            font-size: 1.15rem;
            border-radius: 0 8px 8px 0;
        }
    }
    & .newsletter_heading {
        padding: 3rem 0 2rem;
        line-height: 1.2;
        font-size: 1.9rem;
    }
    & .news_letter_car {
        right: 1rem;
        width: 4rem;
        top: 0.5rem;
    }
}
`
export default {
    name: 'NewsLetterSection',
    components: {
        NewsLetterWrapper
    },
}
</script>