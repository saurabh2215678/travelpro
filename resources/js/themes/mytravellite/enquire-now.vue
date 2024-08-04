<template>
      <SearchFormWithBanner
      title="Search For Place"
      />
      <FormSectionWrapper class="theme_form">
         <div class="container">
            <div class="theme_form_wrap rounded-lg">
                  <div class="theme_form_inner">
                     <h1 class="text-3xl font-semibold pb-2 px-2">
                        Enquire This Trip
                      
                            <Link :href="package.url" class="go_back float-right">
                              <span class="arrow">
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M9.375 233.4l128-128c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H480c17.69 0 32 14.31 32 32s-14.31 32-32 32H109.3l73.38 73.38c12.5 12.5 12.5 32.75 0 45.25c-12.49 12.49-32.74 12.51-45.25 0l-128-128C-3.125 266.1-3.125 245.9 9.375 233.4z"/>
                                 </svg>
                              </span>
                              Back
                        </Link>
                     </h1>
                     <div class="text-lg font700 px-2"> {{package.title}}

                        <span class="text-xs text-teal-500 font700 px-2">{{package.package_duration}}</span>
                     </div>
                     <span class="package_tour_type_text px-2 p-1 ml-2 mb-2 bg-orange-700 rounded">{{type}}</span>
                   
                    <formShortCode slug="[customize_your_trip]" class="form_list" :hidden="{'package':this.package.id}" />
                   
                  </div>
            </div>
         </div>
      </FormSectionWrapper>
</template>

<script>
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
// https://www.npmjs.com/package/vue-toast-notification
import formShortCode from './components/formShortCode.vue';

import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";
import SearchFormWithBanner from './components/SearchFormWithBanner.vue';
import styled from 'vue3-styled-components';

const FormSectionWrapper = styled.section`
padding: 2.5rem 0;
background-color:#f7f6f9;
& .go_back {
    font-size: 1.2rem;
    display: flex;
    & span{
      height: 2.375rem;
      width: 2.375rem;
      margin-right: 0.9rem;
      background-color: var(--theme-color);
      border-radius: 5rem;
      & svg{
         transform: translate(12px,12px);
         fill: var(--white);
         height: 0.938rem;
         width: 0.938rem;
      }
    }
}
& .form-floating .form-control::placeholder{
   color: #222;
   font-size: .8rem;
   font-weight: 400;
}
& .theme_form_wrap {
    background-color: var(--white);
    max-width: 55.625rem;
    margin: 0 auto;
    & .theme_form_inner{
      padding: 1.5rem 1.75rem;
    }
}
& .package_tour_type_text{
   color: var(--white);
   font-size: .7rem;
   margin: 0 0.5rem 1rem;
}
& .form-floating{
   min-width: 50%;
   & label{
      font-size: 0.85rem;
      font-weight: 600;
   }
}
` 


const $toast = useToast();

export default {
    created() {
      document.body.classList.add('enquire-now');
    },
    beforeUnmount() {
      document.body.classList.remove('enquire-now');
   },
    data() {
        return {
            test : "test",
            store,
        }
    },
    methods: {
        showToast(toastObj){
            $toast.open(toastObj);
        }
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
    Link,
    formShortCode,
    SearchFormWithBanner,
    FormSectionWrapper
},
    layout: Layout,
    props: ["package","type"],
};
</script>