    <template>
        <section class="related-hotel" style="background:#fff;" v-if="this.relatedHotels && this.relatedHotels.length > 0">
            <div class="xl:container xl:mx-auto">
                <div class="text_center ml-2">
                    <div class="theme_title mb-6">
                        <div class="title text-2xl">Other Hotels in {{destination}}</div>
                    </div>
                </div>
                <ul class="featured_list flex flex-wrap">
                    <li class="w-64 border-transparent mt-0 m-2" v-for="relatedaccommodation in this.relatedHotels">
                        <Link class="featured_box" :href="relatedaccommodation.url">
                            <div class="images">
                                <img :src="relatedaccommodation.thumbSrc" :alt="relatedaccommodation.name" />
                            </div>
                            <div class="featured_content px-3.5 py-3.5">
                                <div class="title text-lg">{{relatedaccommodation.name}}</div>
                                <p class="text-xs"  v-html="relatedaccommodation.brief"></p>
                                
                            </div>
                        </Link>
                    </li>

                </ul>
            </div>
        </section>
    </template>
<script>
import { store } from '../../store.js';
import axios from "axios";
import { Link } from "@inertiajs/inertia-vue3";
export default {
    created() {
        this.relatedHotels = this.relatedAccommodations;
    },  
    data() {
        return {
            store,
            relatedHotels:[],
        }
    },
    methods:{
        },
    components: {
        Link
     },
props: ["relatedAccommodations","accommodation", "inventory", "adult", "child", "infant", "checkin", "checkout","destination"],
}
</script>