import styled from "vue3-styled-components";

export const AboutHomeWrapper = styled.section`
    padding: 3.188rem 0;
    background: url(../images/bg_about.jpg);
    background-size: 100% 100%;
    /* background-repeat: no-repeat; */
    border: 20px solid transparent;
    border-image-source: url(../images/border_bg.png);
    border-image-repeat: space;
    border-image-slice: 30;
& .about_content.collapsed>p {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    
}
& .about_content.collapsed > div > div {
    visibility: hidden;
    height: 0;
}
& .about_content > div > div {
    visibility: inherit;
    height: auto;
}
& .about_content .read_option i {
    transform: rotate(180deg);
}
& .about_content.collapsed .read_option i {
    transform: inherit;
}

    & .read_option { 
        display: inline-block; 
        color: #333333; 
        margin-top: 0.5rem; 
        cursor: pointer;
        text-transform: uppercase;
        text-decoration: underline;
        & i{
            margin-left: 0.4rem;
        }
    }

& .title_left_top{color: var(--orange); display: block;}
& .title_left {color: #0c2f64; display: block;}
& .about_head{font-size: 55px;font-family: 'Playfair Display',serif;color: rgb(255, 255, 255);font-weight: 500;    line-height: 60px;margin-bottom: 2rem;}
& .about_content p{font-size: 17px;font-family: 'Jost',sans-serif;color: rgb(255, 255, 255);line-height: 1.353;font-weight:300;}



@media (max-width: 767px){
    & .about_head{font-size: 2rem;line-height: 2.5rem;}
    & .test_shape_bg{top: 0;}
    & .about_home_page {background-size:cover;background-repeat:no-repeat;}
    & .about_content{
        p{font-size:14px;}
    }
    /* Rakshit */


    padding: 2.6rem 0;background-size:cover;
    & .title_left {font-size: 2rem;}
}
`