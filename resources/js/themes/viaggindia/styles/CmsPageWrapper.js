import styled from "vue3-styled-components";

export const CmsPageWrapper = styled.section`
padding-bottom: 2.5rem;
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    color: var(--theme-color);
}
& .cms_image {
    margin: 1.5rem 0 1rem;
    width: 100%;
    margin-right: 1rem;
    & img{
        width: 100%;
        object-fit: cover;
        max-height: 28rem;
    }
}
& .cms_content h2 {
  font-size:1.2rem;
  /* font-weight: 600; */
}
& .cms_content p {
  color: #3e3e3e;
  line-height: 1.4;
  margin-bottom: 1rem;
}

/* ====== CMS Style ==== */
h4 {
  font-size: 1.2rem;
  font-weight:600;
  padding-bottom: 0.3rem;
}
& .cms_content h3{
  font-size: 1.45rem;
  color: var(--theme-color);
  margin-top: 1.25rem;
  margin-bottom: 0.625rem;
  position: relative;
}
& .cms_content h3:after{
  content: "";
  display: block;
  width: 3.125rem;
  border-bottom: 2px solid #facf08;
  margin-top: 0.313rem;
}
& .cms_content ul.blocks-gallery-grid {
  display: flex;
  flex-wrap: wrap;
  list-style-type: none;
  padding: 0;
  margin: 0;
  width: 100%;
}
& .cms_content ul.blocks-gallery-grid li{
  margin: 0 1rem 1rem 0;
  display: flex;
  flex-grow: 1;
  flex-direction: column;
  justify-content: center;
  width: min-content;
  height: 18.75rem;
  overflow: hidden;
  flex-basis: 30.33%;
  padding-left: 0px !important;
  margin-bottom: 0.625rem;
  padding-bottom:0.313rem;
}
& .cms_content ul.blocks-gallery-grid li::before, & .cms_content ul.blocks-gallery-grid li::after{
  display: none;
}
& .cms_content ul.blocks-gallery-grid li figure {
  display: inline-block;
  height: 100%;
}
& .cms_content ul.blocks-gallery-grid li figure a{
  height: 100%;
  width:100%;
}
& .cms_content ul.img-data li {
  position: relative;
  padding-bottom: 0.313rem;
  display: inline-block;
  align-items: center;
  margin-bottom: 1.25rem;
  width: 100%;
  padding-left: 0px;
}
& .cms_content ul.blocks-gallery-grid li figure a img {
  height: 100%;
  object-fit: cover;
  width: 100%;
}

& .cms_content .img-data li .img-sec {
  width: 50%;
  float: left;
  filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));
}
& .cms_content .img-data li .para-sec {
  width: 50%;
  float: left;
  padding:2rem;
}
& .cms_content .img-data li:nth-child(even) .img-sec{
  float: right;
}
& .cms_content .img-data li:nth-child(even) .para-sec{
  padding: 2rem;
}

& .bg-yellow-1{
    background: #ff964f;
    width: 100%;
    padding: 1.563rem 1.563rem 0.625rem;
    margin: 1.5rem 0 1rem;
}
& .bg-yellow-1 p {
    color: #fff;
}
& .bg-yellow-1 h4 {
    color: #fff;
    font-size: 1.25rem;
}
& .bg-yellow-1 h3{
    color: #fff;
}
& .column-star {
    margin: 40px 0 0;
    .row {
    display: flex;
    column-gap: 1.2rem;
    }
}
& .bg-yellow-0{
    background: #f1f1f1;
    width: 100%;
    padding: 1.563rem 1.563rem 0.625rem;
    margin: 1.5rem 0 1rem;
}
& .bg-yellow-0 p{
    color: #3e3e3e;
}
& .bg-yellow-0 h4{
    color: #3e3e3e;
    font-size: 1.25rem;
}
& ul.inr-list.row {
    display: flex;
    column-gap: 1rem;
    flex-wrap: wrap;
}
& ul.inr-list.row li{
    /* padding-left: 1.25rem; */
    margin-bottom: 0.9rem;
    position: relative;
    width: 32.33333333%;
}
& ul.inr-list.row li a {
    display: block;
}
& ul.inr-list.row li a img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
& ul.inr-list.row li p{
    margin: 0;
    font-size: 1rem;
    position: absolute;
    bottom: 0;
    width: calc(100%);
    left: 0rem;
    color: #fff;
    padding: 0.938rem;
    padding-top: 2.25rem;
    background-image: linear-gradient(0deg, black, transparent);
    pointer-events: none;
    transition: all ease 0.5s;
}
& .inr-list.row li:hover p {
    background-image: linear-gradient(0deg,#404040fa,transparent);
}
& .firstbgsec {
    background: #fdfd97;
    padding: 2.5rem;
    margin-bottom: 1.875rem;
     img{
      padding-top: 1rem;
     }
}
& .cms_content ul li {
    padding-left: 1.25rem;
    margin-bottom: 0.563rem;
    position: relative;
}
& .cms_content ul:not(.img-data) li:before {
    content: '\f192';
    position: absolute;
    left: 0;
    color: #0077c1;
    font-family: 'FontAwesome';
    font-size: 0.8rem;
}
.dIgHYc .cms_content ul:not(.para-sec) li ul li {
    padding-left: 1.3rem;
    margin-bottom: 0.5rem;
}
& .fulltext {
    position: relative;
    width: 100%;
    float: left;
}
& .firstcontent {
    width: 50%;
    float: left;
    position: absolute;
    left: 0;
    top: 3.125rem;
    z-index: 2;
    background: #f1f1f1;
    padding: 3.125rem;
}
& .firstimg {
    width: 60%;
    margin-bottom: 3.125rem;
    float: right;
    position: relative;
}
& .fullcontent {
    clear: both;
}
& .firstbgsec {
    background: #fdfd97;
    padding: 2.5rem;
    margin-bottom: 1.875rem;
}
& .contentsecond {
    width: 50%;
    float: right;
    position: absolute;
    right: 0;
    top: 3.125rem;
    z-index: 2;
    background: #f1f1f1;
    padding: 3.125rem;
}
& .imgsecond {
    width: 60%;
    height: 28.125rem;
    margin-bottom: 3.125rem;
    float: left;
}
& .img1 img, & .imgsecond img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
& .graybg {
    padding: 1.563rem 1.563rem 0.625rem;
    background: #f1f1f1;
    margin-bottom: 1.875rem;
}
& .orangebg {
    background: #ff964f;
    padding: 1.563rem 1.563rem 0.625rem;
    margin-bottom: 1.875rem;
}
& .orangebg h4, & .orangebg p {
    color: #fff;
}
.yellowbg {
    background: #fdfd97;
    padding: 1.563rem 1.563rem 0.625rem;
    margin-bottom:  1.875rem;
}
& .secitonslist > ul > li:first-child, & .secitonslist > ul > li:nth-child(4) {
    background: #f1f1f1;
}
& .secitonslist > ul > li:nth-child(2), & .secitonslist > ul > li:nth-child(5) {
    background: #ff964f;
}
& .secitonslist > ul > li:nth-child(3) {
    background: #fdfd97;
}
& .secitonslist .img-sec {
    height: 30rem;
}
& .secitonslist .img-sec img {
    object-position: center center;
    object-fit: cover;
    width: 100%;
    height: 100%;
}
& .secitonslist > ul > li:nth-child(2) .para-sec p, & .secitonslist > ul > li:nth-child(2) .para-sec h4, & .secitonslist > ul > li:nth-child(5) .para-sec h4, & .secitonslist > ul > li:nth-child(5) .para-sec p {
    color: #fff;
}
& .img1 {
    width: 100%;
    height: 28.125rem;
}
& .halfleft {
    width: 48%;
    float: left;
    background: #f1f1f1;
    padding: 3.125rem;
    margin-bottom: 1.875rem;
}
.halfright {
    width: 48%;
    float: right;
    background: #f1f1f1;
    padding: 3.125rem;
    margin-bottom: 1.875rem;
}
& .firstimg h4 {
    position: absolute;
    right: 0;
    padding: 0.938rem;
    width: 50%;
    text-align: right;
    background: rgba(255,216,0,0.4);
}
& .halfcontent {
    background: #f1f1f1;
    padding: 1.563rem;
    width: 100%;
    float: right;
}
& .fullimg {
    width: 100%;
    float: left;
    margin-bottom: 1.875rem;
}
& .fullimg {
    position: relative;
    height: 40.625rem;
}
& .clist li {
    display: flex;
    align-items: center;
    margin-bottom: 1.2rem;
    padding-left: 0rem;
}
& .clist li .cimg {
    width: 25%;
    float: left;
    border-radius: 0.3rem;
    height: 12.5rem;
    overflow: hidden;
    filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));
}
& .clist li .cimg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
& .clist li .cpara {
    width: 75%;
    float: left;
    padding-left: 1.563rem;
}
& .slidesection {
    padding: 2.5rem 0;
}
& .leftslide {
    width: 50%;
    float: left;
    padding: 0px 3.125rem 3.125rem 0;
    position: relative;
}
& .destitext {
    width: 50%;
    float: right;
    padding-left: 3.125rem;
}
& .lcont {
    display: flex;
    flex-direction: row-reverse;
}
& .lcont .destimg {
    float: right;
    min-height: auto;
}
& .destimg {
    width: 50%;
    position: relative;
    min-height: 25rem;
}
& .lcont .destcontent {
    float: left;
    background: #fdfd97;
}
.destcontent {
    width: 50%;
    padding: 3.125rem;
    min-height: 25rem;
    text-align: justify;
}
& .fullwidth {
    width: 100%;
    float: left;
}
& .destimg img {
    object-position: center center;
    object-fit: cover;
    width: 100%;
    height: 100%;
}
& .rcont .destimg {
    height: 25rem;
}
& .rcont .destimg {
    float: left;
}
& .rcont .destcontent {
    float: right;
    background: #fff;
}
& .hleftcont {
    width: 50%;
    float: left;
    margin: 1.875rem 0;
}
& .hrightcont {
    width: 50%;
    padding-left: 2.5rem;
    margin: 1.875rem 0;
    float: left;
}
& .fullimg {
    width: 100%;
    float: left;
    position: relative;
    height: 30 .625rem;
    margin-bottom: 1.875rem;
}
& .fullimgtext {
    padding: 1.875rem 4.375rem;
    width: 45%;
    float: left;
    position: absolute;
    left: 5rem;
    top: 0;
    height: 100%;
    z-index: 2;
}
& .fullimgtext:before {
    content: "";
    background: rgba(255, 255, 255, 0.9);
    width: 100%;
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    display: block;
    -webkit-transform: skew(-10deg);
    -moz-transform: skew(-10deg);
    -o-transform: skew(-10deg);
    transform: skew(-10deg);
    z-index: -1;
    margin-left: -1.25rem;
}
& .fullimg img {
    object-position: center center;
    object-fit: cover;
    width: 100%;
    height: 100%;
}
& .imgsection2 {
    padding-top: 3.125rem;
}
& .halefimg {
    position: relative;
    width: 50%;
    float: left;
}
& .halfwith {
    width: 50%;
    float: right;
    padding: 1.25rem 3.125rem;
}
& .halefimg .dimg1 {
    width: 80%;
    overflow: hidden;
    border-radius: 50%;
    height: 28rem;
}
& .halefimg .dimg2 {
    position: absolute;
    width: 60%;
    overflow: hidden;
    border-radius: 50%;
    height: 22rem;
    bottom: -25%;
    left: 40%;
}
& .halefimg img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    object-position: center top;
}
& .bg-yellow-1 ol {
    list-style: auto;
    padding-left: 1.5rem;
    padding-bottom: 1rem;
}
& .detail_img {
    width: 50%;
    float: left;
    padding-right: 0.938rem;
}
& .page_template_default .detail_info {
    border: none;
    padding: 0px;
    width: 50%;
    float: left;
}
& .cms_content .img-data li .para-sec > ul li {
    padding-left: 1.2rem;
}
& ul.inr-list.row li.inr-list-item.col-lg-4{
    padding: 0;
    ::before{
    display: none;
}
}
& .bg-yellow-0 > ol {
    list-style: auto;
    padding-left: 1.2rem;
    padding-bottom: 1rem;
}

/* 25 sep 2023 */
& .dslides .owl-item img {
    width: 100%;
    height: 380px;
    object-fit: cover;
    object-position: center;
}
& .leftslide:before {
    content: "";
    width: 75%;
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    background: #ff964f;
}
& .owl-buttons > div,
& .owl-nav > div,
& .owl-nav > button {
    top: 50%;
    position: absolute;
    color: #fff;
    z-index: 9;
    width: 36px;
    height: 36px;
    background: rgba(0,0,0,0.7);
    margin-top: -18px !important;
    font-size: 0px;
}
& .owl-nav > div,
& .owl-nav > button {
    position: absolute;
    top: 48%;
    padding: 10px;
    transition: 0.3s;
    background: 0 0;
    color: #666;
}
& .owl-nav > .owl-next {
    right: 0px;
}
& .owl-nav > .owl-prev, 
& .owl-buttons > .owl-prev {
    transform: rotate(180deg);
    left: 0px;
}
& .owl-carousel .owl-dot, 
& .owl-carousel .owl-nav .owl-next, 
& .owl-carousel .owl-nav .owl-prev {
    cursor: pointer;
    cursor: hand;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}


& .owl-buttons > div:before, 
& .owl-nav > div:before,
& .owl-nav > button:before {
    display: block;
    font-family: FontAwesome;
    font-size: 30px;
    line-height: 30px;
    position: absolute;
    left: 0;
    top: 0;
    text-align: center;
    width: 100%;
}
& .activtiy_list .owl-buttons > div::before, 
& .owl-nav > div::before,
& .owl-nav > button:before {
    font-size: 22px;
    background: #ffd800;
    border-radius: 4px 0px 0px 4px;
    line-height: 34px;
    width: 36px;
    height: 36px;
    color: #000;
}
& .activtiy_list .owl-buttons > div::before,
& .owl-nav > div::before,
& .owl-nav > button:before,
& .owl-buttons > div::before,
& .testimonial_slider .owl-nav > div:before {
    font-size: 0;
    border-radius: 50%;
    line-height: 34px;
    width: 36px;
    height: 36px;
    color: #000;
    border: 1px solid #d1cdcb;
    content: "";
    background-image: url(../assets/andamanisland/images/arrows.png);
    background-position: 9px -29px;
    display: inline-block;
    background-repeat: no-repeat;
    vertical-align: middle;
    background-color: #fff;
}
& .owl-buttons > .owl-prev:before, 
& .owl-nav > .owl-prev:before {
    content: '\f104';
}
& .owl-buttons > .owl-next:before, 
& .owl-nav > .owl-next:before {
    content: '\f105';
}
/* 25 sep 2023 end */
@media (max-width: 992px){
    
}
@media (max-width: 767px){
& .inner_content_block .img-data li .img-sec {
    width: 100%;
}
& .inner_content_block .img-data li .para-sec {
    width: 100%;
    padding-left: 0;
}
& .cms_content .img-data li .img-sec {
  width: 100%;
}
& .cms_content .img-data li .para-sec {
  width: 100%;
  padding:1rem;
}
& .firstcontent {
    width: 100%;
    padding: 1.125rem;
}
& .firstimg {
    width: 100%;
}
& .contentsecond {
    width: 100%;
    padding: 1.125rem;
}
& .imgsecond {
    width: 100%;
    height: 28.125rem;
}
& .halfleft {
    width: 100%;
    padding: 1.125rem;
}
.halfright {
    width: 100%;
    padding: 1.125rem;
}
& .leftslide {
    width: 100%;
}
& .destitext {
    width: 100%;
    padding-left: 0;
    padding-top: 1rem;
}
& .destimg {
    width: 100%;
}
& .destcontent {
    width: 100%;
    padding: 1.125rem 1rem;
    min-height: auto;
}
& .hleftcont {
    width: 100%;
}
& .hrightcont {
    width:100%;
    padding-left: 0;
}
& .fullimgtext {
    width: 100%;
    position: unset;
    padding: 1rem 0;
    ::before{
     position: inherit;
    }
}
& .halefimg {
    width: 100%;
}
& .halfwith {
    width: 100%;
    padding-left: 0;
    padding-right: 0;
}
& .detail_img {
    width: 100%;
}
& .page_template_default .detail_info {
    width: 100%;
}
& .secitonslist .img-sec {
    height: 100%;
}
& .fullimg {
    height: 100%;
}
& .clist li .cimg {
    height: 100%;;
}
& .rcont .destimg {
    height: 100%;
    min-height: auto;
}
& .fullimg {
    height: 100%;
}
& .fullwidth {
    display: block;
    padding-top: 0;
}
& .halefimg .dimg1 {
    width: 100%;
    overflow: hidden;
    border-radius: 0;
    height: 100%;
}
& .halefimg .dimg2 {
    position: unset;
    width: 100%;
    border-radius: 0;
    height: 100%;
    margin-top: 1rem;
}
}


& .fotogallery .gall_cms{
    margin: 0 -1rem;
    a{
        padding:0 1rem;width: calc(100% /4);margin-bottom:2rem;height: 25rem;
        img{height:100%;width:100%;object-fit:cover;}
    }
}
& .float_img {
    float: right;
    width: 32%;
    margin-left: 1rem;
}


    @media (max-width: 767px){
        & .fotogallery .gall_cms{
            margin: 0;
            a{padding:0;width: 100%;margin-bottom:2rem;}
        }
        & .float_img {
            float: none;
            width: 100%;
            margin-bottom: 1rem;
        }
    }

    
`