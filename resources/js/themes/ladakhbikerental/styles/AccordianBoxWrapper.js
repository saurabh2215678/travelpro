import styled from "vue3-styled-components";

export const AccordianBoxWrapper = styled.div`
/* padding: 1rem 0; */
&:first-child{
    padding-top: 0;
}
&:last-child{
    padding-bottom: 0;
}
& .description{
    display:flex;
    flex-direction: column;
    margin:0;
}
& .open_text {
    position: relative;
    /* cursor: pointer; */
    transition: all .3s ease-in-out;
    padding-right: 2.5rem;
    border-bottom: none;
    
    & i{
        position: absolute;
        right: 0;
        top: 0;
        color: #999;
        font-size: 1.875rem;
        padding: 5px;
        transition: all .3s ease-in-out;
    }
    & .image {
        margin-top: 10px;
        margin-bottom: 10px;
        width: 25%;
        & .text-lg{
            display: none;
        }
    }
    & .brief:not(.w-full) {
        width: 75%;
    }
}
@media (max-width: 767px){
    & .open_text {
        flex-direction: column;
        padding-right: 0;
        & .image {
            margin-top: 0;
            width: 100%;
            & .text-lg{
                display: block;
                margin-bottom: 0.4rem;
            }
        }
        & .brief:not(.w-full) {
            width: 100%;
            & .text-lg{
                display: none;
            }
        }
        
    }
}



.faq_data span p:nth-child(n+6), .faq_data span div:nth-child(n+6), .faq_data span li:nth-child(n+6) {
    display: none!important;
}

.faq_data span p, .faq_data span div, .faq_data span li {
    overflow: hidden;
    display: -webkit-box!important;
    -webkit-line-clamp: 1; /* number of lines to show */
    line-clamp: 1;
    -webkit-box-orient: vertical;
}

.faq_data span li {list-style: inside;padding-left: 16px;position: relative;}

.faq_data span li:before {
    content: "";
    position: absolute;
    width: 5px;
    height: 5px;
    background: currentColor;
    border-radius: 50%;
    left: 0;
    top: 8px;
}

`;