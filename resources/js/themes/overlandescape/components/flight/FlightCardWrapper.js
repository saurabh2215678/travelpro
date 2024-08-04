import styled from "vue3-styled-components";

export const FlightCardWrapper = styled.tr`
.flight_table & td {
        border:none;
        background-color: #0000;
        color: initial;
    } 
& .expendelar_btn{display:none;}
& .last_td{display:none;}
@media (max-width: 768px){
    & .last_td{display:block;}
    & {
        display: flex;
        flex-wrap: wrap;
        height: initial!important;
        margin-bottom: 1rem;
        background-color: #fff;
        box-shadow: 0px 2px 3px #00000033;
        border-radius: 8px;
        position: relative!important;
        &>td{
            display: block;
            &:first-child{
                padding-left: 0.5rem!important;
                width: calc(100% - 13rem)!important;
            }
            &:nth-last-child(2){
                width: 100%;
                margin: 0!important;
                display: grid!important;
                grid-template-rows: 0fr;
                overflow: hidden;
                transition: all ease 0.3s;
                padding: 0;
               
            }
            & .flight_actions{
                display:none;
            }
            &:last-child{display:none;}
        }
        /* & td:nth-child(5){
            width: 100%;
        } */
    }
    & .flight_detail:before{display:none; }
    &.active>td:nth-last-child(2){
        grid-template-rows: 1fr;
        padding: 0.2rem;
    }
    & .assd_price_list {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        min-height: 0;
        & li{
            margin: 0.2rem!important;
            padding: 0.5rem!important;
            border: 1px solid #dfdfdf;
            flex-grow: 1;
            position: relative;
            z-index: 99999;
        }
    }

    & .expendelar_btn{
        display:block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--box-height-pos);
        max-height: 5rem;
    }
    & .mob_price_chk_btn {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    & .prc_chk_top input {
        display: none;
    }
    .splitTable &>td:first-child{width: calc(100% - 14rem)!important;}
}
`;