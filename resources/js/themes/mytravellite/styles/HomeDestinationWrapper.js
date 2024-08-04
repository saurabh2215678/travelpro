import styled from "vue3-styled-components";

export const HomeDestinationWrapper = styled.section`
    padding-top: 0;
    padding-bottom: 3rem;
& .destination-gallery {
    display: flex;
    margin: -1.063rem;
    height: 40.375rem;
}
& .dest_left {
    width: 25%;
}
& .dest_middle{
    width: 35%;
    display: flex;
}
& .dest_right{
    width: 40%;
}
& .dest_card a {
     display: block; position: relative; width: 100%; border-radius: 1.438rem; overflow: hidden; 
     & img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }
     & .detail_box {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        max-height: 100%;
        color: var(--white);
        padding: 1.5rem;
        padding-top: 3rem;
        background-image: linear-gradient(0deg, #00000085, #00000085, transparent);
        transition: all ease 0.5s;
        transform: translateY(calc(var(--box-height) - 5.4rem));
        & p { 
            font-size: 1.2rem;
            color: var(--white);
        }
       
        &>div{
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 7;
            line-clamp: 7;
            -webkit-box-orient: vertical;
            transition: 0.5s;
            transform: translateY(1rem);
        }
    }
    &:hover{
        & .detail_box {
            transform: translateY(0);
            &>div{
                transform: translateY(0);
            }
        }
        & img{
            transform: scale(1.1);
        }
    }
}



& .dest_card { display: flex;padding: 1.063rem; width: 100%;}
& .right_bottom_dest { display: flex; }
& .dest_left>*, 
& .dest_right>* {
    height: 50%;
}
& .right_bottom_dest .dest_card:first-child {
    width: 38%;
}
& .right_bottom_dest .dest_card:last-child {
    width: 62%;
}
& .section_title{
    position: relative;
    width: fit-content;
    margin: 0 auto;
    margin-bottom: 1.25rem;
    padding: 0 2rem;
}


@media (max-width: 767px){
    overflow: hidden;
    padding-bottom: 2rem;
    & .section_title{
        padding: 0;
        margin-bottom: 0;
        & .title_big{
            margin-bottom: 1rem;
        }
    }
    & .destination-gallery{
        margin-top: 0;
        height: 19.375rem;
        overflow: auto;
        padding-left: 1rem;
        padding-right: 1rem;
        & .dest_left,
        & .dest_middle,
        & .dest_right{
            width: initial;
        }
        & .dest_left,
        & .dest_right{
            display: flex;
        }
    }
    & .dest_left>*, 
    & .dest_right>*{
        height: 100%;
    }
    & .dest_card{
        padding: 0.2rem;
        min-width: 14rem;
        height: 100%;
        & a{
            border-radius: 5px;
        }
        & p{
            left: 0.5rem;
            font-size: 0.75rem;
        }
    }
   
}
`