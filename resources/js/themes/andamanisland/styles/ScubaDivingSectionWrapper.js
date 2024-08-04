import styled from "vue3-styled-components";

export const ScubaDivingSectionWrapper = styled.section`
--bottom-offset: 18rem;
padding: 7.5rem 0;
background: url(../assets/andamanisland/images/scuba_bg.jpg) center center no-repeat;
background-size: cover;
position: relative;
margin-bottom: calc(var(--bottom-offset) - 3rem);

& .excontent {
    width: 100%;
    color: #fff;
    text-align: center;
    & .heading2 {
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 1.25rem;
        font-size: 1.875rem;
        & strong {
            display: block;
            font-size: 1.25rem;
            font-weight: 400;
            color: #fe7524;
        }
    }
    & p{
        color: #fff;
        font-size: 1.125rem;
        max-width: 52.938rem;
        margin: auto;
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
    --bottom-offset: 12rem;
    padding-top: 3rem;
}
`