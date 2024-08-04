import styled from "vue3-styled-components";

export const FlightSearchWrapper = styled.div`
& .tabs {
    border-radius: 0.3rem;
    display: flex;
    justify-content: flex-start;
    margin-right: auto;
    margin-bottom: 0.5rem;
    margin-top: 0.5rem;
    overflow: hidden;
    width: fit-content;
    & .tab-btn{
        background-color: var(--black600);
        color: var(--white);
        font-size: .8rem;
        padding: 0.6rem 1.3rem;
        transition: all .5s ease;
        &:hover{
            background-color: var(--white600);
            color: var(--black800);
        }
        &.active{
            background-color: var(--theme-color);
            color: var(--white);
        }
    }
}

& .flight_form{
    display: flex;
    font-weight: 500;
    flex-wrap: wrap;
    gap: 10px 0;
}
& .select_from_wrap, 
& .select_to_wrap{
    cursor: pointer;
    display: flex;
    position: relative;
    flex-direction: column;
    width: 13rem;
    & .selection:before{
        content: "";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        margin-right: 0.5rem;
        opacity: .7;
    }
    & .ui.search.selection.dropdown>input.search{
        padding-left: 2.5rem;
    }
}
& .select_from_wrap .selection:before{
    content: "\f5b0";
}
& .select_to_wrap .selection:before{
    content: "\f5af";
}
& .select_from_wrap:first-child{
    margin-right: 0.5rem;
}
& .swap-icon {
    align-self: center;
    background-color: var(--black);
    border-radius: 50%;
    display: grid;
    font-size: .7rem;
    height: 1.6rem;
    margin-left: -0.98rem;
    margin-right: -0.55rem;
    overflow: hidden;
    place-items: center;
    position: relative;
    width: 1.6rem;
    z-index: 1;
    & .swap_btn {
        color: var(--white);
        height: 100%;
        width: 100%;
    }
}
& .passenger_pop{
    position: absolute;
    background-color: var(--white);
    border-radius: 0.5rem;
    box-shadow: 0 0 15px 0 #00000040;
    color: var(--black);
    opacity: 0;
    overflow: auto;
    padding: 1rem 0;
    pointer-events: none;
    top: 15rem;
    right: 0;
    transition: all .5s ease;
    display: flex;
    white-space: nowrap;
    z-index: 1;
}
& .ssb-wrap {
    display: flex;
    flex-wrap: wrap;
    padding: 0 0.5rem;
    &:not(.round_trip){
        flex-direction: column;
    }
    &>.date_wrap {
        flex-grow: 1;
        position: relative;
        & .date_input{
            color: var(--black600);
            border: 1px solid rgba(34,36,38,.15);
            cursor: pointer;
            font-size: .8rem;
            height: 100%;
            padding: 0 1rem 0 2.5rem;
            width: 9rem;
            &:focus{
                outline: none;
            }
        }
        &:before{
            color: var(--black500);
            content: "\f133";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            left: 1rem;
            pointer-events: none;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
    }
    & div:first-child .date_input{
        border-radius: 5px;
    }
    &.round_trip{
        width: 19rem;
        & div:first-child .date_input{
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: 0;
        }
        &  div:nth-child(2) .date_input{
            border-bottom-right-radius: 5px;
            border-top-right-radius: 5px;
        }
    }
}
& .passenger_wrap{
    position: relative;
    cursor: pointer;
    & .passenger-economy {
        display: flex;
        align-items: center;
        border: 1px solid rgba(34,36,38,.15);
        padding: 0.5rem 0.6rem;
        background-color: var(--white);
        border-radius: 0.36rem;
        color: var(--black600);
        overflow: hidden;
        min-width: 10rem;
        position: relative;
        & i{
            font-size: 1.2rem;
            margin-right: 0.8rem;
        }
        & .passenger-txt{
            margin: -0.5rem 0;
            & .passenger-label {
                font-size: .86rem;
                line-height: 1;
                opacity: .8;
            }
            & input {
                pointer-events: none;
                font-size: .8rem;
            }
        }
    }
}
& .search_btn {
    margin-left: 0.5rem;
    & button {
        background-color: var(--theme-color);
        border-radius: 0.36rem;
        color: var(--white);
        font-size: .8rem;
        height: 100%;
        padding: 0 1.2rem;
        &:hover{
            background: var(--theme-color-dark700);
            color: var(--white);
        }
    }
}
& .form_items_wrap{
    width: 100%;
    & .form_item{
        display: flex;
        &:not(:first-child){
            margin-top: 0.8rem;
            & .search_btn button:first-child{
                padding: 0;
                margin-right: 0.7rem;
                background-color: transparent;
                height: auto;
            }
        }
    }
}
& .item[data-country-name]:after {
    content: attr(data-country-name);
    display: block;
    margin-top: 0.2rem;
    opacity: .6;
}
& .err {
    font-size: 0.8rem;
    color: red;
    font-weight: 400;
}
`;