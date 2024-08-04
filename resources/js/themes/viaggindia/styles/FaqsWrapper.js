import styled from "vue3-styled-components";

export const FaqsWrapper = styled.div`
& .ftitle{
    margin-bottom: 5px;
    transition: all .3s ease-in-out;
    border-top: 1px solid #e5e6e9;
    color: #5c5c61;
    padding: 15px 30px 15px 35px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    position: relative;
    text-align: left;
    display: block;
    & span{
        line-height: 20px;
        color: #f36a2e;
        display: block;
        position: absolute;
        left: 0;
        top: 14px;
        font-size: 16px;
        font-weight: 500;
    }
    &:after{
        content: "+";
        line-height: 20px;
        font-size: 30px;
        font-weight: 500;

        color: #f36a2e;
        display: block;
        position: absolute;
        right: 15px;
        top: 14px;
    }
}
& li.active .ftitle:after{
    content: "-";
    line-height: 16px;
    font-size: 40px;
    font-weight: 500;
}
& .faqlist { 
    background: #fff;
    &>ul {
        list-style: none;
        padding: 0;
        margin: 0;
        &>li:first-child .ftitle{
            border-top: 0;
        }
    }
}


& .fqcont {
    padding: 1.25rem;
    padding-top: 0;
    padding-left: 2.8rem;
    &>*{
        font-size: 16px;
        line-height: 1.4;
        color: #666;
        text-align: left;
    }
}

& p{
    margin: 0 0 10px;
}
& ol ol,
& ol ul,
& ul ol,
& ul ul {
    margin-bottom: 0;
}
& ol, 
& ul {
    margin-top: 0;
    margin-bottom: 10px;
}
& ol {
    display: block;
    list-style-type: decimal;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}
& ul ol {
    margin-block-start: 0px;
    margin-block-end: 0px;
}
& ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
    ul{
        list-style-type: circle;
        margin-block-start: 0px;
        margin-block-end: 0px;
    }
}
& *::marker {
    unicode-bidi: isolate;
    font-variant-numeric: tabular-nums;
    text-transform: none;
    text-indent: 0px !important;
    text-align: start !important;
    text-align-last: start !important;
}
& .read_option {
    display: none;
}
@media (max-width: 767px){
    & .faqlist ul li:nth-child(n + 6){
    display: none;
}
& .faqlist ul li:last-child {
    display: block;
    text-align: center;
}
& .faqlist ul li .read_option {
    cursor: pointer;
    display: inline-block;
    background: var(--theme-color);
    padding: 0.3rem 1rem;
    border-radius: 5rem;
    color: #fff;
    :hover{
      color: var(--white700);
    }
}
& .faqlist.collapsed ul li:nth-child(n + 6){
    display: block;
}
& .faqlist.collapsed ul li .read_option i {
    transform: rotate(180deg);
}
}

`