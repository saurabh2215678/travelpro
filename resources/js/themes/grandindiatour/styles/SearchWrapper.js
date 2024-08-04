import styled from "vue3-styled-components";

export const SearchWrapper = styled.div`
& .passenger_wrap {
     top: auto; left: auto; right: auto; flex: initial; width: initial; overflow: initial; box-shadow: none; padding: 0; border-radius: initial; z-index: initial;
    width: 7.5rem;
    max-height: initial;
    display: flex;
    flex-direction: column;
    background-color: transparent;
    align-self: flex-start;
    &.ret_time{
        width: 8rem;
    }
}
& .passenger_wrap .passenger-economy .pickup-time{padding-left: 1rem;}

& .select_from_wrap, 
& .select_to_wrap{
    width: 10.797rem;
}
& .select_from_wrap{
    &.pickup_loc,
    &.dest_sel{
        width: initial;
        flex-grow: 1;
    }
}
& .passenger-economy{
    min-width: auto;
    width: auto;
    padding: 0.65rem 0.6rem;
}
body:not(.car-module) & .ssb-wrap:not(.round_trip){
    width: 8.5rem;
    margin-right: 0;
    padding: 0;
}
& .ssb-wrap .date_input{
    width: 8rem;
    border-radius: 5px;
}
& .select_from_wrap:first-child{
    margin-right: 0;
}
& .trip-type{
    margin-right: 0;
}
& .ui.selection.dropdown .menu>.item {
    padding: 0.6rem 0.8rem!important;
    white-space: nowrap;
    overflow: hidden;
}
& .flight_form .search.selection.dropdown .menu .item:before{
    margin-right: 0.35rem;
}
& .flight_form{
    margin: 0 -5px;
    &>*{
        padding: 0 5px!important;
        &>label{
            font-family: 'Poppins', sans-serif;
            color: #000;
            padding-left: 3px;
        }
    }
    & .err{
        font-size: 12px;
    }
}
&.book_flight_form {
    padding-bottom: 1.5rem;
}
& .returnCheckBox {
    position: absolute;
    padding-top: 0.5rem;
    & label {
        display: flex;
        align-items: center;
        font-weight: 500;
        color: #292929;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        & input[type="checkbox"]{
            margin-right: 5px;
            accent-color: #0aa0b1;
            width: 1rem;
            height: 1rem;
        }
        & span{
            font-family: 'Poppins', sans-serif;
        }
    }
}
& .trp_box .trip-type select {
    padding-top: 0;
    padding-left: 2rem;
}
& .search_btn {
    margin: 0;
    margin-top: 1.3rem;
}
&.book_flight_form form.flight_form >.trp_box:nth-child(1){
    margin-bottom: 0;
}
& .trp_box .trip-type{
    min-height: 2.43rem;
}
& .free_input {
    padding: 0.6rem 0.5rem;
    font-size: 0.84rem;
    border-radius: 5px;
    border: 1px solid #dcdcdc;
    padding-left: 2rem;
    background-color: #fff;
    background-position: 90% center;
}
& .city_select.select_from_wrap .search:before{
    content: "\f64f";
    pointer-events: none;
}
& .sel_station.select_from_wrap .search:before{
    content: "\f239";
    pointer-events: none;
}

& .pickup_loc:before,
& .dest_sel:before,
& .drop_loc:before {
    content: "\f3c5";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    align-self: center;
    margin-right: 0.5rem;
    opacity: 0.7;
    position: absolute;
    top: 1.78rem;
    left: 1rem;
    pointer-events: none;
}
& .trp_box .trip-type:before {
    content: "\f3c5";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    align-self: center;
    margin-right: 0.5rem;
    opacity: 0.88;
    position: absolute;
    top: 0.53rem;
    left: 0.75rem;
    pointer-events: none;
}
`;