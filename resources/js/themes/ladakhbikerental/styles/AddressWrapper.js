import styled from "vue3-styled-components";

export const AddressWrapper = styled.section`
    padding: 0rem 0 10rem;
    /* & .gray_add_box{
        background-color: #f1f1f1;
        padding: 2rem;
    } */
    
& .title {
    font-size: 1.54rem;
    font-weight: 600;
    color: var(--theme-color);
}
.st_banner {
    position: relative;
    padding-bottom: 3rem;
}
// & .gray_add_box h1{
//     font-size: 2.5rem;
//     padding-bottom: 1rem;
//     font-weight: 700;
//     color: var(--theme-color);
//     text-align: center;
//     text-decoration: underline;
// }
    /* .uk-position-cover {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgb(12 12 12 / 40%);
} */

/* .st_banner .header_content {
    position: absolute;
    top: 0;
    display: flex;
    height: 100%;
    flex-direction: column;
    justify-content: center;
    width: 33rem;
    color: #fff;
    z-index: 5;
} */

/* .st_banner .header_content p{
    color: #5e5e5e;
    font-size: 1.5rem;
    line-height: 1.5;
} */

    @media (max-width: 992px){
        padding-top: 0rem;
        & .gray_add_box{
            padding: 1.1;
        }
    }
    @media (max-width: 767px){
        &.st_banner .inner_banner2 {
        height: 550px;
        object-fit: cover;
      }
    }
`