<template>
    <NewsLetterWrapper>
        <div class="container">
            <!-- <img src="../../assets/images/banner-car.png" class="news_letter_car" alt=""> -->
            <form>
                <h5 class="newsletter_heading text-lg">Sign up to our newsletter</h5>
                <input type="email" placeholder="Enter your email here..." required>
                <button type="submit" class="submit_btn">Submit</button>
            </form>
        </div>
    </NewsLetterWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const NewsLetterWrapper = styled.section`
padding-bottom: 4rem;
& form { 
    display: flex;
    /* box-shadow: 0px 0px 43px 0px rgba(0, 0, 0, 0.08); */
    width: fit-content;
    align-items: center;
    margin: auto;
    border-radius: 1.375rem;
    & input {
        width: 25.688rem;
        padding: 0.6rem 1rem;
        border-radius: 0.3rem 0 0 0.3rem;
        background:transparent;
        border:1px solid var(--white500);
        &:focus{
            outline:none;
            background:var(--white);
            color:var(--black)
        }
    }
    & .submit_btn {
        background-color: var(--secondary-color);
        font-size: 1rem;
        color: var(--white);
        border-radius: 0 0.3rem 0.3rem 0;
        padding: 0.6rem 2rem;
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
    /* padding: 7rem 0 3rem; 
    font-family: 'PT Serif', serif; 
    font-size: 2.938rem; 
    font-weight: 600; */
    padding-right: 1rem;
    color: var(--white); 
}
@media (max-width: 767px){
    padding-bottom: 3.5rem;
    & form {
        border-radius: 8px;
        width: 100%;
        display: inline-block;
        text-align: center;
        & input{
            width: 100%;
            padding: 0.5rem 1.2rem ;
        }
        & .submit_btn{
            font-size: 1.15rem;
            border-radius: 0.3rem;
            color: #000;
            margin-top: 1rem;
        }
    }
    & .newsletter_heading {
        padding: 0rem 0 1rem;
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