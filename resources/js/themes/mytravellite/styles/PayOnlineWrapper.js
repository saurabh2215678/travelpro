import styled from "vue3-styled-components";

export const PayOnlineWrapper = styled.section`
padding: 4rem 0 4rem;
& .gray_add_box{
    background-color: #f1f1f1;
    padding: 2rem;
}
& .left_sec{
    position: relative;
    & .booking_problem_img {
        position: absolute;
        top: 30%;
        left: 0;
        width: 100%;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 600;
        & strong{
            font-weight: 400;
        }
    }
}
@media (max-width: 767px){
        padding: 5rem 0 3rem;
& .submit_btn button {
    margin: 0.5rem 0.5rem;
}
    
}
`