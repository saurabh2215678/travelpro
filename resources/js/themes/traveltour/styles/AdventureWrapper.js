import styled from "vue3-styled-components";

export const AdventureWrapper = styled.section`
padding: 3rem 0;
padding-top: 7rem;
position: relative;
&>.container {
    position: relative;
    z-index: 1;
}
& .section_title {
    padding-bottom: 3rem;
}
& .section_grid { display: flex; width: 100%; position:relative;}
& .section_grid .section_item {
    display: flex;
    flex-direction: column;
    padding: 0 0.5rem;
    height: 100%;
}
& .adv_img_box>img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
& .adv_img_box { width: 16.563rem; height: 16.563rem; margin: auto; border-radius: 50%; overflow: hidden; margin-bottom: 2.688rem; position:relative;}
& .section_card_title { font-family: 'PT Serif', serif; font-size: 1.625rem; color: var(--theme-color); margin-top: auto;}
& .section_card_title br {
    display: none;
}
& .section_content {
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}
& .section_card_description {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    font-size: 0.938rem;
    line-height: 1.5;
    margin: 0.7rem 0;
    margin-bottom: 1.7rem;
}
& .section_content .btn { background-color: var(--secondary-color); color: var(--white); font-size: 1.188rem; font-weight: 800; padding: 0.75rem 4.2rem; text-transform: uppercase; border-radius: 5rem; margin-top: auto; transition: 0.5s;}
& .section_content .btn:hover{background-color: var(--secondary-color-dark);}
& .adv_img_box:before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transform: scale(0.85);
    border-radius: 50%;
    transition: 0.5s;
    border: 1px solid var(--white);
}
& .section_grid .section_item:hover .adv_img_box:before{
    transform: scale(0.9);
}
& .sail_bg { position: absolute; left: 7.313rem; top: -3.7rem; width: 5.25rem; }
& .full_bg {
    position: absolute;
    bottom: -15rem;
    left: 0;
    width: 100%;
    z-index: -1;
}
& .slider_btns {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    width: calc(100% + 4rem);
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    display:none;
    &>*{
        width: 2.5rem;
        height: 2.5rem;
        background-color: var(--theme-color);
        color: var(--white);
        display: grid;
        place-items: center;
        border-radius: 50%;
        font-size: 1rem;
        pointer-events: all;
        transition: all ease 0.5s;
        &:hover{
            background-color: var(--secondary-color);
        }
    }
    &>.swiper-button-disabled{
        background-color: #c8c8c8;
        cursor: no-drop;
    }
    &>.swiper-button-lock{
        display:none;
    }
}
@media (max-width: 1100px){
    padding: 3rem 0;
    & .adv_img_box {
        width: 15rem;
        height: 15rem;
    }
}
@media (max-width: 1024px){
    & .section_title {
        padding-bottom: 1.4rem;
    }
    & .sail_bg {top: -2rem;}
    & .slider_btns {
        display: flex;
        width: 100%;
    }
    & .section_grid {
        padding: 0 1.5rem;
    }
    & .adv_img_box {
        margin-bottom: 1rem;
    }
    & .section_card_description {
        margin: 0.4rem 0;
        margin-bottom: 1rem;
    }
}
@media (max-width: 767px){
    & .sail_bg {
        top: -1.5rem;
        left: 1.5rem;
        width: 3.5rem;
    }
    & .full_bg{
        bottom: -12rem;
        min-height: 30rem;
        object-fit: cover;
    }
}
`