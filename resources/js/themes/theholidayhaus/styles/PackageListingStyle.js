import styled from "vue3-styled-components";

export const ListingWrapper = styled.section`
padding: 3rem 0;
background: #faf7f2;
& .packaging_wrap_inner{
  display: flex;
    & .side_bar{
      width: 23rem;
      margin-right: 2.5rem;
      height: calc(100vh - 8rem);
      overflow: hidden;
      position: sticky;
      top: 7rem;
      background: #fff;
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
        padding: 10px;
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
    /* border-bottom: 1px solid var(--theme-color100); */
}
& .filter_box .sidetitle{
    margin-bottom: 8px;
    border-bottom: 1px solid var(--theme-color200);
    padding-bottom: 5px;
}
& .checkbox_list {
    padding-top: 0.3rem;
    padding-bottom: 0.3rem;
    input{
      margin-right: 0.4rem;
      width: 15px;
      height: 15px;
      position: relative;
      top: 2px;
    }
    label{
      font-size: 0.85rem;
      cursor:pointer;
      & br{
        display : none;
      }
    }
}
& .filter_button {
    position: absolute;
    bottom: 0;
    width: 94%;
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
& .packaging_view {
    flex-grow: 1;
    max-width: calc(100% - 22.5rem);
}
& .packaging_view #disc-title p {
    margin-bottom: 0.7rem;
}
& .packaging_view #disc-title ul {
    margin-bottom: 0.8rem;
    list-style-type: disc;
    padding-left: 1.5rem;
}
& .packaging_view #disc-title ul li {
    padding-bottom: 0.4rem;
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
& .open_filter_btn,
& .close_filter_btn,
& .filter_backdrop{
  display: none;
}
.cut-price span:before {
    border-color: #e12d2d!important;
    border-top: 2px solid;
    content: "";
    position: absolute;
    right: 0;
    top: 50%;
    transform: rotate(-10deg);
    width: 100%;
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


@media (max-width: 768px){
  & .packaging_wrap_inner .side_bar{
    --inset: 1.5rem;
    position: fixed;
    top: var(--inset);
    left: var(--inset);
    background-color: var(--white);
    z-index: 6;
    width: calc(100% - (var(--inset) * 2));
    height: calc(80% - (var(--inset) * 2));
    border: none;
    margin: 0;
    opacity: 0;
    pointer-events: none;
    transition: 0.5s;
  }
  & .checkbox_list label {
    font-size: 1.2rem;
}
& .filter_box .sidetitle {
    font-size: 1.4rem;
}
  & .packaging_view{
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
      margin-top: 10.5rem;
    }
  }
}
`