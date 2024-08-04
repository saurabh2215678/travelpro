import styled from "vue3-styled-components";

export const PackageSliderCardWrapper = styled.div`
display: flex;
flex-direction: column;
border-radius: 14px;
overflow: hidden;
box-shadow: 0px 0px 11px 0px rgba(191, 191, 191, 0.61);
& .package_image_box {
    height: 14.375rem;
    & img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
}

& .package_info {
    background: #fff;
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
    font-weight: 500;
    font-size: 1rem;
    color: #3a4652;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
& .bottom_options { display: flex; justify-content: space-between; align-items: center; margin-top: 0.86rem; }
& .package_price {
    color: #3a4652;
    font-weight: 500;
    font-size: 1rem;
    text-align: right;
    & span{
        font-size: 0.8rem;
        margin-bottom: -2px;
        display: block;
        color: var(--orange800);
        font-weight: 500;
        line-height: normal;
    }
}
& .counter {
    padding-top: 4px;
    padding-left: 5px;
}
`