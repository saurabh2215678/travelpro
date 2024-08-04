<template>
	<faqWrapper class="secpad inner-page faq faq-cms-block" v-if="faqs">
		<div class="container">
			<div class="theme_title mb-6">
				<div class="top_title">{{showLangTitle('FAQsAbout')}} {{destination.name}}</div>
			</div>
			<div class="faqlist">
				<ul>
					<template v-for="row,key in faqs">
						<li class="faq_main">
							<div class="faq_title text-lg"><span><strong>Q{{key+1}}. </strong></span>{{row.question}} <i class="fa-solid fa-chevron-right"></i></div>
							<div class="faq_data" style="display: none;">
								<p v-html="row.answer"></p>
							</div>
						</li>
					</template>
				</ul>
			</div>
		</div>
	</faqWrapper>
</template>
<script>
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import {showLangTitle} from '../../utils/commonFuntions';

const faqWrapper = styled.section`
	padding-top: 3rem;
	padding-bottom:3rem;
	& .top_title {
		font-size: 1.875rem;
		font-weight: 700;
		/* font-family: 'PT Serif',serif;
		color: var(--theme-color);
		text-transform: uppercase; */
		margin-bottom: 1.8rem;
		&:after{
			content: "";
			display: block;
			background-color: var(--secondary-color);
			height: 3px;
			width: 40px;
		}
	}
	& .faq_main {
		background-color: var(--theme-color60);
    	border-bottom: 1px dashed var(--black90);
		&:last-child{
			border-bottom : 0;
		}
		& .faq_title {
			padding: 0.8rem 1.5rem;
			display: flex;
			align-items: center;
			cursor: pointer;
			font-size: 1.1rem;
			font-weight: 600;
			&.active{
				color: var(--theme-color);
			}
			& span {
				margin-right: 0.65rem;
				color: var(--theme-color);
			}
			& i{
				margin-left: auto;
				transition: 0.5s;
			}
			&.active i{
				transform: rotate(90deg);
			}
		}
		& .faq_data {
			padding: 1rem 1.5rem;
			padding-top: 0.2rem;
		}
	}
`


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
     showLangTitle,
    },
    components:{
		faqWrapper
    },
    props: ["destination","faqs","viewAllUrl"],
}
</script>