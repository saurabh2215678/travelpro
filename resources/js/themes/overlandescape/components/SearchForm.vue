<template>
  <search-wrapper class="flight-banner searchBook_form">
    <div class="flight_page">
      <div class="xl:container xl:mx-auto mx-auto">
        <div class="head-search">
          <FormTopMenu :activeForm="activeForm" />
          <ActivitySearchForm v-if="activeForm == 'activity'" />
          <HotelSearchForm v-else-if="activeForm == 'hotel'" />
          <RentalSearchForm v-else-if="activeForm == 'rental'" />
          <PackageSearchForm v-else />
        </div>
      </div>
    </div>
  </search-wrapper>
</template>

<script>
import {hidePopup} from '../utils/commonFuntions';
import { store } from '../store.js';
import FormTopMenu from './FormTopMenu.vue';
import ActivitySearchForm from './activity/ActivitySearchForm.vue';
import HotelSearchForm from './hotel/HotelSearchForm.vue';
import PackageSearchForm from './package/PackageSearchForm.vue';
import RentalSearchForm from './rental/RentalSearchForm.vue';
import styled from 'vue3-styled-components';

const SearchWrapper = styled.div`

`;

export default {

  name:"popup",
  created() {
    // console.log('store', store.popupActive);
  },
  data() {
    return {
      store,
      activeForm: this.type // 'package' | 'activity' | 'hotel'
    }
  },
  methods:{
    closeClick(){
      hidePopup();            
    },
    handleFormSubmit(e){
      e.preventDefault();
      this.isSearched = true;
      var form_data =  {
        filter_tour_type: this.filterTourType,
        categories: this.categoriesArr,
        filter_package_budget: this.filterPackageBudget,
        filter_month: this.filterMonth,
        text: this.textKey,
        sno_of_days: this.snoOfDays,
        smonth: this.sMonth,
      } 
      this.loading = true;
      this.$inertia.get(`/search-packages`, form_data);
    }
  },
  components: {
    FormTopMenu,
    ActivitySearchForm,
    HotelSearchForm,
    PackageSearchForm,
    RentalSearchForm,
    'search-wrapper' : SearchWrapper
  },
  props: ["className", "type"],
}
</script>