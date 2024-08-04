import styled from "vue3-styled-components";

export const HotelDetailPageWrapper = styled.div`
& .tabs {
    border-radius: 0.3rem;
    display: flex;
    justify-content: flex-start;
    margin-bottom: 1rem;
    margin-right: auto;
    margin-top: 1.3rem;
    overflow: hidden;
    width: 100%;
    list-style: none;
    overflow: hidden;
    padding: 0;
    & li{
        display: inline-block;
        & a{
            position: relative;
            display: block;
            padding: 0.625rem 1.563rem;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            background: #fff;
            transition: padding .2s ease,margin .2s ease;
            &.active{
                border-bottom: 2px solid var(--theme-color);
            }
        }
    }




}
& .hotel-brief table tr th, .hotel-brief table tr td {
    border: 1px solid #dee2e6;
    padding: 0.55rem;
    vertical-align: top;
}

.rating-txt li:last-child {
    border-radius: 5rem 5rem 5rem 0;
    background: var(--theme-color);
    width: 2.5rem;
    height: 2.5rem;
    margin-left: 1rem;
    font-size: .8rem;
    color: #fff;
    line-height: 2.5rem;
    text-align: center;
}
.highlights_facility_bottom ul li {
    padding-bottom: 4px;
    padding-right: 8px;
    width: 15%;
    align-items: center;
    display: flex;
    font-size: .8rem;
    font-weight: 600;
}
.highlights_facility_bottom ul li svg {
    margin-right: 0.3rem;
}
.related-hotel .featured_content {
    margin-bottom: 1rem;
    box-shadow: 0 2px 5px 1px rgba(64,60,67,.16);
    border-radius: 0.5rem;
}

[tooltip] {
    position: relative;
}
[tooltip]:after,[tooltip]:before {
    display: none;
    font-size: .75rem;
    line-height: 1.2;
    opacity: 0;
    pointer-events: none;
    position: absolute;
    text-transform: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none
}

[tooltip]:before {
    border: 5px solid transparent;
    content: "";
    z-index: 1001
}

[tooltip]:after {
    background: var(--theme-color);
    border-radius: .1rem;
    box-shadow: 0 1em 2em -.5em rgba(0,0,0,.35);
    color: #fff;
    content: attr(tooltip);
    font-family: Helvetica,sans-serif;
    max-width: 16rem;
    padding: .5rem 1rem;
    text-align: center;
    text-overflow: ellipsis;
    width: -moz-max-content;
    width: max-content;
    z-index: 1000
}

[tooltip]:hover:after,[tooltip]:hover:before {
    display: block
}

[tooltip=""]:after,[tooltip=""]:before {
    display: none!important
}

[tooltip]:not([flow]):before,[tooltip][flow^=up]:before {
    border-bottom-width: 0;
    border-top-color: var(--theme-color);
    bottom: 100%
}

[tooltip]:not([flow]):after,[tooltip][flow^=up]:after {
    bottom: calc(100% + 5px)
}

[tooltip]:not([flow]):after,[tooltip]:not([flow]):before,[tooltip][flow^=up]:after,[tooltip][flow^=up]:before {
    left: 50%;
    transform: translate(-50%,-.5em)
}

[tooltip][flow^=down]:before {
    border-bottom-color: #333;
    border-top-width: 0;
    top: 100%
}

[tooltip][flow^=down]:after {
    top: calc(100% + 5px)
}

[tooltip][flow^=down]:after,[tooltip][flow^=down]:before {
    left: 50%;
    transform: translate(-50%,.5em)
}

[tooltip][flow^=left]:before {
    border-left-color: #333;
    border-right-width: 0;
    left: -5px;
    top: 50%;
    transform: translate(-.5em,-50%)
}

[tooltip][flow^=left]:after {
    right: calc(100% + 5px);
    top: 50%;
    transform: translate(-.5em,-50%)
}

[tooltip][flow^=right]:before {
    border-left-width: 0;
    border-right-color: #333;
    right: -5px;
    top: 50%;
    transform: translate(.5em,-50%)
}

[tooltip][flow^=right]:after {
    left: calc(100% + 5px);
    top: 50%;
    transform: translate(.5em,-50%)
}

@keyframes tooltips-vert {
    to {
        opacity: .9;
        transform: translate(-50%)
    }
}

@keyframes tooltips-horz {
    to {
        opacity: .9;
        transform: translateY(-50%)
    }
}

[tooltip]:not([flow]):hover:after,[tooltip]:not([flow]):hover:before,[tooltip][flow^=down]:hover:after,[tooltip][flow^=down]:hover:before,[tooltip][flow^=up]:hover:after,[tooltip][flow^=up]:hover:before {
    animation: tooltips-vert .3s ease-out forwards
}

[tooltip][flow^=left]:hover:after,[tooltip][flow^=left]:hover:before,[tooltip][flow^=right]:hover:after,[tooltip][flow^=right]:hover:before {
    animation: tooltips-horz .3s ease-out forwards
}

@media (max-width: 768px){
& .highlights_facility_bottom ul li svg {
    float: left;
    fill: var(--orange);
}
& #content_facilities .highlights_facility_bottom ul li {
    width: auto;
    display: inline-block;
}
& #content_facilities .highlights_facility_bottom ul.flex {
    width: 100%;
    display: inline-block;
}
}
`