import styled from 'vue3-styled-components';

const SinglePackageWrapper = styled.section`

& .theme_title {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    & .title {
        color: var(--theme-color);
        font-size: 1.875rem;
        font-weight: 600;
    }
}
& .day_night_detail{
    color: var(--orange);
    display: block;
    font-size: 0.9rem;
    line-height: 2.1;
    margin: 0 0.5rem;
    float: right;
    font-weight: 400;
}
& .city-list-block {
    align-items: center;
    display: flex;
    justify-content: space-between;
    & ul{
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }
}
& .package_tour_type_text {
    border: 1px solid var(--theme-color600);
    padding: 3px 9px;
    border-radius: 3px;
    line-height: normal;
    margin-top: 7px;
    margin-right: 0.3rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--theme-color);
    background-color: var(--theme-color40);
    text-transform: uppercase;
}
& .city-list-block ul li span {
    padding-right: 0.5rem;
}
& .full_field {
    width: 100%;
}

& .btn-default.show-modal{
    background-color: var(--theme-color);
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: .8rem;
    margin-top: 0;
    padding: 0.5rem 1rem;
    border-radius: 0px;
    :hover{
        background: var(--secondary-color);
    }
}
& .justify_btwn {
    flex-direction: row-reverse;
    justify-content: space-between;
}
/* & .right_price_box {
    width: 48%;
} */
& .left_price_box {
    height: 25rem;
    width: 48%;
    position: relative;
}
& .package_detail_img {
    height: 100%;
}
& .package_detail_img .swiper-button-next, 
& .package_detail_img .swiper-button-prev {
    background: #00000080;
    border-radius: 50%;
    height: 35px;
    width: 35px;
}
& .package_detail_img .swiper-slide img {
    cursor: pointer;
    height: 25rem;
    -o-object-fit: cover;
    object-fit: cover;
    width: 100%;
}
& .swiper-button-next:after, 
& .swiper-button-prev:after {
    color: #fff;
    font-size: 15px;
}
& .slider_box .swiper .swiper-slide .tour_category_box .images img {
    height: 12rem;
    width: 100%;
    object-fit: cover;
}
& ul.flight_list li {
    display: flex;
    align-items: center;
    column-gap: 0.5rem;
}
& .package_tour_type_text:empty{display:none;}
& .package_type label {
    cursor: pointer;
    /* font-weight: 600;
    color: #222325;
    display: block;
    margin-bottom: 5px; */
}
& .custom_select {
    position: relative;
    background: #fff;
    border-radius: 4px;
}
& .custom_select:after {
    content: '\f107';
    position: absolute;
    top: 1px;
    right: 1px;
    bottom: 1px;
    font-family: FontAwesome;
    background: #f1f1f1;
    width: 22px;
    line-height: 28px;
    text-align: center;
    border-radius: 0 3px 3px 0;
    pointer-events: none;
}
& .single_package_day {
    background-size: 100% 100%;
    background-position: center center;
    border-radius: 11px;
    width: 100%;
    /* margin-top: 0.5rem; */
}
& .booking_fields{
    border-radius: 3px;
    padding: 7px 16px;
    background: #fff;
    border: 1px solid #ccc;
    margin: 0 0 12px;
    width: 100%;
}
& .form_control {
    display: block;
    width: 100%;
    padding: 0.25rem 0.75rem;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
& .custom_select select.form_control {
    font-size: 14px;
    padding: 0.4rem 0.75rem;
}
& .booking_fields legend {
    border: none;
    /* background: url(../img/select-travel.png) 5px center no-repeat; */
    margin-bottom: 5px;
    width: auto;
    padding: 6px 10px 6px 10px;
    font-size: .9rem;
    text-transform: uppercase;
    font-weight: 700;
}
& .booknow_btn {
    align-items: center;
    border: 1px solid var(--theme-color);
    border-radius: 5px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 0 20px 5px;
    width: 100%;
}
& .booknow_btn .price_box:first-child {
    text-align: center;
    padding: 5px;
}
& .booknow_btn .btn_group {
    display: block;
}
& .booknow_btn .price_box span {
    font-size: 1.563rem;
    color: var(--theme-color);
    font-weight: 700;
}
& .booknow_btn .price_box .old_price {
    font-size:1.2rem !important;
    opacity: .8;
    display: block;
    text-decoration: line-through;
}
& .listing_btn li a {
    width: 100%;
    margin: 5px 0;
    border-radius: 6px;
    display: flex;
    text-align: center;
    align-items: center;
    white-space: inherit;
    padding: 0.5rem 1rem;
    text-transform: capitalize;
    justify-content: center;
    border-radius: 50px;
    /* font-size: .75rem; */
}
& .right_price_box .booknow_btn .btn_group li > .btn {
    background: var(--secondary-color);
    padding: 0.5rem 1rem;
    border-radius: 5rem;
    font-size: .9rem;
    margin: 0.5rem 0 0;
    color: var(--white);
    cursor: pointer;
    :disabled{
        opacity: 0.5;
        cursor: default;
    }
    :hover{
        background: var(--theme-color);
        color: var(--white);
    }
}
& ul.inclusions {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
    overflow: hidden;
}
& ul.inclusions li {
    text-align: center;
    /* width: 3.5rem; */
    min-width: min-content;
    line-height: 12px;
    font-size: .7rem;
    padding: 0 5px;
}
& .inclusions li img {
    height: 2rem;
    -o-object-fit: cover;
    object-fit: cover;
    margin: 0 auto;
}
& .hotel_package_detail .hodel_detail_list .hotel_info .dest_title {margin-bottom: 0;} 
& a.more_btn{
    display: none;
}
& .bg_share {
    align-items: center;
    background: transparent;
    display: flex;
    padding: 0 10px;
    width: 100%;
}
& .footer_bottom_left, 
& .footer_bottom_right {
    /* width: 100%; */
}
& .social_icon {
    align-items: center;
    color: #fff;
    display: flex;
    margin: 1rem 0;
    padding: 0;
}
& .social_icon li {
    padding: 0 4px;
}
& .bg_share .social_icon a {
    height: 2rem;
    /* width: 2rem; */
    fill: var(--white);
    border-radius: 4px;
    display: flex;
    place-content: center;
    align-items: center;
    padding: 0.5rem 1rem;
    gap: 0.2rem
}
& .bg_share .social_icon a:hover {
    opacity: 0.6;
}
& .social_icon .facebook a {
    background-color: #3b5998;
    color: #fff;
}
& .bg_share .social_icon .twitter a {
    background-color: #1da1f2;
    color: #fff;
}
& .social_icon .whatsapp a {
    background-color: #379c48;
    color: #fff;
}
& .social_icon .instagram a {
    background: radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%), radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%), radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent), linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);
    color: #fff;
}
& ul.activ_list li{
    list-style: none;
    display: inline-block;
    padding: 3px 15px 4px;
    border-radius: 15px;
    font-size: 13px;
    margin-right: 10px;
    background: #fff;
    border: 1px solid #c8c8c8;
    color: #3e3e3e;
    margin-bottom: 0.3rem;
    & br{
        display: none;
    }
}
& .package_disc.overview_box {
    /* background: #f7f7f7;
    border: 1px solid #ddd;
    border-radius: 0.3rem; */
    & p{
        font-size: 1rem;
        padding-bottom: 10px;
        line-height: 1.5;
    }
}
& span#s_more {
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
}
& .package_day .faqlist li.faq_main {
    margin-bottom: 1rem;
    position: relative;
    padding: 5px 0;
    &>div {
        display: grid;
        border-bottom: 1px dashed #bdbaba;
    }
    & .faq_title{
        padding: 0;
        margin: 5px 12px 5px 0;
        cursor: pointer;
        border-top: 0;
        position: relative;
        font-weight: 600;
    }
}
& .package_day .faqlist li.faq_main:last-child>div{
    border-bottom: 0;
}
& .day_curcle {
    position: absolute;
    left: 0;
    top: 8px;
}
& .package_day .faqlist li.faq_main .day_curcle>span{
    background: var(--theme-color);
    width: 42px;
    height: 42px;
    border-radius: 50px;
    font-size: 11px;
    color: #fff;
    text-align: center;
    padding: 10px;
    text-transform: uppercase;
    line-height: 15px;
    margin-right: 20px;
}
& .faq_title:after {
    content: "";
    color: #000;
    top: 25px;
    right: 5px;
    transform: rotate(225deg);
    transition: .3s;
    position: static;
    display: inline-block;
    float: right;
    margin-top: 5px;
    border-left: 2.5px solid var(--theme-color);
    border-top: 2.5px solid var(--theme-color);
    height: 10px;
    width: 10px;
}
& .faq_title.active {
    color: var(--theme-color);
}

& .faq_title.active::after {
    transform: rotate(45deg);
    color: var(--theme-color);
}
& .package_day .faqlist li.faq_main .faq_data {
    padding: 0 0 20px;
    display: none;
}
& .right-content-itinerary p {
    font-size: 14px;
}
& .faq_data p{
    margin-top: 0;
}
& .package_day .faqlist li.faq_main:after{
    content: '';
    position: absolute;
    height: 100%;
    width: 1px;
    top: 25px;
    border-left: 1px dashed #bdbaba;
    left: 20px;
    z-index: -1;
}
& .tags {
    padding: 3px 15px 3px;
    margin: 2px 0;
    text-align: center;
    display: inline-block;
    font-size: 12px;
    border: 1px solid #c8c8c8;
    border-radius: 20px;
    text-transform: capitalize;
    margin-right: 0.5rem;
}
&>.w-full>.row>.container>.flex>.row-cols-2{
    max-width: 100%;
}
& .listSec{
    padding-top: 2rem;
}
& .listSec ul li {
    display: inline-block;
    background: #fff;
    a{
    font-size: 1rem;
    color: #333;
    font-weight: 600;
    transition: .3s;
    padding: 0.5rem 1.2rem;
    display: inline-block;
    }
}
& .listSec li.active a{
    color: #f0562f;
    text-decoration: none;
    border-bottom: 2px solid #f0562f;
}
& .btn_enquire{
    background: var(--white);
    border: 1px solid var(--theme-color);
    color: var(--theme-color);
    padding: 0.4rem 1.2rem;
    border-radius: 5rem;
    :hover{
        background: var(--secondary-color);
        border: 1px solid var(--secondary-color);
        color: var(--black);
    }
}

@media (max-width: 992px){
    &>.w-full>.row>.container>.flex>.row-cols-2{
        padding-right: 0;
        width: 100%;
    }
    &>.w-full>.row>.container>.flex_package> .mo_full{
        padding-right: 0;
        width: 100%;
    }
}
@media (max-width: 855px){
& .form_box>.flex{
    flex-direction: column-reverse;
    & .right_price_box,
    & .left_price_box{
        width: 100%;
    }
    & .left_price_box{
        margin-bottom: 2rem;
    }
}
& .city-list-block{
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 0.8rem;
    & ul{
        width: 100%;
        margin-top: 0.5rem;
    }
}
& .single_btn ul .traveller_pricing_inner{
    width: 50%;
}
& .inclusions_share{
    flex-direction: column;
    & .left_side,
    & .share-section{
        width: 100%;
        padding: 0;
    }
    & .share-section>*,
    & .share-section>*>*{
        padding: 0;
    }
}
& .footer_bottom_right {
    width: initial;
}
& .faqlist iframe{
    max-width: 100%;
}
}
@media (max-width: 767px){
    & .booking_fields legend {
        margin-bottom: 0;
        padding-bottom: 0;
    }
    & .single_package_white {
        display: grid;
    }
    & .booknow_btn .btn_group {display:flex;align-items: baseline;width:100%;justify-content:space-between;}
    & .listing_btn li a{margin: 0.5rem 0 0;display: inline-block;}
}

`

export {SinglePackageWrapper}