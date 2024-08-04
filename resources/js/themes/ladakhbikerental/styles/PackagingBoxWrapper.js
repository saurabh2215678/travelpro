import styled from "vue3-styled-components";

export const PackagingBoxWrapper = styled.div`
/* border-radius: 12px; */
transition: .5s;
& .packaging_view_box_top{
  display: flex;
  & .images {
      width: 18.938rem;
      & a{
        width: 100%;
        height: 100%;
        & img{
          width: 100%;
          height: 22rem;
          object-fit: cover;
        }
      }
  }
  & .packaging_info{
    width: calc(100% - 18.938rem);
    padding: 0 0 0 2rem;
    max-width: 100%;
    & .package_info_wrap {
        display: flex;
        justify-content: space-between;
        & .title a {
            padding-top: 0.3rem;
            display: block;
            font-weight: 600;
            font-size: 1.2rem;
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
        & .package_top{
            width: 100%;
            .price_inner{
                border: 0;
                padding: 0;
                text-align: left;
                .price_package{
                    text-align: left;
                }
            }
        }
    }
  }
}
& .packaging_view_box_top .package_top .price_inner {
    .req_price {
    color: var(--theme-color);
    border: 1px solid var(--theme-color);
    display: inline-block;
    padding: .3rem 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    font-size: .8rem;
}

}
& .duration {
  font-size: .8rem;
  display: block;
  color: var(--theme-color);
  position: relative;
  border: none;
  text-transform: uppercase;
}

& .cities {
    font-size: 0.9rem;
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
    /* padding: 1.375rem;
    border: 1px solid var(--theme-color500);
    border-radius: 8px;
    text-align: center; */
    & .price_package {
        font-size: 0.9rem;
        color: var(--black600);
    }
    & .amount {
    font-size: 1.5rem;
    color: var(--theme-color);
    font-weight: 700;
    }
}
& .right_side {
    justify-content: center;
}
& .btn{
    padding: 0.4rem 1rem;
    border-radius: 5rem;
    color: white;
    border: 1px solid var(--theme-color);
    background-color: var(--theme-color);
}
& .btn.bg_theme:hover{
    background-color:var(--theme-color10);
    border: 1px solid var(--theme-color);
    color: var(--theme-color) !important;
}
& .bg_secondary_theme{
    background-color: var(--secondary-color10);
    border: 1px solid var(--secondary-color);
    color: var(--black);
    :hover{
        background: var(--secondary-color);
        color: #fff;
    }
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
& .packaging_view_footer_inner{
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 15px;
    background: #faf9f9;
    border-top: 1px solid #ddd;
    .left_side{
        float: left;
    }
    .inclusions{
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
        overflow: hidden;
        /*! height: 65px 
        overflow-x: scroll; */
    }
    li{
        text-align: center;
        width: 3.5rem;
        min-width: -moz-min-content;
        min-width: min-content;
        line-height: 12px;
        font-size: .7rem;
        padding: 0 5px /*! white-space: nowrap; */;
    }
    img{
        height: 2rem;
        -o-object-fit: cover;
        object-fit: cover;
        margin: 0 auto;
    }
    .right_side{
        float: right;
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
            height: auto;
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
        margin-top: 0rem;
        margin-bottom: 0rem;
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