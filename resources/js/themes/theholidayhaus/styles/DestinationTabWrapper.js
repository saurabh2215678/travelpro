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
& .top_title {
		font-size: 1.5rem;
		font-weight: 600;
		/* font-family: 'PT Serif',serif;
		color: var(--theme-color);
		text-transform: uppercase; 
		margin-bottom: 1.8rem;*/
		&:after{
			content: "";
			display: block;
			background-color: var(--secondary-color);
			height: 3px;
			width: 40px;
		}
	
}
& .inner_content_block .desti_img{
    float: right;
    padding-bottom: 2rem;
    position: relative;
    margin: 0 0rem 0 2rem;
    ::before{
    content: "";
    width: 10px;
    height: 50%;
    position: absolute;
    left: -10px;
    top: -10px;
    background: var(--secondary-color);
    display: block;
    }
    ::after{
    content: "";
    width: 50%;
    height: 10px;
    position: absolute;
    left: -10px;
    top: -10px;
    background: var(--secondary-color);
    display: block;
    }
    img {
      width: 30rem;
    }
}
& .inner_content_block >div p {
    margin-bottom: 0.7rem;
}
& .detail_tab_section{
    /* position: sticky; */
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
        & li:first-child{
            padding-left: 0;
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
& .dest_desc ul {
    list-style: disc;
    padding-left: 25px;
    li{
        padding-bottom: 10px;
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
& .inner_content_block{
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

 /* ====== CMS Style ==== */
 & .inner_content_block h3{
    font-size: 1.45rem;
    font-weight:600;
    color: var(--theme-color);
    margin-top: 1.25rem;
    margin-bottom: 0.625rem;
    position: relative;
    :after{
        content: "";
        display: block;
        width: 3.125rem;
        border-bottom: 2px solid #facf08;
        margin-top: 0.313rem;
    }
 }
 &  .inner_content_block ul.blocks-gallery-grid {
    display: flex;
    flex-wrap: wrap;
    list-style-type: none;
    padding: 0;
    margin: 0;
    width: 100%;
}
& .inner_content_block ul.blocks-gallery-grid li{
    margin: 0 1rem 1rem 0;
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    justify-content: center;
    width: min-content;
    height: 18.75rem;
    overflow: hidden;
    flex-basis: 30.33%;
    padding-left: 0px !important;
    margin-bottom: 0.625rem;
    padding-bottom:0.313rem;
    figure {
        display: inline-block;
        height: 100%;
    }
    a{
        height: 100%;
        width:100%;
    }
    img {
        height: 100%;
        object-fit: cover;
        width: 100%;
    }
}
& .inner_content_block ul.img-data li {
    position: relative;
    padding-bottom: 0.313rem;
    display: inline-block;
    align-items: center;
    margin-bottom: 1.25rem;
    width: 100%;
    padding-left: 0px;
}

& .inner_content_block .img-data li .img-sec {
    width: 50%;
    float: left;
    filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));
}
& .inner_content_block .img-data li .para-sec {
    width: 50%;
    float: left;
    padding-left: 1.563rem;
}
& .inner_content_block .img-data li:nth-child(even) .img-sec{
    float: right;
}
& .inner_content_block .img-data li:nth-child(even) .para-sec{
    padding-left: 0px;
    padding-right: 1.563rem;
}
& .description ul.blocks-gallery-grid {
    display: flex;
    flex-wrap: wrap;
    list-style-type: none;
    padding: 0;
    margin: 0;
    width: 100%;
}
& .description ul.blocks-gallery-grid li {
    margin: 16px 16px 16px 0;
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    justify-content: center;
    width: min-content;
    height: 300px;
    overflow: hidden;
    flex-basis: 30.33%;
    padding-left: 0px !important;
    padding-bottom: 5px;
}
& .description ul.blocks-gallery-grid li figure {
    display: inline-block;
    height: 100%;
    a{
     height: 100%;
     width: 100%;
    }
}
& .description ul.blocks-gallery-grid li img {
    height: 100%;
    object-fit: cover;
    width: 100%;
}
& .description ul li::before {
    content: '\f192';
    position: absolute;
    left: 0;
    color: #0077c1;
    font-family: 'FontAwesome';
    font-size: 14px;
}
& .description p{
    margin-bottom: 0.5rem;
}
& .description ul{
    padding-bottom: 1rem;
    li{
    padding-left: 1.2rem;
    padding-top: 0.5rem;
}

}
& .description ol {
    list-style: auto;
    padding-left: 1.2rem;
    padding-bottom: 1rem;
    li{
        padding-top: 0.5rem;
    }
}
& .description h3 {
    font-size: 1.2rem;
    font-weight: 600;
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
& .detail_tab_section .scrollspy_btns li {
    white-space: nowrap;
}


& .destination_about_content {
    flex-direction: column;
}
.WTrUR .inner_content_block {
    width: 100%;
}
.desti_img {
    width: 100%;
    margin-top: 1rem;
}


}

`;