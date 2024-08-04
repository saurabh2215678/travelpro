<template>
    <section class="home_client bggray home_featured">
        <div class="xl:container xl:mx-auto">
            <div class="text_center ">
                <div class="theme_title mb-6">
                    <div class="title text-2xl">Meet Our Team</div>
                    <div class="view_all_btn"><a :href="store.websiteSettings?.TEAM_URL">View All</a></div>
                </div>
                <div class="p_relative">
                    <div class="slider_box">
                    <div class="swiper client_slider">
                        <div class="swiper-wrapper">
                            
                            <div class="swiper-slide" v-for="team in teams">
                                <div class="images">
                                <img
                                    :src="team.teamSrc"
                                    :alt="team.title" />
                                </div>
                                <div class="team_info">
                                <div class="text py-4 px-2">
                                    <span class="title leading-5"> {{team.title}}</span>
                                    <div class="designation para_md1 text-xs"> {{team.sub_title}} </div>
                                    <div class="designation para_md1 text-sm font-bold">{{team.designation}}</div>
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    </div>
                    <div class="slider_btns">
                    <div class="client-prev theme-prev"><img
                        src="../assets/images/next.png" alt="Next Icon">
                    </div>
                    <div class="client-next theme-next"><img
                        src="../assets/images/prev.png" alt="Prev Icon">
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
import { store } from '../store.js';
import { Link } from "@inertiajs/inertia-vue3";

export default {
    name:'OurTeamSlider',
    created() {
    },
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    components: {
      
        Link
    },
    props: ["teams"],
}
</script>