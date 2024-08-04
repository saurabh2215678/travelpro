import styled from "vue3-styled-components";

export const MyFavouriteWrapper = styled.section`
& .user_profile_inner {
    column-gap: 3rem;
}
& .title-inner{
    background: #F3F4F6;
    padding: 0.5rem 1rem;
    margin-top: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    span{
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color);
    }
    p{
        color: #222;
        font-weight: 400;
    }
}
& .card_box {
    flex-wrap: wrap;
    margin: -0.8rem;
    .card_box_list{
     padding: 0.8rem;
    }
.box_shodow {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 0px 11px 0px rgba(191,191,191,0.61);
}
.package_info {
    background: #fff;
    padding: 1.25rem;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
}
.day_night {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--black700);
    margin-top: -2.4rem;
    padding: 0.4rem 1.1rem;
    margin-bottom: 1.3rem;
    background-color: var(--white);
    border-radius: 4px;
    box-shadow: 0px 0px 22px 0px rgba(0,0,0,0.09);
}
.package_title {
    line-height: 1.2;
    font-weight: 600;
    font-size: 1.06rem;
    color: #3a4652;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.bottom_options {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    margin-top: 0.86rem;
}
 .book_btn {
    color: #fff;
    background: var(--theme-color);
    border: 1px solid var(--theme-color);
    padding: 0.25rem 1rem;
    font-size: 13px;
    font-weight: 500;
    border-radius: 5rem;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    :hover {
    background-color: var(--secondary-color);
}
}
.package_price {
    color: #3a4652;
    font-weight: 700;
    font-size: 1.1rem;
    text-align: right;
}
.package_price span {
    font-size: 0.875rem;
    margin-bottom: -7px;
    display: block;
    color: #3a4652;
    font-weight: 600;
}
}

`