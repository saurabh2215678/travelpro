import styled from "vue3-styled-components";

export const ListingWrapper = styled.section`
padding: 3rem 0;
& .packaging_wrap_inner{
  display: flex;
    & .side_bar{
      width: 15rem;
      margin-right: 1.5rem;
      height: calc(100vh - 8rem);
      overflow: hidden;
      position: sticky;
      top: 7rem;
      border-radius: 10px;
      border: 1px solid var(--black100);
      & *::-webkit-scrollbar {
        width: 6px;
      }

      & *::-webkit-scrollbar-track {
        background: #f1f1f1; 
      }
      
      & *::-webkit-scrollbar-thumb {
        background: #cecece; 
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
& .filter_box {
    padding: 0.5rem 0;
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
& .filter_button {
    position: absolute;
    bottom: 0;
    width: 100%;
    background-color: var(--theme-color);
    display: flex;
    & li{
      width: 50%;
      &:first-child{
        border-right: 1px solid var(--white200);
      }
      & .btn,
      & .btn2{
        color: var(--white);
        width: 100%;
        text-align: center;
        padding: 1rem;
        transition: 0.5s;
        &:hover{
          background-color: var(--secondary-color);
        }
      }
    }
}
& .packaging_view {
    flex-grow: 1;
    max-width: calc(100% - 16.5rem);
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
@media (max-width: 768px){
  & .packaging_wrap_inner .side_bar{
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
    bottom: 1rem;
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
    }
  }
}
`