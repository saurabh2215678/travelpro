import styled from "vue3-styled-components";

export const DestinationBox2Wrapper = styled.div`
& .title_box {
    display: flex;
    align-items: center;
    margin-bottom: 1.3rem;
    & .title{
        font-size: 34px;
        font-weight: 600;
        text-transform: capitalize;
    }
    & .right_btn{margin-left: auto;min-width: 150px;text-align: right;}
}
& .right_btn a:hover{color: var(--white)!important;}
& .left_item p {
    font-size: 1.063rem;
    font-weight: 500;
}
& .slider_btns {
    display: flex;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    width: calc(100% + 3rem);
    justify-content: space-between;
    pointer-events: none;
    &>*{
        width: 2.58rem;
        height: 2.58rem;
        background-color: var(--theme-color);
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: var(--white);
        pointer-events: all;
        &.swiper-button-disabled{
            opacity: 1;
            cursor: no-drop;
            background-color: var(--white);
            box-shadow: 0 0 7px #0000001a;
            color: var(--black300);
        }
        &.swiper-button-lock{
            display: none;
        }
    }
    & .slider_btn_prev {
        margin-left: 0.35rem;
    }
}


& .destination_view {
    padding: 0px 5px 5px;
    width: 100%;
    float: left;
}
& .destimg {
    position: relative;
    height: 17.2rem;
    display: flex;
    & img{
        width: 100%;
        object-fit: cover;
        object-position: center;
    }
}
& .package_info{
    text-align: left;
    position: relative;
    background: #fff;
    box-shadow: 0 3px 3px #ccc;
    width: 100%;
    float: left;
    z-index: 9;
    padding: 0;
    min-height: auto;
    & a{
        font-size: 16px;
        color: #111113;
        padding: 18px;
        display: block;
        font-weight: 600;
        line-height: 1.1;
        & span{
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    }
}
& .noswiper-slide {
    padding-bottom: 25px;
}
& .count-dist {
    color: #808087;
}
& .dest_card a {
     display: block; position: relative; width: 100%; /*border-radius: 15px; overflow: hidden;*/ 
     & img {
        width: 100%;
        height: 100%;
        height: 15rem;
        object-fit: cover !important;
        transition: 0.5s;
        border-radius: 15px;
    }
     & .detail_box {
        /* position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        max-height: 100%;
        color: var(--white);
        padding: 1.5rem;
        padding-top: 3rem;
        background-image: linear-gradient(0deg,#0000009c calc(100% - 9rem),#0000009c,transparent);
        transition: all ease 0.5s;
        transform: translateY(calc(var(--box-height) - 5.4rem)); */
        & p { 
            font-size: 1.3rem;
            /* color: var(--white); */
            text-align: center;
        }
       
        /* &>div{
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 7;
            line-clamp: 7;
            -webkit-box-orient: vertical;
            transition: 0.5s;
            transform: translateY(1rem);
        } */
    }
    /* &:hover{
        & .detail_box {
            transform: translateY(0);
            &>div{
                transform: translateY(0);
            }
        }
        & img{
            transform: scale(1.1);
        }
    } */
}
/* & .destination_slider{
    position: relative;
    &:before{
        content: "";
        position: absolute;
        top: -1rem;
        right: 100%;
        width: 50vw;
        height: calc(100% + 2rem);
        background-color: var(--white);
        z-index: 2;
    }
} */
@media (max-width: 1100px){
   & .slider_btns{
    display: none;
   }
}
@media (max-width: 768px){
    & .title_box {
        flex-direction: column;
        align-items: flex-start;
        & .right_btn{
            margin-top: 1rem;
            margin-left: 0;
            margin-right: auto;
            text-align: left;
        }
    }
    & .title_box .title{
        font-size: 25px;
    }
    & .noswiper-slide { width: 47%;}
    .noswiper-wrapper {display: flex;flex-wrap: wrap;justify-content: center;}
}
`