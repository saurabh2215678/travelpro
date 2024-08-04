<template>
    <PaginationWrapper class="pagination-wrapper" >
        <nav v-html="this.links" ref="navref"></nav>
    </PaginationWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
import styled from "vue3-styled-components";

const PaginationWrapper = styled.div`
    display : inline-flex;
    & .pagination{
        display:flex;
        border: 1px solid #b7b7b7;
        border-radius: 6px;
        & .page-link{
            padding: 0.5rem 0.8rem;
            line-height: 1;
            border-right: 1px solid #b7b7b7;
            &:hover{
                background-color: var(--theme-color100);
            }
        }
        & a.page-link{
            cursor: pointer;
        }
        & .page-item {
            &.active .page-link{
                background-color: var(--theme-color);
                color: var(--white);
            }
            &:last-child .page-link{
                border-right: 0;
            }
        }
    }
`

export default {
    name:"Pagination",
    created() {
        this.paginationLinks = this.links
    },
    data() {
        return {
            store,
            paginationLinks: false
        }
    },
    methods: {
        handlePaginationLink(e) {
            e.preventDefault();
            var element = e.target;
            var href = element.getAttribute('href');
            if(href) {
                this.$inertia.get(`/${href}`);
            }
        },
    },
    mounted() {

    },
    watch:{
        paginationLinks : {
            handler: function(paginationLinks) {
                let currentObj = this;
                setTimeout(()=>{                   
                    var allPageLinks = currentObj.$refs.navref.querySelectorAll('.page-link');
                    for (var i = 0; i < allPageLinks.length; i++) {
                        allPageLinks[i].addEventListener('click', this.handlePaginationLink);
                    }
                }, 500);
            }
        }
    },
    components:{
        Link,
        PaginationWrapper
    },
    props:["links"]
}
</script>