import styled from "vue3-styled-components";

export const Instawrapper = styled.section`
padding:3.75rem 0 0 0;
& .nc-section{background:transparent;}
embed-instagram-feed {
    position: relative;
    :before {
    background: #F7FAFC;
    min-height: 88px;
    width: 100%;
    content: '';
    position: absolute;
}
}
div#instagram-feed {
    display: grid;
    grid-template-columns: auto auto auto auto;
    grid-column-gap: 0.2rem;
}
div#instagram-feed a img {
    height: 15rem;
    width: 100%;
    object-fit: cover;
}

/* .title_inner {
    position: relative;
    top: 3rem;
    z-index: 99;
} */

@media (max-width: 768px){
    padding: 3rem 0;
    & h3.text-center{
        /* text-align: left; */
        line-height: 1.2;
        font-size: 1.875rem;
        & br{
            display: none;
        }
    }
    & .partner_sec{justify-content: center;}
    & div#instagram-feed {
    grid-template-columns: auto auto;
}

}
`