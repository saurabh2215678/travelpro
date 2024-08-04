import styled from "vue3-styled-components";

export const PackageSliderCardWrapper = styled.div`
display: flex;
flex-direction: column;
border-radius: 14px;
overflow: hidden;
/* box-shadow: 0px 0px 11px 0px rgba(191, 191, 191, 0.61); */
& .package_image_box {
    height: 28.125rem;
    & img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
    }
}

& .package_info {
    /* background: #fff; */
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
& .pack_day_night {
    font-weight: 600;
    color: var(--black700);
    margin-top: -2.4rem;
    padding: 0.4rem 1.1rem;
    margin-bottom: 1.3rem;
    background-color: var(--white);
    border-radius: 4px;
    box-shadow: 0px 0px 22px 0px rgba(0, 0, 0, 0.09);
    &:before{
        content: "\f017";
        font-family: "Font Awesome 6 Free";
        font-weight: 500;
        font-size: 14px;
        margin-right: 7px;
        color: var(--theme-color);
        margin-left: -2px;
    }
}
& .package_title {
    line-height: 1.2;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 1.125rem;
    font-family: 'Jost',sans-serif;
    color: rgb(55, 20, 12);
    font-weight: bold;
}
& .rating_box {
    display: flex;
    position: relative;
    justify-content: space-between;
    font-size: 12px;
    margin-right: auto;
    align-items: center;
    padding-top: 1rem;
    margin-top: auto;
    & .vue-star-rating-rating-text{display:none;}
    & svg {
        width: 0.85rem;
        height: auto;
        margin-right: 0.15rem;
    }
}
& .book_btn {
    color: #fff;
    background: var(--theme-color);
    border: 1px solid var(--theme-color);
    padding: 0.25rem 1rem;
    font-size: 13px;
    font-weight: 500;
    border-radius: 5rem;
    transition: 0.5s;
    &:hover{
        background-color: var(--theme-color100);
        color: var(--theme-color);
    }
}
& .bottom_options { display: flex; align-items: center; margin-top: 0.86rem;justify-content:flex-end }
& .package_price {
    font-size: 1.2rem;
    font-family: 'Jost',sans-serif;
    color: rgb(55, 20, 12);
    font-weight: 600;
    & span{
        font-size: 0.875rem;
        margin-bottom: -7px;
        display: block;
        color: #3a4652;
        font-weight: 600;
    }
}
& .counter {
    padding-top: 4px;
    padding-left: 5px;
}

& .book_btn{color: #fff; background: var(--theme-color); border: 1px solid var(--theme-color); padding:0.25rem 1.4rem; border-radius: 5rem; transition: 0.5s; font-size: 0.875rem; font-family: 'Jost',sans-serif; font-weight: 400;}

@media (max-width: 767px){
    & .package_image_box{height: 24.875rem;}
    & .bottom_options{justify-content:flex-start;}
}

`