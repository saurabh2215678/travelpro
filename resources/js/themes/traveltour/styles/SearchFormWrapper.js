import styled from "vue3-styled-components";

export const SearchFormWrapper = styled.form`
  box-shadow: 2px 4px 13px #0000002e;
  padding: 1rem;
  border-radius: 10px;
  background-color: var(--white700);
& .selectoption {
  border: 1px solid var(--black200);
  position: relative;
  border-radius: 5px;
  display: flex;
  &>i {
    font-size: 1.3rem;
    color: var(--theme-color);
    position: absolute;
    top: 50%;
    left: 0.7rem;
    transform: translateY(-50%);
    pointer-events: none;
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
    border-radius: 4px;
    flex-grow: 1;
  }
  & select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
  } 
}

& .search_packages {
    position: absolute;
    top: 100%;
    width: 100%;
    background-color: var(--white);
    box-shadow: -2px 8px 25px #00000042;
    z-index: 4;
    border-radius: 10px;
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
  background-color: var(--theme-color);
  padding: 0.5rem 1rem;
  color: var(--white);
  border-radius: 4px;
  height: 100%;
  cursor: pointer;
  transition: 0.5s;
  &:hover {
      background-color: var(--theme-color-dark);
  }
}

`;