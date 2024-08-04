import styled from "vue3-styled-components";

export const HomeBlogGridWrapper = styled.section`
padding-top: 5rem;
padding-bottom: 4rem;
& .blog_top {
    & .font19{
        color: var(--orange);
    }
    & .font40{
        margin-bottom: 2rem;
    }
}
& .blog_grid{
    & ul{
        
        & li{
            padding-bottom: 1.6rem;
            & .blog_box {
                position: relative;
                display: flex;
                align-items: center;
                & .blog_image {
                    height: 9.625rem;
                    width: 15.563rem;
                    border-radius: 12px;
                    overflow: hidden;
                    & img{
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: 0.5s;
                    }
                }
                & .blog_content {
                    width: calc(100% - 15.563rem);
                    padding-left: 2.3rem;
                    & .pill {
                        background-color: var(--orange);
                        padding: 0.2rem 0.85rem;
                        color: var(--white);
                        font-size: 0.8rem;
                        border-radius: 4px;
                        margin-bottom: 0.63rem;
                        opacity: 0.89;
                    }
                    & .blog_title {
                        font-size: 1.375rem;
                        color: #2c2c2c;
                        overflow: hidden;
                        display: -webkit-box;
                        -webkit-line-clamp: 1;
                        line-clamp: 1;
                        -webkit-box-orient: vertical;
                        margin-bottom: 0.6rem;
                    }
                    & .blog_desc {
                        color: #2c2c2c;
                        overflow: hidden;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        line-clamp: 2;
                        -webkit-box-orient: vertical;
                    }
                    & .blog_link {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        opacity: 0;
                    }
                }
               
            }
            &:not(:first-child){
                & .blog_box{
                    &:hover{
                        & .blog_image img{
                            transform: scale(1.1);
                        }
                        & .blog_desc,
                        & .blog_title{
                            color: var(--theme-color-dark);
                        }
                    }
                }
            }
        }
        
    }
    
}
@media (min-width: 800px){
    & .blog_grid{
        & ul{
            display: grid;
            grid-template-columns: repeat(2,1fr);
            grid-template-rows: repeat(4,11rem);
            
            & li:first-child{
                grid-column-start: 1;
                grid-column-end: 2;
                grid-row-start: 1;
                grid-row-end: 5;
                padding-right: 3.75rem;
                & .blog_box{
                    flex-direction: column;
                    align-items: flex-start;
                    & .blog_image{
                        width: 100%;
                        height: 23.375rem;
                        margin-bottom: 2.3rem;
                    }
                    & .blog_content{
                        padding: 0;
                        width: 100%;
                        & .blog_title{
                            font-size: 1.688rem;
                        }
                        & .blog_desc{
                            -webkit-line-clamp: 4;
                            line-clamp: 4;
                        }
                        & .blog_link{
                            position: static;
                            opacity: 1;
                            width: initial;
                            height: initial;
                            padding: 0.6rem 1.8rem;
                            background-color: #14bad4;
                            text-transform: uppercase;
                            font-weight: 500;
                            color: #fff;
                            margin-top: 2rem;
                            border-radius: 5rem;
                            &:hover{
                                background-color: #0991a6;
                            }
                        }
                    }
                }
            }
        }
    }
}

@media (max-width: 800px){
    padding-top: 3rem;
    & .blog_grid{
        & ul{
            & li .blog_box {
                & .blog_image{
                    height: 8.625rem;
                    width: 10rem;
                    border-radius: 11px;
                }
                & .blog_content{
                    width: calc(100% - 10rem);
                    padding-left: 1.1rem;
                }
            }
        }
    }
}
`