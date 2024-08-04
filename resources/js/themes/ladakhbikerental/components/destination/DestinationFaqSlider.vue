<template>
	<faqWrapper class="secpad inner-page faq faq-cms-block" v-if="faqs">
		<div class="container">
			<div class="theme_title">
				<div class="font30 color_theme fw700 mb-8">FAQs About {{destination.name}}</div>
			</div>
			<div class="faqlist">
				<ul>
					<template v-for="row,key in faqs">
						<li class="faq_main">
							<div class="faq_title font16"><span><strong>Q{{key+1}}. </strong></span>{{row.question}} </div>
							<div class="faq_data" style="display: none;">
								<div class="font16" v-html="row.answer"></div>
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
		/*background-color: var(--theme-color60);*/
		border: 1px solid #ccc;
    	border-bottom: 0;
		&:last-child{
			border-bottom : 1px solid #ccc;
		}
		& .faq_title {
			padding: 0.8rem 1.5rem;
			display: flex;
			align-items: center;
			cursor: pointer;
			font-size: 1rem;
			font-weight: 600;
			position:relative;
			// border: 1px solid #ccc;
			// border-bottom: 0;
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
		& .faq_title:after {
			content: "";
			color: #000;
			transition: .3s;
			position: absolute;
			display: inline-block;
			right:1rem;
			margin-top: 5px;
			border-left: 6px solid transparent;
			border-right: 6px solid transparent;
			border-top: 12px solid #555;
			height: 10px;
			width: 10px;
		}
		& .faq_title.active:after{transform: rotate(58deg);}
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
    },
    components:{
		faqWrapper
    },
    props: ["destination","faqs","viewAllUrl"],
}
</script>