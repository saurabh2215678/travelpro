import styled from "vue3-styled-components";

export const RecognizedSection = styled.section`
padding: 3.125rem 0;background:#f9f9fb;
& .partner_sec h3{text-transform: uppercase;letter-spacing: 2px;}
& .prtner_flex{
    margin: 0 -1rem;
    li{padding:0 1rem;}
} 
@media (max-width: 768px){
    padding: 1rem 0;
    & h3.text-center{
        /* text-align: left; */
        & br{
            display: none;
        }
    }
   & .partner_sec h3 {
    margin-bottom: 0;
}
& .partner_sec .mt-16 {
    margin-top: 1rem;
}
& .partner_sec>div {
    width: 100%;
}

}
`