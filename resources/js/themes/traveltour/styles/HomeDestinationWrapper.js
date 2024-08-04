import styled from "vue3-styled-components";

export const HomeDestinationWrapper = styled.section`
    padding-top: 7.3rem;
    padding-bottom: 6rem;
& .destination-gallery {
    display: flex;
}
& .dest_left {
    width: 25%;
}
& .dest_middle{
    width: 35%;
    display: flex;
}
& .dest_right{
    width: 40%;
}
& .dest_card a {
     display: block; position: relative; width: 100%; border-radius: 1.438rem; overflow: hidden; 
     &:before{
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-image: linear-gradient(0deg, #0000008a, transparent);
        height: 6.5rem;
     }
    }
& .dest_card p { 
    position: absolute;
    bottom: 1rem;
    z-index: 1;
    color: var(--white);
    left: 1rem; 
    max-height: calc(100% - 1rem);
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    /* word-break: break-all; */
    max-width: calc(100% - 1rem);
}
& .dest_card a img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
& .dest_card { display: flex;padding: 1.063rem; }
& .right_bottom_dest { display: flex; }
& .dest_left>*, 
& .dest_right>* {
    height: 50%;
}
& .right_bottom_dest .dest_card:first-child {
    width: 38%;
}
& .right_bottom_dest .dest_card:last-child {
    width: 62%;
}
& .section_title{
    position: relative;
    width: fit-content;
    margin: 0 auto;
    margin-bottom: 1.25rem;
    padding: 0 2rem;
}
& .plane_blue {
    position: absolute;
    left: 100%;
    bottom: 0;
    width: 9.813rem;
}
@media (max-width: 1024px){
    padding-top: 5rem;
}
@media (max-width: 767px){
    overflow: hidden;
    padding-bottom: 2rem;
    & .plane_blue {
        left: 0;
        width: 5rem;
        bottom: 3.5rem;
    }
    
    & .dest_card{
        padding: 0.2rem;
        & a{
            border-radius: 5px;
        }
        & p{
            left: 0.5rem;
            font-size: 0.75rem;
        }
    }
   
}
`