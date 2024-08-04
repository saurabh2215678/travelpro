import styled from "vue3-styled-components";

export const DestinationBox2Wrapper = styled.div`
& .title_box {
    display: flex;
    align-items: center;
    margin-bottom: 1.3rem;
    & .title{
        font-size: 1.7rem;
        font-weight: 600;
    }
    & .right_btn{margin-left: auto;}
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

& .pagination .swiper-pagination-bullets.swiper-pagination-horizontal{bottom: -2rem;}
& .pagination .swiper-pagination-bullet{width: 30px;height: 10px;display: inline-block;background: transparent!important;border: 1px solid var(--theme-color);border-radius:15px;}
& .pagination .swiper-pagination-bullet-active {width: 30px!important;height: 10px!important;display: inline-block;background: var(--theme-color)!important;border: 1px solid var(--theme-color);border-radius:15px;}
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

& .dest_card a {
    /* overflow: hidden; */
     /* :before{content:'';position:absolute;top:0;left:0;background-color:#0000007d;width:100%;height:100%;z-index:1;} */
     :after{content: ''; position: absolute; top: 0; left: 0; width: 0; height: 100%; background-color: rgba(255, 255, 255, 0.4); transition: none;}
     & img {
        width: 100%;
        height: 100%;
        /* min-height: 19rem; */
        object-fit: cover!important;
        transition: 0.5s;
        max-height:490px;

    }
     & .detail_box {
        position: absolute;
        top:50%;
        left: 50%;
        /* width: 100%; */
        max-height: 100%;
        color: var(--white);
        transform: translate(-50%, -50%);
        z-index: 9;
        /* padding: 1.5rem;
        padding-top: 3rem;
        background-image: linear-gradient(0deg,#0000009c calc(100% - 9rem),#0000009c,transparent); */
        transition: all ease 0.5s;
        /* transform: translateY(calc(var(--box-height) - 5.4rem)); */
        width: 100%;
        text-align: center;
        & h3 { 
            font-size: 1.5rem;
            color: var(--white);
            /* text-align: center; */
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
    /* &:hover{
        :after{width: 100%;background-color: rgba(255, 255, 255, 0);transition: all 0.6s ease-in-out;}
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
& .destination_slider{
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
}






& .detail_box_txt {
    background: var(--white);
    padding: 3rem 4rem;
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    h3{
        border-bottom: 2px solid var(--theme-color);
        margin-bottom: 2rem;
        padding-bottom: .5rem;
        width: fit-content;
        font-weight: 700;
        line-height: normal;
    }
}
& .detail_box_img {overflow: hidden;}
& .destination_list .destination_box:nth-child(odd) .desti_link{flex-direction: row-reverse;}
& .btn_theme {
    margin-top: 3.75rem;
    span{
        img{
            min-height: auto;
            width: auto;
            height: auto;
        }
    } 
}
& .desti_link:hover{color: #000;}
& .desti_link:hover .btn_theme{
    color: #000;
    span{
        color: var(--theme-color);
        background: var(--theme-color);
        transform: translateX(0.5rem);
    }
}
@media (max-width: 1100px){
   & .slider_btns{
    display: none;
   }
}
@media (max-width: 768px){
    & .destination_box {
        margin-bottom: 3rem;
    }
    & .detail_box_txt{
        padding: 2rem;
    }
    & .title_box {
        flex-direction: column;
        align-items: flex-start;
        & .right_btn{
            margin-top: 1rem;
            margin-left: 0;
            margin-right: auto;
        }
    }
}
`