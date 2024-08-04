import styled from "vue3-styled-components";

export const FlightResWrapper = styled.section`
.car-module & .flight_res_wrap .flight_res_right{
    padding-left: 1rem;
    padding-right: 1rem;
    width: calc(100% - 16rem);
} 
.car-module & .flight_res_wrap .flight_res_right .flight_table thead th{
    color: inherit;
    cursor: pointer;
}
.car-module & .flight_res_wrap .flight_res_right .flight_table thead th:hover{
    color:var(--theme-color);
}
.car-module & .flight_res_wrap .flight_res_right .flight_table.search_table_inr .btn:hover{
    color: var(--white) !important;
}
@media (max-width: 768px){
    & .search_table_inr thead tr{
        display: flex;
        & th{
            display: none;
            &:last-child{
                display: block;
                width: 100%;
            }
        }
    }
    .car-module & .flight_res_wrap .flight_res_right{
        width: 100%;
        padding-left: 0;
        padding-right: 0;
    }
    .car-module & .flight_res_wrap{
        margin: 0;
    }
    & .flight_table.search_table_inr {
        overflow: initial;
    }
}
`;