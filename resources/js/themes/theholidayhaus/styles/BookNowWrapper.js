import styled from 'vue3-styled-components';

export const BookNowWrapper = styled.section`
    background: #f7f6f9;
    margin-top: 0;
    padding-top: 3rem;
    padding-bottom: 3rem;
& .left_sec.logincard{
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
    margin: 1rem;
    padding: 1.2rem;
}
& .left_sec{
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
    margin: 1rem;
    padding: 1.2rem;
}
& .form_price {
    display: flex;
    justify-content: space-between;
}
& .right_sec{
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
    margin: 1rem;
    padding: 1.2rem;
}
& .popup-booking .form_price .left_sec .title, & .popup-booking .form_price .right_sec .title {
    border-bottom: 1px solid #ddd;
    padding-bottom: 0.5rem;
    margin-bottom: 0.8rem;
    position: relative;
}
& .popup-booking .form_price .left_sec .title:before, & .popup-booking .form_price .right_sec .title:before {
    border-bottom: 1px solid var(--theme-color);
    bottom: -0.1rem;
    content: "";
    padding-bottom: 0.5rem;
    position: absolute;
    width: 5rem;
}
& .popup-booking .form_price .logincard ul li span {
    color: #db862b;
    font-size: .9rem;
    font-weight: 500;
}
& .popup-booking .form_group.phone_code .form-select.country_code {
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    font-size: .8rem;
    height: calc(0.6em + 0.75rem + 0.7rem);
    margin-right: 5px;
    padding: 0.25rem 0.5rem;
    width: 30%;
}
& .popup-booking .form_group.phone_code input {
    width: 65%;
}
& .popup-booking .form_group.phone_code label {
    flex: 1;
    min-width: 100%;
}
.validation_error:empty {
    display: none;
}
& .primary-btn, .secondary-btn:hover {
    background: var(--theme-color);
    color: #fff;
}
& .primary-btn {
    border-radius: 5rem;
    font-size: .8rem;
    line-height: normal;
    padding: 0.5rem 2rem;
}
& .primary-btn:hover {
    background: var(--secondary-color);
    color: #fff;
}
& .popup-booking .form_group input, & .popup-booking .form_group textarea {
    background: #fff;
    outline:0;
}
& .form-control:focus, & .form-floating .form-control:focus, & .form_control:focus {
    border: 1px solid var(--theme-color);
}
& .phone_code {
    display: flex;
    align-items: end;
    flex-wrap: wrap;
    justify-content: space-between;
}
& .img-pack {
    align-items: center;
    display: flex;
}
& .img-pack img {
    width: 8rem;
    float: left;
    padding-right: 1.563rem;
}
& .popup-booking .book_info_list {
    font-size: 0.8rem;
    margin-top: 0;
    display: flex;
    padding: 0.5rem 0;
    width: 100%;
    align-content: center;
    align-items: center;
}
& .popup-booking .book_info_list>span {
    color: #464646;
    display: flex;
    justify-content: space-between;
}
& .book_info_list>span {
    width: 60%;
}
& .popup-booking .book_info_list>span em {
    font-style: inherit;
    padding-right: 0.5rem;
}
& .popup-booking li:last-child.book_info_list span {
    font-weight: 600;
}
& .popup-booking li:last-child.book_info_list .bold {
    color: var(--theme-color);
    font-size: 1rem;
}


@media (max-width: 767px){
/* padding-top: 3rem; */
&.allbooking_form form#book_now_form .form_price {
    display: block;
}  
&.allbooking_form form#book_now_form .form_price .left_form, &.allbooking_form form#book_now_form .form_price .right_sec {
    padding-right: 0;
    width: 100%;
} 
&.allbooking_form form#book_now_form .form_price .right_sec {
    width: 92%;
}
&.allbooking_form form#book_now_form .form_price .form_list .w-1\/2 {
    width: 100%;
}
&.allbooking_form form#book_now_form .form_price .form_list .w-1\/4 {
    width: 50%;
}
&.allbooking_form form#book_now_form .form_price .form_list .w2.flex {
    flex-wrap: wrap;
}
}
`;