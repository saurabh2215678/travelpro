<template>
    <form-wrapper :action="store.websiteSettings?.ACTIVITY_URL" method="GET" name="searchForm" class="" id="search_packages_form" @submit="validateSearchPackageForm" >
        <h3 class="text-lg font-bold pb-1 pt-1">Search Activities</h3>
        <div class="flex gap-2">
            <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
                <label>Where To?</label>
                <i class="mapicon"></i>
                <input type="text" name="text" class="form-control" :value="store.searchData?.text" id="search_packages" autocomplete="off" placeholder="Search for a place or activity" v-on:keyup="searchPackages($event)" @click="loadPackages('')" >
                <div class="search_activities">
                    <ul id="search_activities_ul"></ul>
                </div>
            </div>
            <div class="selectoption date_box pr-0.5 md:w-1/2 max-md:w-full">
                <label>When?</label>
                <DatePicker columns="2" v-model="dateModal" mode="date" class="date_wrap" :min-date="this.departureMinDate" >
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        name="dep"
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="DD/MM/YYYY"
                        autocomplete="off"
                        />
                    </template>
                </DatePicker>
                <!-- <input type="text" name="dep" class="form-control datepicker" :value="store.searchData?.dep" autocomplete="off" placeholder="DD/MM/YYYY"> -->
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <div class="searchbtn mt-6">
                <input type="hidden" name="slug" :value="store.searchData?.search_slug">
                <input type="submit" class="btn btn-primary p-2 pl-4 pr-3 h-auto" value="Search"> 
            </div>
        </div>
    </form-wrapper>

</template>

<script>
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import axios from "axios";
import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';


const FormWrapper = styled.form`
position: relative;
z-index: 99;
& .form-control.datepicker{
    padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 0.5rem;
}
& .date_input{
  border: 1px solid rgb(204, 204, 204); 
  padding: 0.35rem 0.9rem; 
  border-radius: 4px;
}
@media (max-width: 767px){
  & .mapicon {
    top: 0.8rem;
  }
}
`;

export default {
    name:'ActivitySearchForm',
    created() {

    },
    mounted(){
    },
    data() {
        return {
            store,
            dateModal: '',
            departureMinDate : new Date(),
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
        'form-wrapper' : FormWrapper,
        SetupCalendar,
        Calendar,
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