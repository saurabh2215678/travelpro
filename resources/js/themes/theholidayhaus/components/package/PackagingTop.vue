<template>
    <PackagingTopWrapper class="packaging_top mb-5">
        <div class="title">Showing {{this.total_count}} Packages For You</div>
        <div class="sort_by">
            <div class="custom_select">
                <i class="fa-solid fa-chevron-down"></i>
                <select class="form_control" name="sort_by_price" id="sort_by_price" @change="handleOnChange($event)">
                    <option value="">Sort By Price :</option>
                    <option value="lth" :selected="this.checkedFunction('sort_by_price','lth')">Low To High</option>
                    <option value="htl" :selected="this.checkedFunction('sort_by_price','htl')">High To Low</option>
                </select>
            </div>
        </div>
    </PackagingTopWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const PackagingTopWrapper = styled.div`
display: flex;
align-items: center;
justify-content: space-between;
& .title{
    color: var(--theme-color800);
    font-weight: 500;
}
& .form_control{
    border: 1px solid var(--black200);
    border-radius: 7px;
    padding: 0.3rem 1.2rem;
    padding-left: 0.7rem;
    cursor: pointer;
    appearance: none;
    font-size: 0.86rem;
    :focus{
        outline:none;
        border-color: var(--theme-color);
    }
}
& .custom_select {
    position: relative;
    & i {
        position: absolute;
        pointer-events: none;
        top: 10px;
        font-size: 0.84rem;
        right: 6px;
    }
}
`

export default {
    name:'PackagingTop',
    components:{
        PackagingTopWrapper
    },
    props: ["total_count", "handleOnChange", "checkedFunction"],
}
</script>