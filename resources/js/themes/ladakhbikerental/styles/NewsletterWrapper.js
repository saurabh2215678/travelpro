import styled from "vue3-styled-components";

export const NewsletterWrapper = styled.section`
padding:8.75rem 0 6.25rem 0;

& h3.text-center{
    line-height: 1.1;
    margin-bottom: 3rem;
}
& .svg_box {
    --size: 4.438rem;
    max-width: var(--size);
    max-height: var(--size);
    width: 100%;
    height: 100%;
    margin-right: 1.25rem;
    margin: 0 auto;
}
& .box_content p {
    line-height: 1.25;
    margin-top: 0.2rem;
}
& .why_ride_sec{text-align:center;}
& .why_icon img{margin:0 auto;}
& .box_content{
    margin-top: 0.625rem;
    h5{line-height:1.2rem;}
    }

& .form-floating .form-control{height: 3.125rem!important;border:1px solid #979797;}
& .footer_img{margin-top: -23rem;}
@media (max-width: 768px){
    padding: 3rem 0;
    & h3.text-center{
        text-align: left;
        & br{
            display: none;
        }
    }
    & .footer_img{margin-top:0;}
}
`