import styled from "vue3-styled-components";

export const CertifyWrapper = styled.section`
padding: 3.125rem 0;
& .heading2{
    color: #111113;
    font-size: 2.5rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 4.6rem;
    margin-top: 2rem;
}
& ul {
    list-style: none;
    padding: 0;
    margin: 0 -0.625rem;
    & li{
        width: 25%;
        float: left;
        padding: 0.625rem;
    }
}
& .certbox {
    background: #f5f5f5;
    padding: 4.813rem 1.25rem 1.875rem;
    text-align: center;
    position: relative;
    margin-bottom: 1.875rem;
    & h3{
        color: #0d0d10;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    & p{
        font-size: 1.063rem;
        color: #4f4f57;
    }
}
& .certlogo {
    width: 6.625rem;
    height: 6.625rem;
    background: #f5f5f5;
    border-radius: 50%;
    position: absolute;
    left: 50%;
    top: -2.813rem;
    margin-left: -2.813rem;
    display: inline-block;
    overflow: hidden;
    border: 3px solid #fff;
    & img{
        mix-blend-mode: multiply;
    }
}
@media (max-width: 767px){
    & .heading2 {
        margin-top: 0;
        margin-bottom: 2.5rem;
        font-size: 2rem;
    }
    & ul {
        display: flex;
        flex-direction: column;
        & li{
            width: 100%;
        }
    }
}
`