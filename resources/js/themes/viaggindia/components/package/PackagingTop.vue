<template>
    <PackagingTopWrapper class="packaging_top">
        <div class="title">{{showLangTitle('Showing')}} {{this.total_count}} {{showLangTitle('PackagesForYou')}}</div>
        <div class="sort_by">
            <div class="custom_select">
                <i class="fa-solid fa-chevron-down"></i>
                <select class="form_control" name="sort_by_price" id="sort_by_price" @change="handleOnChange($event)">
                    <option value="">{{showLangTitle('SortByPrice')}} :</option>
                    <option value="lth" :selected="this.checkedFunction('sort_by_price','lth')">{{showLangTitle('LowToHigh')}}</option>
                    <option value="htl" :selected="this.checkedFunction('sort_by_price','htl')">{{showLangTitle('HighToLow')}}</option>
                </select>
            </div>
        </div>
    </PackagingTopWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import {showLangTitle} from '../../utils/commonFuntions.js';

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
    methods: {
    showLangTitle,
    },
    components:{
        PackagingTopWrapper
    },
    props: ["total_count", "handleOnChange", "checkedFunction"],
}
</script>