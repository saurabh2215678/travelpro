import styled from "vue3-styled-components";

export const ScubaDivingSectionWrapper = styled.section`
padding:7.188rem 0;
padding-top:0;
/* background: url(../assets/andamanisland/images/scuba_bg.jpg) center center no-repeat;
background-size: cover;
position: relative;
margin-bottom: calc(var(--bottom-offset) - 3rem); */

& .excontent {
    width: 100%;
    color: #fff;
    text-align: center;
    background-color: #cb984a;
    position: relative;
    padding: 3.75rem;
    border-radius: 1rem;
    margin-top: 5.625rem;
    & .heading2 {
        font-weight: 500;
        margin-top: 0;
        margin-bottom: 1.25rem;
        font-size: 45px;
        font-family: 'Playfair Display', serif;
        color: rgb(255, 255, 255);
        line-height: 1.178;
        & strong {
            display: block;
            font-size: 1.25rem;
            font-weight: 400;
            color: #fe7524;
        }
    }
    & p{
        color: #fff;
        max-width: 52.938rem;
        margin: auto;
        font-size: 20px;
        font-family: 'Jost',sans-serif;
        line-height: 1;
    }
    & a.readbtn {
        background: #ffd800;
        padding: 0.4rem 1.563rem;
        color: #000;
        display: inline-block;
        border-radius: 5rem;
        font-weight: 600;
        font-size: 1rem;
        margin-top: 0.6rem;
    }
}
& .expimg {
    width: 100%;
    margin-top: 2.813rem;
    overflow: hidden;
    position: relative;
    margin-bottom: calc(0px - var(--bottom-offset));
    text-align: center;
    & img{
        width: 61.563rem;
    }
    & .play-btn {
        color: #0077c1;
        font-size: 1.875rem;
        left: 50%;
        padding-left: 0.438rem;
        position: absolute;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
        z-index: 1;
        width: 5rem;
        height: 5rem;
        border-radius: 100%;
        background: rgba(255,255,255,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        &:before{
            content: "";
            position: absolute;
            z-index: 0;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
            display: block;
            width: 4.375rem;
            height: 4.375rem;
            background: #fff repeat scroll 0 0;
            border-radius: 100%;
            animation: pulse-border 1500ms ease-out infinite;
        }
        &:after{
            content: "";
            position: absolute;
            z-index: 1;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
            display: block;
            width: 4.375rem;
            height: 4.375rem;
            background: rgba(255,255,255,0.2) repeat scroll 0 0;
            border-radius: 100%;
            transition: all 200ms;
            border: 1px solid #0077c1;
        }
    }
}
& .container{
    position: relative;
    z-index: 3;
}


& .excontent:after{position:absolute;right:0;content:'';background:url(../images/static_img.png);width:202px;height:165px;bottom:0;}
& .cta_flex{display:flex;justify-content:space-between;margin-top:2rem;width:80%;margin:2rem  auto 0;flex-wrap:wrap;}
& .cta_flex li a{
    background-color: #ad083d;
    color: #fff;
    display: inline-block;
    padding: 0.6rem 3.2rem;
    border-radius: 40px;
    font-weight: 500;
}


@keyframes pulse-border {
  0% {
    transform: translateX(-50%) translateY(-50%) translateZ(0) scale(1);
    opacity: 1;
  }

  100% {
    transform: translateX(-50%) translateY(-50%) translateZ(0) scale(1.5);
    opacity: 0;
  }
}

@media (max-width: 767px){
    padding:3.188rem 0;
    & .excontent{
        padding:1rem;
        .heading2{font-size:1.5rem;}
        p{font-size: 1rem;}
    }
    & .cta_flex li
    {margin-right:.5rem}
    a{padding: 0.6rem 1rem!important;margin-bottom:.5rem;font-size: 12px;}
    & .cta_flex{justify-content:center;width:100%;position: relative;z-index: 9;}
    & .excontent{margin-top:0;}
    &section{padding:3rem 0;}
     

    /* Rakshit */
    --bottom-offset: 12rem;
    padding-top: 3rem;
}
`