import styled from "vue3-styled-components";

export const SearchFormWrapper = styled.form`
width: 45.5rem;
margin-left: auto;
position: absolute;
right: 0;
left: 0;
background: var(--white);
border-radius: 0.5rem;
box-shadow: 0px 10px 10px 0px #00000017;
padding: 1rem;
top: 2rem;
z-index: -1;
& .form-control,
& .selectoption select{border:0}

& .selectoption {
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
    padding: 0.34rem 1rem;
    padding-left: 2.3rem;
    border-radius: 0;
    flex-grow: 1;
    line-height: 2;
    font-size: 0.93rem;
    font-weight: 500;
  }
  & select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
    font-weight: 500;
    color: var(--black700);
    cursor: pointer;
  } 
}
& .option_box {
    display: flex;
    border: 1px solid #00000021;
    border-radius: 5rem 0 0 5rem;
}
& .option_box_wrapper{
  flex-grow: 1;
  position: relative;
    z-index: 6;
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
                pointer-events: none;
            }
        }
    }
}

& .searchbtn .btn {
  background-color: var(--theme-color);
  padding: 0.5rem 1.65rem;
  color: var(--white);
  border-radius: 0;
  height: 100%;
  
  cursor: pointer;
  transition: 0.5s;
  &:hover {
      background-color: var(--theme-color);
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

& .searchbtn { 
  width: 2.65rem; 
  /* margin-left: -1.5315rem;  */
  position: relative; 
  z-index: 6; 
  display: flex;
    background: var(--theme-color);
    text-align: center;
    justify-content: center;
  & button {
    /* background-color: var(--theme-color); */
    display: grid;
    color: #fff;
    place-items: center;
    /* border-radius: 50%;
    width: 100%;
    height: 100%; */
    transition: 0.5s;
    font-size: 0.85rem;
    width: 100%;
    /* padding-top: 2px; */
  }
  & .search_input{
    display: none;
  }
  & button:hover,
  & .search_input:hover{
    background-color: var(--theme-color-dark);
  }
}
& .selectoption>input.form-control{
  border-radius: 9rem 0 0 9rem;
  padding-left: 2.8rem!important;
  padding-top: 0.4rem;
  &::placeholder {
      color: var(--black700);
  }
}
& .search-first.selectoption>svg {
    left: 1.45rem;
    top: 0.65rem;
    max-height: 1.15rem;
    width: 0.9rem;
}
& .selectoption>svg {
    position: absolute;
    width: 0.86rem;
    max-height: 0.96rem;
    z-index: 999;
    top: 0.7rem;
    left: 1rem;
    pointer-events: none;
}
& .selectoption.search-last>svg {
    top: 0.76rem;
}
& .search-mdl:before, 
& .search-mdl:after {
    content: "";
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 60%;
    width: 1px;
    background-color: #d9d9d9;
    z-index: 999;
}
& .search-mdl:after{
  right: 0;
}
& .search-mdl:before{
  left: 0;
}
& .selectoption.search-mdl i, 
& .selectoption.search-last i {
    left: initial;
    pointer-events: none;
}
& .selectoption.search-mdl i {
    right: 1.5rem;
}
& .selectoption.search-last i {
    right: 2.5rem;
}
@media (max-width: 991px){ 
  top: 3rem;


}



@media (max-width: 767px){ 
  width: 20.5rem;
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
      &>input-security.form-control{
        padding-left: 2.3rem!important;
      }
  }
  
  & .searchbtn {
    margin: 0;
    width: 100%;
    & .search_input{
      display: block;
    }
    & button{
      display: none;
    }
  }
  & .search-mdl:before, 
  & .search-mdl:after{
    display: none;
  }
  & .search-first.selectoption>svg{
    left: 1rem;
  }
  & .selectoption input, 
  & .selectoption select {
      border: 1px solid #d6d6d6;
      border-radius: 5px!important;
      flex-grow: initial;
      width: 100%;
  }
  & .selectoption.search-last i{
    right: 1.5rem;
  }
}

`;