import styled from 'vue3-styled-components';

export const HeaderWrapper = styled.header`
    padding: 1rem 0;
    position: fixed; top: 0; left: 0; width: 100%;
    transition: 0.5s;
    z-index:5;
    background-color: var(--white);
    border-bottom: 1px solid var(--black100);
    .home_page &{
        padding: 2.375rem 0;
        background-color: transparent;
        border-bottom-color: transparent;
    }
    & .header_inner {display: flex; align-items: center;}
    & .header_right { display: flex; align-items: center; flex-grow: 1; }
    & .theme-nav_wrap { margin-left: auto; margin-right: auto; }
    & .sub-menu { position: absolute; opacity: 0; pointer-events: none; transition: 0.5s; transform: translatey(5rem); background-color: var(--white); box-shadow: 4px 6px 12px #0000003b; border-radius: 8px; min-width: 100%; padding: 0.8rem 0; width: max-content;}
    & .has-dropdown{position:relative;}
    & .has-dropdown>a:after { content: "\f107"; font-family: "Font Awesome 6 Free"; font-weight: 900; margin-left: 0.4rem; transform: translate(1px, 2px); display: inline-block; transition: 0.5s;}
    & .has-dropdown:hover>a:after { transform: translate(1px, 2px) rotate(180deg); }
    & .has-dropdown:not(.unhover):hover .sub-menu { transform: translateY(0); opacity: 1; pointer-events:all;}
    & .theme-nav-li .sub-menu a { display: block; max-width: 18rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; font-size: 1.01rem; line-height: 1.7;}
    & .theme-nav-li .sub-menu a:hover {background-color: var(--theme-color100);}
    & .header-logo img { width: 100%; }
    & .header-logo { width: 12.625rem; }
    & .theme-nav { display: flex; }
    & .header_right>.btn{padding: 1rem 2.45rem; border-radius: 1.25rem;}
    & .theme-nav-li a { font-size: 1.125rem; font-weight: 300; padding: 0 1.375rem; }

    &.sml-header { padding: 1rem 0; box-shadow: 1px 4px 30px #00000033; background-color: var(--white); }
    &.sml-header .header_right>.btn {padding: 0.6rem 1.8rem; border-radius: 0.8rem; font-size: 1.15rem;}
    & .header_right>.btn:hover { background-color: var(--secondary-color); color: white;}
    & .header_right>.btn.mob_signin {
        display: none;
    }
    & .has-dropdown .toggler{display: none;}

    @media (max-width: 1250px){
    padding: 1.3rem 0;
    &.sml-header{
        padding: 0.9rem 0;
    }
    }
    @media (max-width: 992px){
        padding: 1.2rem 0;

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


        .home_page &{
            padding: 1.2rem 0;
            &.sml-header{
                padding: 0.9rem 0;
            }
        }
        &  .header_right>.btn{
            display: none;
            &.mob_signin{line-height: 1; font-size: 1.7rem; display: block; color: var(--black600); background-color: transparent; padding: 0.5rem; margin-right: 0.2rem;}
        }

        & .theme-nav_wrap { position: fixed; top: 0; right: 0; height: 100%; background-color: var(--white); z-index: 1; transition: 0.5s; transform: translateX(100%); padding-top: 4rem;}
        & .menu_backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: var(--black400); transition: 0.5s; opacity: 0; pointer-events: none; }
        
        & .theme-nav{ 
            flex-direction :column; overflow: auto; height: 100%; 
        }
        & .header_right{flex-grow : 0;margin-left: auto; flex-direction: row-reverse;}
        & .menu_toggle {
            padding: 0.7rem 0.5rem; display: flex; flex-direction: column; border-radius: 8px; position: relative; z-index: 1; transition : 0.5s;
                & span{
                    width: 24px; height: 2px; background: var(--black900); transition : 0.5s; transform-origin: right;
                    &:nth-child(2){margin: 5px 0;}
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
        
        & .has-dropdown{
            &.active .toggler i{
                transform: rotate(180deg);
            }
            &>a{
                padding-right: 3.6rem;
                &:after{font-size: 0;}
            }
            &>.sub-menu{
                position: static;
                transform: none;
                opacity: 1;
                box-shadow: none;
                background-color: var(--black40);
                border-radius: 0;
                pointer-events: all;
                display: none;
                transition: none;
            }
            & .toggler {
                display: grid;
                place-items: center;
                position: absolute;
                right: 1rem;
                top: 0.2rem;
                height: 1.8rem;
                border: 1px solid var(--black200);
                padding: 0 0.5rem;
                color: var(--black700);
                font-size: 0.85rem;
                & i{
                    pointer-events: none;
                    transition: all ease 0.5s;
                }
            }
        }
        & .theme-nav-li a {
            width: 100%;
            padding-top: 0.3rem;
            padding-bottom: 0.3rem;
        }

    }
    @media (min-width: 992px){
        & .sub-menu{
            display: block!important;;
        }
    }
`;
