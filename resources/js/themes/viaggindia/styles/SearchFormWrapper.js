import styled from "vue3-styled-components";

export const SearchFormWrapper = styled.form`
max-width: 43.5rem;
& .selectoption {
  border: 1px solid var(--black100);
  position: relative;
  border-radius: 0;
  display: flex;
  border-right: 0;
  border-top: 0;
  border-bottom: 0;
  &:first-child{
    border: 0;
  }
  &>i {
    font-size: 1rem;
    position: absolute;
    top: 50%;
    left: 0.7rem;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--black400);
  }
  & input:focus,
  & select:focus{
    outline: none;
  }
  & input,
  & select{
    font-size: 0.85rem;
    padding: 0.62rem 1rem;
    padding-left: 2.3rem;
    border-radius: 0;
    flex-grow: 1;
    line-height: 2;
  }
  & select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
  } 
}
& .option_box {
    display: flex;
    border: 1px solid var(--black100);
}
& .option_box_wrapper{
  background: #e3e6e8;
  flex-grow: 1;
}
& .search_packages, 
& .search_hotels {
    position: absolute;
    top: 100%;
    width: 100%;
    background-color: var(--white);
    box-shadow: -2px 8px 25px #00000042;
    z-index: 4;
    border-radius: 0;
    overflow: hidden;
    &>ul {
        max-height: 9rem;
        overflow: auto;
        padding: 0.5rem 0;
        &:empty{
            display: none!important;
        }
        & li{
            cursor: pointer; 
            padding: 0.3rem 1rem;
            &:hover{
                background-color:var(--theme-color40);
            }
            & i{
                margin-right: 0.5rem;
                color: var(--theme-color800);
            }
        }
    }
}

& .searchbtn .btn {
  background-color: var(--orange);
  padding: 0.5rem 1.65rem;
  color: var(--white);
  border-radius: 0;
  height: 100%;
  cursor: pointer;
  transition: 0.5s;
  &:hover {
      background-color: var(--orange-dark);
  }
}
& .passenger_wrap {
    display: none;
    flex: 1;
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 0 15px 0 #00000040;
    color: #000;
    max-height: 18rem;
    opacity: 1!important;
    overflow: auto;
    padding: 1rem;
    top: 2.7rem;
    transition: all .5s ease;
    position: absolute;
    left: 0;
    right: 0;
    width: 22rem;
    z-index: 99999;
   .guest_room_box label{
    font-size: 0.8rem;
    }
  .selc_btn {
    width: 6rem;
    padding: 0.5rem 0.2rem;
    background-color: #fff;
    box-shadow: rgba(0,0,0,.13) 0 1px 4px 0;
    border-radius: 4rem;
    text-align: center;
}
.selc_btn span {
    font-size: .8rem;
    color: var(--theme-color);
    cursor: pointer;
    padding: 0 0.3rem;
}
button.btn2 {
    color: var(--theme-color);
    border-width: 1px;
    border-color: var(--theme-color);
    border-radius: 5rem;
    :hover{
      background: var(--theme-color);
      color: var(--white);
    }
}

}
@media (max-width: 767px){ 
  & .option_box {
    display: block;
    border: 0;
}
& .flex.gap-2{
  display: block;
}
& > .flex{
  flex-direction: column;
}
& .option_box_wrapper {
    padding: 0;
    background: transparent;
}
& .selectoption {
    margin-bottom: 0.5rem;
    border: 0;
}
& .selectoption input, & .selectoption select {
    /* font-size: 0.85rem;
    padding: 0.62rem 1rem;
    padding-left: 2.3rem; */
    border-radius: 0.2rem;
}



}

`;