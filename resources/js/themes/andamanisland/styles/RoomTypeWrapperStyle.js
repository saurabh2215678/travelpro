import styled from "vue3-styled-components";

export const RoomTypeWrapper = styled.div`
& .slider_main{
    position: relative;
    margin-left: 0.8rem;
}
& .slider_btns {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    &>*{
        width: 2.25rem;  
        height: 2.25rem;
        background-color: var(--white);
        display: grid;
        place-items: center;
        border-radius: 50%;
        border: 1px solid #d1cdcb;
        color: var(--theme-color);
        &.swiper-button-disabled{
            opacity: 0.4;
            cursor: no-drop;
        }
        &.swiper-button-lock{
            display: none;
        }
    }
    & .slider_btn_prev {
        margin-left: 0.35rem;
    }
  }

  /* & .roomgallery2 .swiper-wrapper {
    height: 15rem
} */

/* & .roomgallery2 .swiper-slide {
    height: 15rem!important
} */
& .room-options.last-border .repeater:last-child {
    border-bottom: 0;
}
& .roomgallery2 .swiper-slide img {
    border-radius: .5rem;
    height: 15rem;
    -o-object-fit: cover;
    object-fit: cover;
    width: 100%
}
& .room-options .plan_options .room-options-type {
    font-size: 1.2rem;
}
& .hotelViewgallery, & .roomgallery{
  width:100%;
  height:300px;
  margin-left:auto;
  margin-right:auto
}
&.room_type>ul>li {
    background: #fff;
    margin-bottom: 1rem;
    box-shadow: 0 2px 5px 1px rgba(64,60,67,.16);
    border-radius: 1rem;
    position: relative;
}
& .hotelViewgallery .swiper-slide, & .roomgallery .swiper-slide{
  background-size:cover;
  background-position:center
}
&.room_type>ul>li:first-child{
  margin-bottom:0
}
&.room_type>ul>li:nth-child(2){
  border-top-left-radius:0;
  border-top-right-radius:0
}
& .hotelViewgallery2, & .roomgallery2{
  height:70%;
  width:100%
}
& .roomgallery, & .roomgallery2{
  width:90%
}
& .hotelViewgallery2 .swiper-slide a, & .roomgallery2 .swiper-slide a{
  width:100%
}
& ul.hotel_adult_child_icon li {
    padding-bottom: 0.4rem;
    display: inline-block;
}
/*! .room-options:after {
  content: '';
  position: absolute;
  right: 24.9%;
  border: 1px solid #CBD5E1;
  top: 0;
  bottom: 0;
} */

/*! .room-options:before {
  content: '';
  position: absolute;
  left: 0%;
  border: 1px solid #CBD5E1;
  top: 0;
  bottom: 0;
} */

& li>.room-options{
  border-left:2px solid #bac9db;
  overflow:hidden
}

& .room-options.last-border .repeater::before{
  height:100%;
  content:'';
  position:absolute;
  width:2px;
  right:50%;
  background:#cbd5e1
}

/*! .last-border .plan_options {
  border-right: 2px solid #bac9db;
} */

& .hotelViewgallery, & .roomgallery{
  height:20%;
  box-sizing:border-box;
  padding:3px 0;
  margin-left:.8rem
}
& .hotelViewgallery .swiper-slide-thumb-active, & .roomgallery .swiper-slide-thumb-active{
  opacity:1
}
& .hotelViewgallery2 .swiper-button-next::after, & .hotelViewgallery2 .swiper-button-prev::after, & .roomgallery2 .swiper-button-next::after, & .roomgallery2 .swiper-button-prev::after{
  font-size:22px!important; 
  background:#ffffff8a;
  width:2rem;
  text-align:center;
  height:2rem;
  line-height:2rem;
  border-radius:.2rem
}
& .hotelViewgallery .swiper-slide img, & .roomgallery .swiper-slide img{
  display:block;
  width:100%;
  /*! height: 100%; */
  height:3.5rem;
  -o-object-fit:cover;
     object-fit:cover;
  border-radius:.5rem
}
/* & .roomgallery2 .swiper-slide img{
  border-top-left-radius:0!important
} */

& .hotelViewgallery .swiper-slide:hover, & .roomgallery .swiper-slide:hover{
  opacity:.5;
  cursor:pointer
}
& .roomgallery .swiper-slide img{
  height:3.5rem;
  -o-object-fit:cover;
     object-fit:cover;
  border-radius:.5rem
}
& .roomgallery .swiper-slide a{
  width:100%
}
& .hotelViewgallery2 .swiper-wrapper{
  height:15rem
}
/* & .hotelViewgallery2 .swiper-slide{
  height:15rem!important
} */
& .hotelViewgallery2 .swiper-slide img, &.roomgallery2 .swiper-slide img{
  height:13rem;
  -o-object-fit:cover;
     object-fit:cover;
  width:100%;
  border-radius:.5rem
}
& .hotel-card{
  box-shadow:0 2px 5px 1px rgba(64,60,67,.16);
  border-radius:1rem
}
& .plan_price .room-price {
    text-align: center;
}
& .plan_price .view_all_btn {
    text-align: center;
}
& .price-list-view .cut-price span:before {
    border-color: #e12d2d!important;
    border-top: 2px solid;
    content: "";
    position: absolute;
    right: 0;
    top: 50%;
    transform: rotate(-10deg);
    width: 100%;
}
& .view_all_btn a {
    padding: 5px 25px;
    text-transform: capitalize;
    background: var(--theme-color);
    color: #fff;
    border-radius: 20px;
    display: inline-block;
    font-size: .8rem;
    :hover{
      background: var(--secondary-color);
      color: #fff;
    }
}

& .hide {
    display: none;
}


@media (max-width: 992px){
        
}

@media (max-width: 767px){
  ul.mobile-show {
    display: block;
}

}
    
`