import styled from "vue3-styled-components";

export const BreadcrumbWrapper = styled.ul`
    display: inline-flex;margin-left:.5rem;
    & li{
        display: flex;
        align-items: center;
        & a,
        & span{
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            line-clamp: 1;
            -webkit-box-orient: vertical;
        }
        & span{
            word-break: break-all;
        }
        &:after {
            content: "";
            width: 1px;
            height: 10px;
            background-color: currentColor;
            display: inline-block;
            margin: 0 0.6rem;
            transform: rotate(20deg);
            opacity: 0.5;
        }
        &:last-child:after{
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }
    }
`;