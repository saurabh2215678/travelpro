<template>
    <section class="content-section not_found_page">
        <div class="xl:container xl:mx-auto text-center">
            <h3 class="font-semibold">{{ title }}</h3>
            <div class="py-3">{{ description }}</div>
            <Link href="/" class="btn theme_clr text-sm rounded-full" style="display:inline-block;">Go to Home</Link>
        </div>
    </section>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { computed } from 'vue'
import Layout from './components/layout.vue';
export default {
    name: 'Error',
    created() {
        const title = computed(() => {
            return {
                503: '503: Service Unavailable',
                500: '500: Server Error',
                404: '404: Page Not Found',
                403: '403: Forbidden',
            }[this.status]
        });
        this.title = title;
        const description = computed(() => {
            return {
                503: 'Sorry, we are doing some maintenance. Please check back soon.',
                500: 'Whoops, something went wrong on our servers.',
                404: 'Sorry, the page you are looking for could not be found.',
                403: 'Sorry, you are forbidden from accessing this page.',
            }[this.status]
        });
        this.description = description;
    },
    data() {
        return {
            title:'',
            description:''            
        }
    },
    methods: {

    },
    mounted() {

    },
    beforeUnmount() {

    },
    components: {
        Link
    },
    layout: Layout,
    props: ["status"]
};
</script>
