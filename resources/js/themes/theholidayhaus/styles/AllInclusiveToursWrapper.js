import styled from "vue3-styled-components";

export const AllInclusiveToursWrapper = styled.section`
padding: 6rem 0;
margin:3rem 0;
position: relative;
& .theme_bg2 {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
}
& h3{
    line-height: 1.1;
    margin-bottom: 3rem;
    color:var(--secondary-color);
    position: relative;
    :before{
        content: '';
        background: var(--secondary-color);
        width: 50px;
        position: absolute;
        height: 3px;
        top: 65px;
        margin: 0 auto;
        left: 0;
        right: 0;
    }
}
& .svg_box {
    --size: 4.438rem;
    max-width: var(--size);
    max-height: var(--size);
    width: 100%;
    height: 100%;
    margin-right: 0.25rem;
    display: flex;
    align-items: center;
}
& .box_content p {
    line-height: 1.25;
    margin-top: 0.2rem;
}
@media (max-width: 768px){
    padding: 3rem 0;
    & h3.text-center{
        text-align: left;
        & br{
            display: none;
        }
    }
    & h3{
        font-size: 25px;
        margin-bottom: 10px;
    :before{
        content: '';
        top: 90px;
        opacity: 0;
    }
}  
& .box_content h5 {
    font-size: 1.438rem;
}
& .grid.gap-6.mt-3 {
    display: flex;
    flex-wrap: wrap;
}   
& .grid.gap-6.mt-3 .flex {
    width: 45%;
}


}
`