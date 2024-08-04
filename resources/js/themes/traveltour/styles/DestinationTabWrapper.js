import styled from "vue3-styled-components";

export const DestinationTabWrapper = styled.section`
/* padding-top: 2.8rem;
& .tabcontent{
    background-color: var(--theme-color50);
    padding: 2rem;
}
& .tablinks {
    padding: 0.7rem 1.3rem;
    border-bottom: 3px solid transparent;
    transition: 0.5s;
    background-color: var(--theme-color40);
    font-weight: 600;
    font-size: 0.89rem;
    &.active{
        border-color: var(--theme-color800);
    }
} */
& .detail_tab_section{
    position: sticky;
    top: 5rem;
    background-color: var(--white);
    z-index: 2;
    border-top: 1px solid var(--black80);
    box-shadow: 1px 8px 14px #0000000d;
    margin: 2rem 0 0;  
    & .scrollspy_btns{
        display: flex;
        margin: 0.2rem -0.5rem;
        & li{
            padding: 0rem 1rem;
        }
        & li>a {
            padding: 0.8rem 0rem;
            margin:0 0.5rem;
            /* box-shadow: 0 1px 7px 0 rgba(0,0,0,.2); */
            /* border-radius: 4px; */
            font-size: 1rem;
            font-weight: 500;
            border-bottom: 3px solid var(--black10);
            &.active{
                /* background-color: var(--theme-color); */
                color: var(--theme-color);
                border-bottom: 2px solid var(--theme-color);
            }
        }
    }
}

& .desti_about_img {
    width: 12rem;
    float: left;
    padding-right: 1rem;
    & img{
        width: 100%;
    }
}
/* & .gallery_popup {
    width: calc(25% - 1rem);
    margin: 0.5rem;
} */
.swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
/* & .destinaton_images_fancy{
    margin : -0.5rem;
} */
& .dest_desc{
    font-size: 0.94rem;
    &:after{
        content: "";
        display: block;
        clear: both;
    }
}

& >.container{
    position: relative;
    & .slider_btns {
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
        width: calc(100% + 2rem);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 1;
        & .slider_btn{
            width: 3.625rem;
            height: 3.625rem;
            background-color: var(--secondary-color);
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: var(--white);
            font-size: 1.2rem;
            transition: 0.5s;
            pointer-events: all;
            &:hover{
                background-color: var(--secondary-color-dark);
            }
            &.swiper-button-disabled{
                background-color: hsl(0deg 0% 87.4%);
                color: var(--black400);
                cursor: no-drop;
            }
            &.swiper-button-lock{
                display:none;
            }
        }
    }
    & .swiper{
        overflow: visible;
        & .swiper-slide{
            pointer-events: none;
            height: auto;
            &>div{
                opacity: 0;
                transition :0.5s
            }
        }
        & .swiper-slide-visible{
            pointer-events: all;
            &>div{
                opacity: 1;
            }
        }
    }
}
& .destinaton_images_fancy{position: relative;}
& #photo_gallery{position:relative;}
& #photo_gallery .slider_btns {
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
        width: calc(100% + 4rem);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 1;
}
& #photo_gallery .slider_btn{
        width: 3.625rem;
        height: 3.625rem;
        background-color: var(--secondary-color);
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: var(--white);
        font-size: 1.2rem;
        transition: 0.5s;
        pointer-events: all;
    }
    & #photo_gallery .slider_btn:hover{
        background-color: var(--secondary-color-dark);
    }
    & #photo_gallery .swiper-button-disabled{
        background-color: hsl(0deg 0% 87.4%);
        color: var(--black400);
        cursor: no-drop;
    }
    & #photo_gallery .swiper-button-lock{
        display:none;
 }


@media (max-width: 1070px){
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}
@media (max-width: 767px){
    padding-left: 0;
    padding-right: 0;
    &>.container .slider_btns{
        display: none;
    }
    & .tabcontent{
        padding: 1rem;
    }
    & .gallery_popup {
        width: calc(33.33% - 1rem);
    }
    & .detail_tab_section{
    & .scrollspy_btns{
        overflow-x: scroll;
        margin: 0rem;
    }
}
& .destination_about_content {
    flex-direction: column;
}
.WTrUR .dest_desc {
    width: 100%;
}
.desti_img {
    width: 100%;
    margin-top: 1rem;
}


}

`;