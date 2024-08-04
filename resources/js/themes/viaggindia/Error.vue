<template>
    <section class="content-section not_found_page">
        <div class="xl:container xl:mx-auto text-center">
            <h3 class="font-semibold">{{ title }}</h3>
            <div class="py-3">{{ description }}</div>
            <Link href="/" class="btn theme_clr text-sm rounded-full" style="display:inline-block;">Go to Home</Link>
        </div>
    </section>
</template>

<style>
.not_found_page {
  height: auto;
  min-height: calc(100vh - 398px);
  padding: 4rem 0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.not_found_page h3 {
  padding: 0;
  margin: 0 0 2rem 0;
  font-size: 2.5rem;
  color: var(--theme-color);
  text-shadow: 2px 3px 4px #333;
  font-weight: 700;
  margin-bottom: 0;
  text-shadow: 2px 3px 4px #3333337a;
}
.btn {
    background-color: var(--theme-color);
    border: 1px solid transparent;
    border-radius: 5px;
    color: var(--white);
    font-size: 1rem;
    line-height: 1;
    padding: .4rem 1.2rem .5rem;
    transition: all .5s ease;
}
</style>
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
