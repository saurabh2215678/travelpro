import styled from "vue3-styled-components";

export const PackagingBoxWrapper = styled.div`
border: solid 1px #e1e1e1;
border-radius: 12px;
transition: .5s;
margin-top: 1.188rem;
& .packaging_view_box_top{
  display: flex;
  & .images {
      width: 13.938rem;
      & a{
        width: 100%;
        height: 100%;
        & img{
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
      }
  }
  & .packaging_info{
    width: calc(100% - 13.938rem);
    padding-left: 1rem;
    & .package_info_wrap {
        display: flex;
        justify-content: space-between;
        & .title a {
            padding-top: 0.3rem;
            display: block;
            font-weight: 600;
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
        }
    }
  }
}

& .duration {
  font-size: .738rem;
  display: block;
  color: var(--secondary-color);
  position: relative;
  border: none;
  text-transform: capitalize;
}

& .cities {
    font-size: 0.8rem;
    & i {
    margin-right: 4px;
    }
}
& .package_type {
    display: flex;
    flex-wrap: wrap;
    font-size: 0.8rem;
    & .customradio {
        display: flex;
    }
}
& .package_top{
    width: calc(100% - 13rem);
}
& .price {
    width: 13rem;
}
& .price_inner {
    padding: 1.375rem;
    border: 1px solid var(--theme-color500);
    border-radius: 8px;
    text-align: center;
    & .price_package {
        font-size: 0.9rem;
        color: var(--black600);
    }
    & .amount {
    font-size: 1.5rem;
    color: var(--secondary-color);
    font-weight: 700;
    }
}
& .right_side {
    justify-content: center;
}
& .btn{
    padding: 0.3rem 0.5rem;
    border-radius: 5px;
    color: white;
}
& .bg_secondary_theme{
    background-color: var(--secondary-color);
}
& .activ_list li {
    border: 1px solid #c8c8c8;
    color: #3e3e3e;
    list-style: none;
    display: inline-block;
    padding: 3px 15px 4px;
    border-radius: 15rem;
    font-size: 0.813rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    & br {
        display: none;
    }
}
@media (max-width: 850px) and (min-width: 769px){
  & .price{
    width: 11rem;
  }
  & .package_top{
    width: calc(100% - 11rem);
  }
  & .btn{
    font-size: 0.78rem;
    &.mr-3{
        margin-right: 0.4rem;
    }
  }
}
@media (max-width: 768px){
    & .packaging_view_box_top{
        flex-direction: column;
        & .images{
            width: 100%;
            display: flex;
            height: 11rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
        }
        & .packaging_info{
            width: 100%;
            padding: 0;
            & .package_info_wrap{
                flex-direction: column;
            }
        }
    }
    & .package_top,
    & .price{
        width: 100%;
    }
    & .package_disc{
        margin-top: 0.8rem;
        margin-bottom: 0.8rem;
    }
    & .right_side{
        justify-content: space-between;
    }
    & .btn{
        flex-grow: 1;
        text-align: center;
        &.mr-3{
            margin-right: 0.4rem;
        }
    }
}
`