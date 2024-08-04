import styled from "vue3-styled-components";

export const PopularHolidayWrapper = styled.section`
& .section_title{padding: 4rem 0 1.2rem;}
& .title_box {
    display: flex;
    align-items: center;
    margin-bottom: 1.3rem;
    & .title{
        font-size: 34px;
        font-weight: 600;
    }
    & .right_btn{margin-left: auto;}
}
& .right_btn a:hover{color: var(--white)!important;}
& .section_content a { 
    height: 20.875rem; 
    width: 100%; 
    position: relative;
    & span {
        position: absolute;
        top: 0.8rem;
        left: 0rem;
        padding: 0.5rem 0.8rem;
        border-radius: 4px;
        font-weight: 500;
        line-height: 1;
        color: var(--white);
        right: 0;
        width: 100%;
        text-align: center;
    }
    & img { 
        width: 100%; 
        height: 20rem; 
        object-fit: cover !important; 
        border-radius: 15px;
    }
}
& .txtbtn {
    position: absolute;
    bottom: 30px;
    left: 0;
    right: 0;
    text-align: center;
    color: #fff;
}
@media (max-width: 768px){
    & .section_title {
        padding-top: 1rem;
    }
   & .title_box .right_btn {
    margin-left: 0;
    text-align: left;
    margin-top: 15px;
}
    & .title_box{
        flex-direction: column;
        align-items: flex-start;
    }
    & .title_box .title {
    font-size: 25px;
}
    
}
`