import styled from "vue3-styled-components";

export const FlightTopAreaWrapper = styled.div`
@media (max-width: 768px){
    &.search_list_flight .flight_search_detail{
        overflow: initial;
        flex-wrap: wrap;
    }
    & .flight_info_scroller { flex-grow: 1; width: 100%; max-width: 100%; justify-content: center; margin-bottom: 0.3rem; }
    & .sch2 {
        margin: 0!important;
        flex-grow: 1;
        margin-bottom: 0.3rem!important;
    }
    & .sch3 {
        margin: 0!important;
        flex-grow: 1;
        margin-bottom: 0.3rem!important;
    }
    & .sch4 {
        flex-grow: 1;
        margin-left: 0.3rem!important;
        margin-bottom: 0.3rem!important;
    }
    & .modify_btn_sec {
        width: 100%;
        padding: 0!important;
        line-height: 1!important;
        & button {
            height: initial!important;
            width: 100%;
            padding: 0.8rem;
        }
    }
    & .closeModifySearch {
        top: -1.5rem;
    }
}
`