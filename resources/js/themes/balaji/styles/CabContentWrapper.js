import styled from "vue3-styled-components";

export const CabContentWrapper = styled.section`
& .cabs_img1 {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    height: 15.625rem;
    opacity: .9;
    /* margin-top: 1.875rem; */
}
& .cabs_img2 {
    width: 15%;
    background-color: #eee;
    border-radius: 50%;
    margin-top: -2.5rem;
    opacity: .9;
    float: right;
    margin-right: 0.875rem;
}
& .product-thumb .cabs {
    height: 100%;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0px 0px 11px 0px rgba(191,191,191,0.61);
}
& .card_cabs {
    padding: 1.25rem;
}
& .outstation_li_img {
    width: 2.188rem;
    height: 2.188rem;
    filter: invert(25%) sepia(97%) saturate(7467%) hue-rotate(221deg) brightness(135%) contrast(122%);
    /* margin-top: 0.313rem; */
}
& .outstation_li_txt {
    list-style: none;
    font-size: 0.8rem;
    margin-left: 0.238rem;
}
& li.outstation_li {
    width: 3.188rem;
}
@media (max-width: 767px){
    
}
`;