<template>
    <div class="pagination-wrapper" >
        <nav v-html="this.links" ref="navref"></nav>
    </div>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
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
                var firstChar = href[0];
                if(firstChar == '/') {
                    this.$inertia.get(`${href}`);
                } else {
                    this.$inertia.get(`/${href}`);
                }
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
                    let navRef = currentObj.$refs.navref;
                    if(navRef) {
                        var allPageLinks = navRef.querySelectorAll('.page-link');
                        for (var i = 0; i < allPageLinks.length; i++) {
                            allPageLinks[i].addEventListener('click', this.handlePaginationLink);
                        }
                    }
                }, 500);
            }
        }
    },
    components:{
        Link
    },
    props:["links"]
}
</script>