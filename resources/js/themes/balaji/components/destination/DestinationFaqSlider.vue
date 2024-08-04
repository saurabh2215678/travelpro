<template>
	<div class="secpad inner-page faq faq-cms-block" v-if="faqs">
		<div class="xl:container xl:mx-auto">
			<div class="theme_title mb-6">
				<div class="title text-2xl">FAQs About {{destination.name}}</div>
			</div>
			<div class="faqlist">
				<ul>
					<template v-for="row,key in faqs">
						<li class="faq_main">
							<div>
								<div class="faq_title text-lg"><span><strong>Q{{key+1}}. </strong></span>{{row.question}}</div>
								<div class="faq_data" style="">
									<p v-html="row.answer"></p>
								</div>
							</div>
						</li>
					</template>
				</ul>
			</div>
		</div>
	</div>
</template>
<script>
import { store } from '../../store.js';
export default {
    name:'DestinationFaqSlider',
    created() {

    },
    mounted(){

    },
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    components:{

    },
    props: ["destination","faqs","viewAllUrl"],
}
</script>