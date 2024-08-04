import styled from "vue3-styled-components";

export const FooterWrapper = styled.footer`
    color: var(--white);
    padding: 4.5rem 0;
    background: #0077c1 url(../images/footerbg-new.jpg) center center no-repeat;
    background-size: cover;
    padding: 3.125rem 0 0;
    position: relative;
    & p {
        color: inherit;
    }
    & .footer_bottom_inner {
        display: flex;
    }
    & .sec_first {
        padding-top: 1.3rem;
    }
    & .sec_second,
    & .sec_third {
        & .theme-nav {
            display: flex;
            & > li {
                flex-grow: 1;
                padding: 0 1.5rem;
            }
        }
    }
    & .social {
        display: flex;
        align-items: center;
    }
    & .footer_logo {
        margin-bottom: 1.4rem;
    }
    & .ftr_breif {
        font-size: 0.938rem;
        max-width: 18.375rem;
        margin-bottom: 1.25rem;
        line-height: 1.8;
    }
    & .social a {
        display: flex;
        margin-right: 1rem;
        & i {
            border-radius: 5rem;
            width: 2rem;
            height: 2rem;
            text-align: center;
            line-height: 2;
        }
        & .fa-facebook {
            background: #6081c4;
            :hover {
                background: var(--white);
                color: #6081c4;
            }
        }
        & .fa-twitter {
            background: #43bdf0;
            :hover {
                background: var(--white);
                color: #43bdf0;
            }
        }
        & .fa-instagram {
            background: radial-gradient(
                    circle farthest-corner at 35% 90%,
                    #fec564,
                    transparent 50%
                ),
                radial-gradient(
                    circle farthest-corner at 0 140%,
                    #fec564,
                    transparent 50%
                ),
                radial-gradient(
                    ellipse farthest-corner at 0 -25%,
                    #5258cf,
                    transparent 50%
                ),
                radial-gradient(
                    ellipse farthest-corner at 20% -50%,
                    #5258cf,
                    transparent 50%
                ),
                radial-gradient(
                    ellipse farthest-corner at 100% 0,
                    #893dc2,
                    transparent 50%
                ),
                radial-gradient(
                    ellipse farthest-corner at 60% -20%,
                    #893dc2,
                    transparent 50%
                ),
                radial-gradient(
                    ellipse farthest-corner at 100% 100%,
                    #d9317a,
                    transparent
                ),
                linear-gradient(
                    #6559ca,
                    #bc318f 30%,
                    #e33f5f 50%,
                    #f77638 70%,
                    #fec66d 100%
                );
            :hover {
                background: var(--white);
                color: #f09433;
            }
        }
        & .fa-linkedin {
            background: #2697cf;
            :hover {
                background: var(--white);
                color: #2697cf;
            }
        }
    }
    & .theme-nav > li {
        & .sub-menu a {
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 0.4rem;
            &:hover {
                color: var(--secondary-color);
            }
        }
    }
    & .sec_third h4,
    & .sec_forth h4,
    & .theme-nav > li > a {
        font-size: 1.5rem;
        font-weight: 500;
        margin-bottom: 0.4rem;
        &:hover {
            color: var(--white);
        }
    }
    & .footer_contact li {
        margin-bottom: 0.5rem;
        &:last-child {
            margin-bottom: 0;
        }
        & a {
            display: inline-block;
            width: 100%;
        }
        & a:hover {
            color: var(--secondary-color);
        }
        & p {
            align-self: center;
            & a:hover {
                color: var(--secondary-color);
            }
        }
        & img {
            width: 2.375rem;
            margin-right: 0.85rem;
        }
    }
    & .top_phone a:first-child {
        position: relative;
        padding-left: 0rem;
    }
    & .top_phone a {
        padding-left: 1.3rem;
    }
    & .top_phone a:first-child:before {
        content: "\f095";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 0.85rem;
        margin-right: 0.4rem;
    }
    & .footer_accept {
        text-align: center;
        width: 100%;
        /* float: left; */
        padding: 2.25rem 0 1.25rem;
        img {
            display: inline-block;
            max-height: 60px;
        }
    }
    & #form_call_back_request .form-floating {
        padding-left: 0;
        padding-right: 0;
    }
    & #form_call_back_request .btn-book-space {
        margin-top: 0;
        margin-bottom: 1rem;
    }
    & #form_call_back_request .btn-book-space .btnSubmit {
        background: var(--orange);
        padding: 0.5rem 1.2rem;
        width: 100%;
        margin: 0;
        :hover {
            background: var(--theme-color);
        }
    }
    & .whatsapp-box {
        position: fixed;
        z-index: 10;
        color: #fff;
        font-size: 34px;
        bottom: 15px;
        left: 15px;
        width: 50px;
        height: 50px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background-color: #4dc247;
        box-shadow: rgb(0 0 0 / 40%) 2px 2px 6px;
    }

    & .footerfix {
        width: 66.66%;
        float: right;
        position: fixed;
        right: 0;
        bottom: 0;
        display: none;
        z-index: 999;
    }
    & .footerfix ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    & .footerfix ul li {
        width: 50%;
        float: left;
    }
    & .callfix {
        background: #0077c1;
    }
    & .footerfix ul li a {
        display: block;
        height: 44px;
        text-align: center;
        color: #fff;
        font-size: 16px;
        line-height: 44px;
    }
    & .mailfix {
        background: #f26122;
    }

    @media (max-width: 767px) {
        padding: 2rem 0;
        & .footer_bottom_inner {
            flex-direction: column;
        }
        & .sec_first,
        & .sec_second,
        & .sec_third,
        & .sec_forth {
            width: 100%;
        }
        & .sec_first {
            margin-bottom: 2rem;
        }
        & .sec_second .theme-nav {
            flex-wrap: wrap;
            & > li {
                padding: 0;
                margin-bottom: 1.45rem;
            }
        }
        & .sec_forth h4,
        & .theme-nav > li > a {
            margin-bottom: 1rem;
        }
        & .footer_contact li p {
            font-size: 1.1rem;
            line-height: 1.5;
        }
        & .whatsapp-box {
            border-radius: 0;
            width: 33.33%;
            position: fixed;
            bottom: 0;
            left: 0;
            height: 44px;
            font-size: 28px;
        }
        & .footerfix {
            display: block;
        }


        & form {
            display: flex;
            flex-direction: column;
            & .submit_btn {
                position: absolute;
                right: 13px;
                bottom: 0;
                height: 4.1rem;
                border-radius: 0;
                background-color: var(--theme-color);
            }
        }
    }
`;
