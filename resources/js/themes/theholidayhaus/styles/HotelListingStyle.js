import styled from "vue3-styled-components";

export const ListingWrapper = styled.section`
padding: 3rem 0;
& .hotel_wrap_inner{
  display: flex;
    & .side_bar{
      width: 20rem;
      margin-right: 2.5rem;
      height: calc(100vh - 8rem);
      overflow: hidden;
      position: sticky;
      top: 7rem;
      /* border-radius: 10px;
      border: 1px solid var(--black100); */
      & *::-webkit-scrollbar {
        width: 10px;
      }

      & *::-webkit-scrollbar-track {
        background: #f1f1f1; 
      }
      
      & *::-webkit-scrollbar-thumb {
        background: var(--theme-color); 
        border-radius: 5rem;
      }


      & *::-webkit-scrollbar-thumb:hover {
        background: var(--theme-color);  
      }
      & form{
        height: calc(100% - 3.5rem);
        overflow: auto;
        
      }
    }
  }
& .description_inner{
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
& #disc-title.collapsed .description_inner{
    display: block;
}
& .read_option {
    cursor: pointer;
    display: inline-block;
    color: var(--theme-color);
    font-weight: 700;
    :hover{
      color: var(--orange);
    }
}
& .collapsed .read_option i {
    transform: rotate(180deg);
}

& .filter_box {
    padding: 0.5rem 0;
}
& .filter_box .sidetitle{
    margin-bottom: 8px;
    border-bottom: 1px solid var(--secondary-color);
    padding-bottom: 5px;
}
& .checkbox_list {
    padding-top: 0.1rem;
    padding-bottom: 0.1rem;
    input{
      margin-right: 0.4rem;
    }
    label{
      font-size: 0.85rem;
      cursor:pointer;
      & br{
        display : none;
      }
    }
}
& #disc-title p {
    margin-bottom: 0.8rem;
}
& #disc-title ul {
    list-style: disc;
    margin-left: 1.5rem;
    padding-bottom: 1rem;
}
& .filter_button {
    position: absolute;
    bottom: 0;
    width: 100%;
    background-color: var(--theme-color);
    display: flex;
    & li{
      width: 100%;
      &:first-child{
        border-right: 1px solid var(--white200);
      }
      & .btn,
      & .btn2{
        color: var(--white);
        width: 100%;
        text-align: center;
        padding: 0.5rem 1rem;
        transition: 0.5s;
        &:hover{
          background-color: var(--secondary-color);
        }
      }
    }
}
& .hotel_view {
    flex-grow: 1;
    max-width: calc(100% - 20.5rem);
}
& .hotel_view .hotel-card{
    margin-bottom: 20px;
    border: 1px solid #e7e7e7;
    transition: 0.2s;
}
& .pagination-wrapper{
  text-align: center;
    margin-top: 2.5rem;
}
& .alert_not_found{
  background: #e5e3e061;
  font-size: 1.2rem;
  padding: 1rem;
  margin: auto;
  margin-top: 0.938rem;
  text-align: center;
  font-weight: 600;
  color: var(--black500);
  max-width: 55rem;
  & a{
    color: var(--theme-color);
  }
}

& .hotel-content-list-view .facilities_list .facility_title{
  font-size: 1rem;
  font-weight: 600;
}
& .hotel-content-list-view .facilities_list .hotel_facilities{
    display: inline-block;
    margin-right: 4px;
    margin-bottom: 4px;
    background: #f7f0c8;
    padding: 2px 8px;
    border-radius: 3px;
  }
& .hotel-content-list-view .facilities_list .hotel_facilities span{
      position: relative;
      margin-right: 0.5rem;
      padding-right: 0.8rem;
  }
& .hotel-content-list-view .facilities_list .hotel_facilities span:last-child{
     margin-right: 0rem;
     padding-right: 0rem;
}
& .hotel-content-list-view .facilities_list .hotel_facilities span::before{
      content: '+';
      position: absolute;
      right: 0;
}
& .hotel-content-list-view .facilities_list .hotel_facilities span:last-child::before{
      display:none;
}
& .price-list-view .rating-txt li:last-child {
    border-radius: 5rem 5rem 5rem 0;
    background: var(--secondary-color);
    width: 2.5rem;
    height: 2.5rem;
    margin-left: 1rem;
    font-size: .8rem;
    color: #333;
    line-height: 2.5rem;
    text-align: center;
}
& .price-list-view .cut-price span:before { 
    /* border-color:var(--orange); */
    border-top: 2px solid var(--orange);
    content: "";
    position: absolute;
    right: 0;
    top: 50%;
    transform: rotate(-10deg);
    width: 100%;
}
& .price-list-view .main-price{
  color: var(--theme-color);
  font-weight: 700;
}
& .price-list-view a.view-more {
    background: var(--theme-color);
    color: var(--white);
}
& .price-list-view a.view-more:hover {
    background: var(--secondary-color);
}

& .open_filter_btn,
& .close_filter_btn,
& .filter_backdrop{
  display: none;
}
& .pageLoader {
    display: none;
}
& .pageLoader.active {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 99;
    background: var(--black500);
    display: flex;
    align-items: center;
    justify-content: center;
}
& .pageLoader svg{
   filter: hue-rotate(22deg);
}
& .onewayloader .flight_res_wrap {
    display: flex;
    column-gap: 0.5rem;
}
& .onewayloader .flight_res_wrap .flight_res_left {
    width: 30%;
}
& .onewayloader .flight_res_wrap .flight_res_right {
    width: 70%;
}
& .onewayloader .flight_res_wrap .flight_res_left .w-full.p-4 {
    padding: 4rem;
}
[tooltip] {
    position: relative;
}
[tooltip]:after,[tooltip]:before {
    display: none;
    font-size: .8rem;
    line-height: 1.2;
    opacity: 0;
    pointer-events: none;
    position: absolute;
    text-transform: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none
}

[tooltip]:before {
    border: 5px solid transparent;
    content: "";
    z-index: 1001
}

[tooltip]:after {
    background: var(--theme-color);
    border-radius: .1rem;
    box-shadow: 0 1em 2em -.5em rgba(0,0,0,.35);
    color: #fff;
    content: attr(tooltip);
    font-family: Helvetica,sans-serif;
    max-width: 16rem;
    padding: .5rem 1rem;
    text-align: center;
    text-overflow: ellipsis;
    width: -moz-max-content;
    width: max-content;
    z-index: 1000
}

[tooltip]:hover:after,[tooltip]:hover:before {
    display: block
}

[tooltip=""]:after,[tooltip=""]:before {
    display: none!important
}

[tooltip]:not([flow]):before,[tooltip][flow^=up]:before {
    border-bottom-width: 0;
    border-top-color: var(--theme-color);
    bottom: 100%
}

[tooltip]:not([flow]):after,[tooltip][flow^=up]:after {
    bottom: calc(100% + 5px)
}

[tooltip]:not([flow]):after,[tooltip]:not([flow]):before,[tooltip][flow^=up]:after,[tooltip][flow^=up]:before {
    left: 50%;
    transform: translate(-50%,-.5em)
}

[tooltip][flow^=down]:before {
    border-bottom-color: #333;
    border-top-width: 0;
    top: 100%
}

[tooltip][flow^=down]:after {
    top: calc(100% + 5px)
}

[tooltip][flow^=down]:after,[tooltip][flow^=down]:before {
    left: 50%;
    transform: translate(-50%,.5em)
}

[tooltip][flow^=left]:before {
    border-left-color: #333;
    border-right-width: 0;
    left: -5px;
    top: 50%;
    transform: translate(-.5em,-50%)
}

[tooltip][flow^=left]:after {
    right: calc(100% + 5px);
    top: 50%;
    transform: translate(-.5em,-50%)
}

[tooltip][flow^=right]:before {
    border-left-width: 0;
    border-right-color: #333;
    right: -5px;
    top: 50%;
    transform: translate(.5em,-50%)
}

[tooltip][flow^=right]:after {
    left: calc(100% + 5px);
    top: 50%;
    transform: translate(.5em,-50%)
}

@keyframes tooltips-vert {
    to {
        opacity: .9;
        transform: translate(-50%)
    }
}

@keyframes tooltips-horz {
    to {
        opacity: .9;
        transform: translateY(-50%)
    }
}

[tooltip]:not([flow]):hover:after,[tooltip]:not([flow]):hover:before,[tooltip][flow^=down]:hover:after,[tooltip][flow^=down]:hover:before,[tooltip][flow^=up]:hover:after,[tooltip][flow^=up]:hover:before {
    animation: tooltips-vert .3s ease-out forwards
}

[tooltip][flow^=left]:hover:after,[tooltip][flow^=left]:hover:before,[tooltip][flow^=right]:hover:after,[tooltip][flow^=right]:hover:before {
    animation: tooltips-horz .3s ease-out forwards
}

@media (max-width: 768px){
  & .hotel_wrap_inner .side_bar{
    --inset: 1.5rem;
    position: fixed;
    top: var(--inset);
    left: var(--inset);
    background-color: var(--white);
    z-index: 6;
    width: calc(100% - (var(--inset) * 2));
    height: calc(100% - (var(--inset) * 2));
    border: none;
    margin: 0;
    opacity: 0;
    pointer-events: none;
    transition: 0.5s;
  }
  & .hotel_view{
    max-width: 100%;
  }
  & .open_filter_btn,
  & .filter_backdrop{
    display: block;
  }
  
  & .open_filter_btn,
  & .filter_backdrop{
    position: fixed;
  }
  & .open_filter_btn{
    bottom: 3rem;
    left: 1rem;
    width: 3.5rem;
    height: 3.5rem;
    background-color: var(--theme-color);
    color: var(--white);
    display: grid;
    place-items: center;
    border-radius: 50%;
    font-size: 1.2rem;
    transition: 0.5s;
    z-index: 1;
    border: 3px solid;
  }
  & .filter_backdrop{
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: var(--black500);
    opacity: 0;
    pointer-events: none;
    z-index: 5;
    transition: .5s;
  }
  & .close_filter_btn {
      --size: 2.37rem;
      position: fixed;
      right: 0.8rem;
      top: 0.8rem;
      background-color: var(--theme-color);
      z-index: 7;
      display: grid;
      color: var(--white);
      border-radius: 50%;
      width: var(--size);
      height: var(--size);
      opacity: 0;
      pointer-events: none;
      place-items: center;
      font-size: 0.85rem;
      transition: .5s;
  }
  .sidebar_opened & {
    & .filter_backdrop,
    & .side_bar,
    & .close_filter_btn{
      opacity: 1;
      pointer-events: all;
      margin-top: 3.5rem;
    }
  }
 & .hotel_view .hotel-card ul li .flex{
    display: block;
   >*{
    width: 100%;
    padding: 0 0.7rem;
  }
 }
 & .price-list-view a.view-more {
    margin-top: 0;
    margin-left: 1rem;
}


}
`