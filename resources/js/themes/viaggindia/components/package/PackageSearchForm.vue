<template>
  <HomePageForm 
  v-if="isHomePage" 
  :validateSearchPackageForm="validateSearchPackageForm"
  :searchPackages="searchPackages"
  :loadPackages="loadPackages"
   />
  <SearchFormWrapper v-else :action="store.websiteSettings?.PACKAGE_URL" method="GET" name="searchForm" class="package_form" id="search_packages_form" @submit="validateSearchPackageForm">
       <h3 class="text-lg font-bold pb-1 pt-1">Search Holiday Packages</h3>
       <div class="flex">

        <div class="option_box_wrapper">
          <div class="option_box">
            <div class="selectoption md:w-1/2 max-md:w-full">
              <i class="fa-solid fa-location-dot"></i>
              <input type="text" name="text" class="form-control" :value="store.searchData?.text" id="search_packages" autocomplete="off" placeholder="Search for a place or package" v-on:keyup="searchPackages($event)" @click="loadPackages('')">
              <div class="search_packages">
                  <ul id="search_activities_ul">
                  </ul>
              </div>
            </div>
            <div class="selectoption md:w-1/3 max-md:w-full">
              <i class="fa-regular fa-hourglass-half"></i>
              <select name="sno_of_days" >
                  <option value="">Number of days</option>
                  <template v-for="val,key in store.websiteSettings?.noOfDays">
                    <option :value="key" :selected="store.searchData?.sno_of_days == key" >{{val}}</option>
                  </template>
              </select>
            </div>
            <div class="selectoption md:w-1/3 max-md:w-full">
              <i class="fa-regular fa-calendar"></i>
              <select name="smonth">
                  <option value="">Select Month</option>
                  <template v-for="val,key in store.websiteSettings?.months">
                    <option :value="key" :selected="store.searchData?.smonth == key" >{{val}}</option>
                  </template>
                  <option value="not_decision">Not decided</option>
              </select>
            </div>
          </div>
        </div>

          <div class="searchbtn">
             <input type="hidden" name="slug" :value="store.searchData?.search_slug">
             <input type="submit" class="btn btn-primary" value="Explore">
          </div>
       </div>
  </SearchFormWrapper>
</template>

<script>
import { store } from '../../store.js';
import axios from "axios";
import HomePageForm from '../home/HomePageForm.vue';
import { SearchFormWrapper } from '../../styles/SearchFormWrapper';

export default {
  name:'PackageSearchForm',
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
        var search_url = store.websiteSettings.PACKAGE_URL.replace(store.websiteSettings.FRONTEND_URL,'');
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
          })  
          .then(function (resp) {
            let response = resp.data
            $('#search_activities_ul').empty();
            if(response.success) {
              if(response.result) {
                $.each(response.result, function(index,row){
                  var row_li = '<li data-slug="'+row.slug+'">'+row.title+'</li>'
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
        }
  },
  components:{
      SearchFormWrapper,
      HomePageForm
  },
  props: ['isHomePage'],
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