import styled from 'vue3-styled-components';

export const HeaderWrapper = styled.header`
    padding: 0;
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%;
    transition: 0.5s;
    z-index:5;
    /* background-color: var(--white800); */

    & .header_inner {display: flex; align-items: center;}
    & .header_right { display: flex; align-items: center; flex-grow: 1; }
    & .theme-nav_wrap { margin-right: 2rem; }
    & .sub-menu { position: absolute; opacity: 0; pointer-events: none; right:0;transition: 0.5s; transform: translatey(5rem); background-color: var(--white); box-shadow: 4px 6px 12px #0000003b; /*border-radius: 8px;*/ min-width: 100%; padding: 0rem; width: max-content;}
    & .has-dropdown{position:relative;}
    & .has-dropdown:hover>a:after { transform: translate(1px, 2px) rotate(180deg); }
    & .has-dropdown:not(.unhover):hover .sub-menu { transform: translateY(0); opacity: 1; pointer-events:all;}
    & .theme-nav-li .sub-menu a { display: block; /*max-width: 20rem;*/ overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; font-size: 0.9rem; padding:0.5rem 1rem;}
    & .theme-nav-li .sub-menu a *{pointer-events: none;}
    & .theme-nav-li .sub-menu a:hover {background-color: var(--theme-color100);}
    & .header-logo img { width: 100%; }
    & .header-logo { width: 20.875rem;}
    & .header_top,
    & .header_menus {transition: 0.5s;}
    /* &.sml-header {
        & .header-logo { width: 5rem; }
        & .header_menus {transform: translateY(-2.2rem);}
        & .header_top { opacity: 0; pointer-events:none;}
    } */
    & .header_bottom {
        padding-bottom: 0;
        transition: 0.5s;
        display: flex;
        flex-wrap: wrap;
    }
    & .header_left {
        display: flex;
        align-items: center;
        padding: 0.3rem 0;
    }
    &.sml-header {
        background-color: var(--white900);
        box-shadow: -1px 3px 15px #0003;
        & .header_bottom {
            padding-bottom: 0;
        }
        & .header-logo {
            width: 10rem;
        }
    }
    & .theme-nav { display: flex; justify-content: flex-end;
        &>.theme-nav-li>a{
            padding: 0rem 0.7rem;
            font-size: 15px;
            font-family: 'Jost', sans-serif;;
            color: rgb(255, 255, 255);
            font-weight: 400;
            *{
                pointer-events: none;
            }
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
            background: #ffd800;
            transition: 0.3s;
        }
     }
     & .theme-nav-li:hover >a::before {
            left: 0;
            right: 0;
        }
& .sub-menu .theme-nav-li>a+ul {
    position: absolute;
    left: 100%;
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

& .header_right>.btn:hover { background-color: var(--secondary-color); color: white;}
& .has-dropdown .toggler{display: none;}
& .header_menus {
    margin-left: 3rem;
    color: var(--black);
    flex-grow: 1;
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
        &.top_phone{
            font-size: 0.95rem;
            padding-top: 0.2rem;
            position: relative;
            &:first-child:before{
                content: "\f095";
                font-family: "Font Awesome 6 Free";
                font-weight: 900;
                position: absolute; 
                left: 0; 
                color: var(--orange);
            }
            &:not(:first-child){
                padding-left: 0;
            }
        }
    }
}
& .top_phone a:after{
    content: "/";
    margin: 0 2px;
    margin-left: 3px;
    display: inline-block;
}
& .top_phone:nth-child(2) a:after{
    content: "";
    position: absolute;
    opacity: 0;
    pointer-events: none;
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
    & a {
        background-color: var(--theme-color);
        padding: 0.5rem 1rem;
        border-radius: 0;
        border: 1px solid var(--theme-color);
        color: var(--white);
        &:not(.mob_signin){
            padding: 0.8rem 1.8rem;
            font-weight: 400;
            font-size: 0.91rem;
        }
        &:hover{
            background-color: var(--theme-color-dark);
            border-color: var(--white);
        }
    }
}
& .mob_signin {
    display: none;
}




/* Rakshit */



&.sml-header .theme-nav>.theme-nav-li>a{color:#000;}
& .contact_us li span p{font-size: 18px;font-family: 'Jost',sans-serif;color: rgb(255,255,255);font-weight: 600;}
& .contact_us li span i{color: rgb(255,255,255);font-size: 1.5rem;}
& .social{
   display:flex;flex-wrap:wrap;margin-left:2rem;  
   li a{width:30px;height:30px;background-color:#000;color:#fff;margin-right:.5rem;border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;}
}
&.sml-header .contact_us li span p{color:#000;}
&.sml-header .contact_us li span i{color:#000;}




@media (max-width: 1280px){
    & .theme-nav { display: flex; justify-content: flex-end;
        &>.theme-nav-li>a{
            padding: 0rem 0.5rem;
            font-size: 14px;
        }
    }
    & .contact_us li span p{font-size: 14px;}
}

@media (max-width: 1250px){
    padding: 1.3rem 0;
    &.sml-header{
        padding: 0.9rem 0;
    }
}
@media (max-width: 992px){
    padding: 0;
    z-index: 999;

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
        padding: 0.7rem 0.5rem;background: var(--theme-color); display: flex; flex-direction: column; border-radius: 8px; position: relative; z-index: 2;     margin-left: 0.6rem; transition : 0.5s;
            & span{
                width: 24px; height: 2px; background: var(--white); transition : 0.5s; transform-origin: right;
                &:nth-child(2){margin: 5px 0;}
            }
        }
    & .menu_toggle:hover {
    background: var(--theme-color);
    span {
    background: #fff;
}
}

& .theme-nav_wrap { margin-right: 0; }
    & .theme-nav>.theme-nav-li>a {color:#000;font-size:16px;font-weight:500;}
    & .header_inner{padding-left:.5rem;padding-right:.5rem;}
    & .header-logo{width: 12.8rem;}
    & .social{display:none;}
    & .cont{display:none;}
    & .theme-nav_wrap{margin-top:0!important;}

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
            transform: translateY(-0.6rem) scale(0.8);
        }
        
    }
    & .theme-nav-li{
        & .sub-menu a{
            padding: 0rem 1rem;
            margin: 0.52rem 0;
        }
        & > a{
            &::before {
                opacity: 0;
            }
        }
    }
  
    

    & .header_menus{margin-left: 0;}

    &.sml-header {
        padding: 0;
        & .header-logo{
            width: 9rem;
        }
    }
    & .header-logo{
        width: 4.8rem;
    }
    & .header_left{
        padding: 0;
        padding-bottom: 0.2rem;
    }


    & .header_inner {
        justify-content: space-between;
    }
    & .header_right {
    flex-grow: inherit;
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



@media (max-width: 767px){
    & .theme-nav_wrap { margin-right: 0; }
    & .theme-nav>.theme-nav-li>a {color:#000;font-size:16px;font-weight:500;}
    & .header_inner{padding-left:.5rem;padding-right:.5rem;}
    & .header-logo{width: 12.8rem;}
    & .social{display:none;}
    & .cont{display:none;}
    & .theme-nav_wrap{margin-top:0!important;}
}
`;
