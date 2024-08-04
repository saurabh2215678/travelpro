import styled from "vue3-styled-components";

export const HomeBlogWrapper = styled.section`
overflow:hidden;
padding-top: 8rem;
padding-bottom: 5.5rem;
& .section_title {
    position: relative;
}
& .slider_btns {
    position: absolute;
    right: 0;
    top: 1rem;
    display: flex;
}
& .slider_btns>* {
    width: 3.625rem;
    height: 3.625rem;
    background-color: var(--secondary-color);
    border-radius: 50%;
    display: grid;
    place-items: center;
    color: var(--white);
    font-size: 1.2rem;
    transition: 0.5s;
}
& .slider_btns>*:first-child {
    margin-right: 1rem;
}
& .slider_btns>*:hover {
    background-color: var(--secondary-color-dark);
}
& .slider_btns>.swiper-button-disabled {
    background-color: var(--white);
    box-shadow: -2px 7px 15px #0000001a;
    color: var(--black500);
    cursor: no-drop;
}
& .slider_btns>.swiper-button-disabled:hover {
    background-color: var(--black30);
}
& .slider_box {
    margin: 0 -1.5rem;
}
& .blog_box {
    box-shadow: -4.459px 4.015px 16px 0px rgba(132, 132, 132, 0.14);
    padding: 0.8rem;
    margin: 1rem;
    border-radius: 1.438rem;
    &>img {
        height: 14.625rem;
        width: 100%;
        object-fit: cover;
        border-radius: 0.938rem;
    }
    & .text {
        padding: 0 0.9rem;
        padding-bottom: 1.3rem;
        & .title {
            font-size: 1.688rem;
            font-weight: 500;
            color: var(--black900);
            margin-top: 1.5rem;
            margin-bottom: 1.1rem;
            font-family: 'Raleway', sans-serif;
            line-height: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1; /* number of lines to show */
                    line-clamp: 1; 
            -webkit-box-orient: vertical;
        }
        &>p{
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* number of lines to show */
                    line-clamp: 2; 
            -webkit-box-orient: vertical;
        }
    }
}


& .view_all_btn {
    text-align: center;
    margin-top: 3.5rem;
    & a {
        background-color: var(--secondary-color);
        padding: 0.55rem 3.1rem;
        color: var(--white);
        border-radius: 8rem;
        font-weight: 600;
        transition: 0.5s;
        &:hover{
            background-color: var(--secondary-color-dark);
        }
    }
}
@media (max-width: 1024px){
    padding-top: 4rem;
    padding-bottom: 2rem;
}
@media (max-width: 767px){
    padding-top: 2.5rem;
    & .slider_btns>* {
        width: 3rem;
        height: 3rem;
        font-size: 0.85rem;
        &:first-child{
            margin-right: 0.7rem;
        }
    }
    & .blog_box{
        & .text{
            padding-bottom: 1rem;
            & .title{
                font-size: 1.4rem;
                margin-bottom: 0.85rem;
            }
        }
    }
}
`