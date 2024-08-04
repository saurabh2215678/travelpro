import styled from 'vue3-styled-components';

export const HeaderWrapper = styled.header`
    padding: 0;
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%;
    transition: 0.5s;
    z-index:5;
    /* background-color: var(--white); */
    /* border-bottom: 1px solid var(--black100); */
    padding: 0 4rem;
    background: linear-gradient(to bottom, rgb(255 255 255 / 64%) 0%, rgb(255 255 255 / 64%) 51%, rgba(0, 0, 0, 0) 100%);
    & .header_inner {display: flex; align-items: center; }
    & .header_right {height:0; display: flex; align-items: center; min-width: 20rem; justify-content: flex-end; align-self: flex-start; padding-top: 0.5rem;}
    & .theme-nav_wrap { margin-left: auto; margin-right: auto; }
    & .sub-menu { position: absolute; opacity: 0; pointer-events: none; right:0;transition: 0.5s; transform: translatey(5rem); background-color: var(--white); box-shadow: 4px 6px 12px #0000003b; /*border-radius: 8px;*/ min-width: 100%; padding: 0rem; width: max-content;}
    & .has-dropdown{position:relative;}
    & .has-dropdown>a:after { content: "\f107"; font-family: "Font Awesome 6 Free"; font-weight: 900; margin-left: 0.4rem; transform: translate(1px, 2px); display: inline-block; transition: 0.5s;}
    & .has-dropdown:hover>a:after { transform: translate(1px, 2px) rotate(180deg); }
    & .has-dropdown:not(.unhover):hover>.sub-menu { transform: translateY(0); opacity: 1; pointer-events:all;}
    & .theme-nav-li .sub-menu a { display: block; /*max-width: 20rem;*/ overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; font-size: 0.9rem; padding:0.5rem 1rem;}
    & .theme-nav-li .sub-menu a:hover {background-color: var(--theme-color100);}
    & .header-logo img { width: 100%; }
    & .header-logo { width: 12.2rem;}
    & .header_top,
    & .header_menus {transition: 0.5s;}
    /* &.sml-header {
        & .header-logo { width: 5rem; }
        & .header_menus {transform: translateY(-2.2rem);}
        & .header_top { opacity: 0; pointer-events:none;}
    } */
    & .search_box.active {
    position: relative;
}
& .search_formbtn .searchbtn:hover {
    color: var(--theme-color);
}
& .search_formbtn {
    padding-left: 1rem;
}
    & .search_btn{padding: 0.85rem 1rem 0.6rem;display:flex;align-items:center;}
    & .header_bottom {
        transition: 0.5s;
        align-items:center;
    }
    & .header_left {
        display: flex;
        align-items: center;
        padding: 0.3rem 0;
        min-width: 20rem;
    }
    &.sml-header {
        background-color: var(--white);
        box-shadow: -1px 3px 15px #0003;
        & .header_bottom {
            padding-bottom: 0;
        }
        & .header-logo {
            /* width: 6rem; */
        }
    }
    & .theme-nav { display: flex; justify-content: flex-end;
        &>.theme-nav-li>a{
            padding: 0.85rem 1rem 0.6rem;
            color: var(--black800)!important;
            font-weight: 500;
            /* font-family: 'SF-Pro-Display'; */
            font-size: 1rem;
        }
    }
    & .header_right>.btn{padding: 1rem 2.45rem; border-radius: 1.25rem;}
    & .theme-nav-li > a {
        font-size: 1.08rem; 
        padding: 0.5rem 1rem;
        position: relative;
        ::before{
            content: "";
            display: block;
            position: absolute;
            left: 50%;
            right: 50%;
            bottom: 0;
            height: 2px;
            background: var(--theme-color600);
            transition: 0.3s;
        }
     }
     & .theme-nav-li:hover >a::before {
            left: 0;
            right: 0;
        }
& .header_middle {
    flex-grow: 1;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    flex-direction: column;
}
& .header_top {
    max-width: 45.625rem;
    width: 100%;
}
& .goog-te-gadget{font-size:0!important;}
& .goog-te-gadget span{display:none;}

@media (min-width: 992px){
    & .sub-menu .theme-nav-li>a+ul {
        position: absolute;
        right: 100%;
        top: 100%;
        width: 100%;
        display: none;
        border-bottom: 0px solid #f2f2f2;
        border-radius: 0 0 3px 3px;
        top: 0;
        background-color: var(--white);
        padding: 0;
        width: -webkit-max-content;
        width: -moz-max-content;
        width: max-content;
    }
    & .sub-menu .theme-nav-li:hover>a+ul {
        display: block;
    }
}

& .header_right>.btn:hover { background-color: var(--secondary-color); color: white;}
& .has-dropdown .toggler{display: none;}
& .header_menus {
    color: var(--black800);
    display: flex;
    align-items: center;
    }
& .contact_info { 
    display: flex;
    align-items: center;
    justify-content: flex-end;
    line-height: 1;
    border-bottom: 1px solid var(--black80);
    &>li{
        padding-left: 1.4rem;
        font-size: 1.1rem;
    }
}
& .top_phone{
    & a{
        display: flex;
        align-items: center;
        font-size: 1rem;
        font-weight: 500;
        & .svg_box{
            width: 1.3rem;
            margin-right: 0.3rem;
        }
    }
}

& .top_email a {
    position: relative;
    &:before{
        content: "\f0e0";
        font-family: "Font Awesome 6 Free";
        font-weight: 400;
        font-size: 1rem;
        margin-right: 0.4rem;
        color: var(--orange);
    }
}
& .last_options {
    &>a:first-child {
        font-weight: 500;
        display: flex;
        white-space: nowrap;
        align-items: center;
        margin-left: 1rem;
        & svg{
            width: 1.7rem;
            margin-right: 0.6rem;
        }
        & i{
            margin-left: 0.6rem;
        }
    }
}
& .mob_signin {
    display: none;
}
& .search_btn{
    /* display: none; */
    font-size: 1.3rem;
    color: #737373;
}
& .search_close_btn{
    display: none;
    position: absolute;
    top: -1rem;
    right: -1rem;
    background-color: var(--theme-color);
    width: 2.5rem;
    height: 2.5rem;
    place-items: center;
    color: var(--white);
    border-radius: 50%;
}
@media (max-width: 1650px){
    & .container-fluid{
        padding: 0 3rem;
    }
}
@media (max-width: 1550px){
    & .container-fluid{
        padding: 0 2.65rem;
    }
}
@media (max-width: 1350px){
    & .container-fluid{
        padding: 0 1.3rem;
    }
}

@media (max-width: 1100px){
    & .header_top {
        position: fixed;
        top: 50%;
        left: 50%;
        background-color: var(--white);
        max-width: calc(100% - 3rem);
        padding: 1.5rem;
        border-radius: 8px;
        transform: translate(-50%, -10rem);
        opacity: 0;
        pointer-events: none;
    }
    & .header_inner{
        padding: 0;
    }
    & .top_phone a{
        font-size: 0px;
        & .svg_box{
            margin-right: 0;
        }
    }
    & .search_btn{
        display: block;
    }
    & .header_menus{
        & .search_btn,
        & .top_phone,
        & .last_options{
            margin-left: 1rem;
        }
    }
    /* & .header_left,
    & .header_right{min-width: 15rem;} */
  & .header_left, & .header_right {
    min-width: auto;
    width: 100%;
    height: auto;
}
    & .last_options>a:first-child{
        font-size: 0px;
        margin-left: 0;
        & svg{
            margin-right: 0;
        }
        & i{
            display: none;
        }
    }
    & .search_backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--black400);
        -webkit-transition: 0.5s;
        transition: 0.5s;
        opacity: 0;
        pointer-events: none;
    }
    .search_opened & {
        & .header_top{
            transform: translate(-50%, -50%);
            opacity: 1;
            pointer-events: all;
        }
        & .search_backdrop{
            opacity: 1;
            pointer-events: all;
        }
    }
    & .search_close_btn{
        display: grid;
    }
    & .selectoption:first-child{
        z-index: 9999;
    }
}

@media (max-width: 992px){
    padding: 0;
    z-index: 9999;

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
    
    &.mob_signin{line-height: 1; font-size: 1.7rem; display: block; color: var(--black600); background-color: transparent; padding: 0.5rem; margin-right: 0.2rem;}

    & .theme-nav_wrap { position: fixed; top: 0; right: 0; height: 100%; background-color: var(--white); z-index: 1; transition: 0.5s; transform: translateX(100%); padding-top: 4rem; padding-bottom: 4rem; min-width: 20rem;}
    & .menu_backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: var(--black400); transition: 0.5s; opacity: 0; pointer-events: none; }
    
    & .theme-nav{ 
        flex-direction :column; overflow: auto; height: 100%; justify-content: flex-start; margin-top: 2rem;
        li{
            border-top: 1px solid #9e9e9e42;
        }
    }
    & .menu_toggle {
        padding: 0.7rem 0.5rem; display: flex; flex-direction: column; border-radius: 8px; position: relative; z-index: 2;     margin-left: 0.6rem; transition : 0.5s;
            & span{
                width: 24px; height: 2px; background: var(--black900); transition : 0.5s; transform-origin: right;
                &:nth-child(2){margin: 5px 0;}
            }
        }
    & .menu_toggle:hover {
    background: var(--theme-color);
    span {
    background: #fff;
}
}
    & .contact_info{
        border-bottom: 0;
        &>li{
            display: none;
            &.last_options{
                display: block;
                & a{
                    display: none;
                    &.mob_signin{
                        display: block;
                        padding: 0 0.5rem;
                        color: var(--black);
                        background: transparent;
                        border: none;
                        font-size: 1.2rem;
                    }
                }
                & .user_login a{
                    display: block;
                }
            }
        }
    }
    & .header_bottom{
        padding: 0;
        margin-right: 0.5rem;
    }
    & .search_formbtn .searchbtn {
        width: 30px;
        text-align: center;
        background: var(--theme-color);
        height: 30px;
        line-height: 30px;
        color: #fff;
        border-radius: 3px;
        :hover {
         background: var(--theme-color100);
}
}
    .sidemenu_active & {
        
        & .menu_backdrop{
            opacity: 1;
            pointer-events: all;
        }
        & .theme-nav_wrap{
            transform: translateX(0%);
        }
        & .menu_toggle{
            transform: scale(0.8);
            cursor: pointer;
            & span:nth-child(1){
                transform: rotate(-45deg) translate(2px, 0);
            }
            & span:nth-child(2){
                opacity: 0;
            }
            & span:nth-child(3){
                transform: rotate(45deg) translate(2px, 0);
            }
        }
        &:not(.sml-header) .menu_toggle{
            transform: translateY(-0.5rem) scale(1);
            padding-left: 2px;
            padding-right: 10px;
        }
        
    }
    & .theme-nav-li{
        & .sub-menu a{
            padding: 0rem 1rem;
            margin: 0.52rem 0;
            max-width: 15.5rem;
            line-height: 1.9;
            font-size: 15px;
        }
        & > a{
            &::before {
                opacity: 0;
            }
        }
    }
    & .has-dropdown{
        &.active>.toggler i{
            transform: rotate(180deg);
        }
        &>a{
            padding-right: 3.6rem;
            &:after{font-size: 0;}
        }
        & .sub-menu{
            position: static;
            transform: none;
            opacity: 1;
            box-shadow: none;
            background-color: var(--black40);
            border-radius: 0;
            pointer-events: all;
            display: none;
            transition: none;
            padding: 1px 0;
        }
        & .toggler {
            display: grid;
            place-items: center;
            position: absolute;
            right: 1rem;
            top: 1rem;
            height: 1.8rem;
            /* border: 1px solid var(--black200); */
            padding: 0 0.5rem;
            color: var(--black700);
            font-size: 16px;
            cursor: pointer;
            & i{
                pointer-events: none;
                transition: all ease 0.5s;
            }
        }
    }
    & .theme-nav>.theme-nav-li>a {
        width: 100%;
        font-size: 16px;
        padding: 0.8rem 1rem 0.8rem;
    }

    & .header_menus{margin-left: 0; width:100%;justify-content: flex-end; cursor: pointer;}

& .theme-nav-li .sub-menu ul li a {
    font-size: 13px;
}


}
@media (min-width: 992px){
    & .sub-menu{
        display: block!important;;
    }
}
@media (max-width: 768px){
    & .header_left, 
    & .header_right {
        /* min-width: initial; */
        height: auto;
    }
    & .container-fluid {
        padding: 0 0.5rem;
    }
   & .search_formbtn {
    padding-left: 1rem;
}
}
/* .has-dropdown .has-dropdown .toggler{top: -2px;} */

`;