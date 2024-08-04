<template>
  <SearchFormWrapper :action="store.websiteSettings?.ACTIVITY_URL" method="GET" name="searchForm" class="activity_form" id="search_packages_form" @submit="validateSearchPackageForm" >
      <h3 class="text-lg font-bold pb-1 pt-1">Search Activities</h3>
      <div class="flex">
          <div class="selectoption md:w-1/2 max-md:w-full">
              <!-- <label>Where To?</label> -->
              <i class="fa-solid fa-location-dot"></i>
              <input type="text" name="text" class="form-control" ref="searchInputRef" id="search_packages" autocomplete="off" placeholder="Search for a place or activity" v-on:keyup="searchPackages($event)" @click="loadPackages('')" >
              <SearchActivityDropdownWrapper class="search_activities">
                  <ul id="search_activities_ul"></ul>
              </SearchActivityDropdownWrapper>
          </div>
          <div class="selectoption date_box md:w-1/2 max-md:w-full">
              <!-- <label>When?</label> -->
              <i class="fa-regular fa-calendar"></i>
              <DatePicker :columns="datePickerColumn" v-model="dateModal" mode="date" class="date_wrap w-full" :min-date="this.departureMinDate" >
                  <template v-slot="{ inputValue, inputEvents, togglePopover }">
                      <input
                      name="dep"
                      class="date_input w-full"
                      :value="inputValue"
                      @click="togglePopover"
                      placeholder="DD/MM/YYYY"
                      autocomplete="off"
                      />
                  </template>
              </DatePicker>
          </div>
          <div class="searchbtn">
              <input type="hidden" name="slug" :value="store.searchData?.search_slug">
              <input type="submit" class="btn btn-primary p-2 pl-4 pr-3 h-auto" value="Search"> 
          </div>
      </div>
  </SearchFormWrapper>

</template>

<script>
import { store } from '../../store.js';
import axios from "axios";
import { DatePicker } from 'v-calendar';
import { SearchFormWrapper } from '../../styles/SearchFormWrapper';
import { SearchActivityDropdownWrapper } from '../../styles/SearchActivityDropdownWrapper';


export default {
  name:'ActivitySearchForm',
  data() {
      return {
          store,
          dateModal: '',
          departureMinDate : new Date(),
          datePickerColumn: 2
      }
  },
  mounted(){
    if(this.store.searchData?.text){
      this.$refs.searchInputRef.value = this.store.searchData?.text;
    }
    this.setDatePickerRowAndColumn();
    window.addEventListener('resize', this.setDatePickerRowAndColumn);
  },
  methods:{
    validateSearchPackageForm(e) {
      e.preventDefault();
      let currentObj = this;
      var search = true;
      $('#search_activities_ul li').each(function(){
        if( $(this).hasClass('active')) {
          search = false;
          $(this).trigger('click');
        }
      });
      if(search) {
        if($('#search_packages_form input[name=slug]').val() == '') {
          $('#search_packages_form input[name=slug]').attr('disabled',true);
        }
        let query_string = $('#search_packages_form').serialize();
        var search_url = store.websiteSettings.ACTIVITY_URL.replace(store.websiteSettings.FRONTEND_URL,'');
        currentObj.$inertia.get(`${search_url}?${query_string}`);
      } else {
        return search;
      }
    },
    searchPackages(event) {
      var search = true;
      var li_count = $('#search_activities_ul li').length;
      var curent_active = -1;
      var each_counter = 0;
      $('#search_activities_ul li').each(function(){
        if( $(this).hasClass('active')){
          curent_active = each_counter;
        }
        each_counter++;
      });

      switch (event.keyCode) {
        case 37:
            // alert('Left key');
            curent_active-=1;
            search = false;
            break;
            case 38:
            // alert('Up key');
            curent_active-=1;
            search = false;
            break;
            case 39:
            // alert('Right key');
            curent_active+=1;
            search = false;
            break;
            case 40:
            // alert('Down key');
            curent_active+=1;
            search = false;
            break;
            case 13:
            // alert('Enter key');
            search = false;
            break;
          }


          if(curent_active==li_count){
            curent_active = 0;
          }

          $('#search_activities_ul li').removeClass('active');
          $('#search_activities_ul li').eq(curent_active).addClass('active');

          if(search) {
            $('#search_activities_ul').hide();
            $('#search_activities_ul').empty();
            $('#search_packages_form input[name=slug]').val('');
            $('#search_packages_form input[name=slug]').attr('disabled',true);

            var text = event.target.value;
            if(text.length > 2) {
              this.loadPackages(text);
            }
          }    
    },
    loadPackages(text) {
      var currentObj = this;
      axios.post(store.websiteSettings?.ajaxSearchPackages, {
        text: text,
        is_activity: 1
      })  
      .then(function (resp) {  
        let response = resp.data
        $('#search_activities_ul').empty();
        if(response.success) {
          if(response.result) {
            $.each(response.result, function(index,row){
              var row_li = '<li data-slug="'+row.slug+'"> <i class="fa-solid fa-person-running"></i>'+row.title+'</li>'
              $('#search_activities_ul').append(row_li);
            });
            $('#search_activities_ul').show();
          }
        } else if(response.message) {
          // console.log('false');
        } else {
          // console.log('error');
        }
      })  
      .catch(function (error) {
        let response = error.response.data;
      });
    },
    setDatePickerRowAndColumn(){
      const windowWidth = window.innerWidth
      if(windowWidth <= 767){
        this.datePickerColumn = 1;
      }else{
        this.datePickerColumn = 2;
      }
    }
  },
  components:{
      SearchFormWrapper,
      SearchActivityDropdownWrapper,
      DatePicker,
  },
  props: [],
}
$(document).on('click','#search_activities_ul li',function(){
    var slug = $(this).attr('data-slug');
    var title = $(this).text();
    $('#search_activities_ul').hide();
    $('#search_activities_ul').empty();
    $('#search_packages').val(title);
    $('#search_packages_form input[name=slug]').val(slug);
    $('#search_packages_form input[name=slug]').attr('disabled',false);
  });
</script>