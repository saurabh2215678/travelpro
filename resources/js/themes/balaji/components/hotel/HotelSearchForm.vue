<template>
<form-wrapper :action="store.websiteSettings?.HOTEL_URL" method="GET" name="searchForm" class="" id="search_hotels_form" @submit="validateSearchHotelForm" >
   <h3 class="text-lg font-bold pb-1 pt-1">Search Hotels</h3>
   <div class="header-top-search flex gap-2">
      <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
         <label>Destination or Property Name</label>
         <i class="mapicon"></i>
         <input type="text" name="text" class="form-control" :value="this.searchData?.text" id="search_hotels" autocomplete="off" placeholder="Place or accommodation" v-on:keyup="searchHotels($event)" @click="loadHotels('')">
         <div class="search_hotels">
            <ul id="search_hotels_ul"></ul>
         </div>
      </div>
      <div class="selectoption date_box pr-0.5 md:w-1/2 max-md:w-full">
         <!-- <label class="flex mx-1"><span>(Check In - Check Out)</span></label> -->
         <label><span>(Check In - Check Out)</span></label>
         <i class="calendericon"></i>
         <input type="text" id="hoteldaterange" class="form-control daterange" :value="`${this.searchData?.checkin_picker} - ${this.searchData?.checkout_picker}`" autocomplete="off">
         <input type="hidden" name="checkin" :value="this.searchData?.checkin">
         <input type="hidden" name="checkout" :value="this.searchData?.checkout">
      </div>
      <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
         <label>Guest & Rooms</label>
         <i class="guest-icons"></i>
         <input type="text" class="form-control guest_room" :value="this.searchData?.guest_title" id="guest_rooms" autocomplete="off">
         <div class="passenger_wrap">
            <div class="guest_room_box flex">
               <div class="guest_room_select w-1/2" data-id="guest_room_select">
                  <label>Rooms <span> (Max 6)</span></label>
                  <!-- <div class="selc_btn">
                     <span><i class="fa fa-minus"></i></span>
                     <span class="pax_counter">{{this.searchData?.inventory}}</span>
                     <span><i class="fa fa-plus"></i></span>
                  </div> -->
                  <counterbox :value="this.searchData?.inventory" :minValue="1"/>
               </div>
               <div class="adults_select w-1/2" data-id="adults_select">
                  <label>Adults <span> (12+ yr)</span></label>
                  <!-- <div class="selc_btn">
                     <span><i class="fa fa-minus"></i></span>
                     <span class="pax_counter">{{this.searchData?.adult}}</span>
                     <span><i class="fa fa-plus"></i></span>
                  </div> -->
                  <counterbox :value="this.searchData?.adult" :minValue="1"/>
               </div>
               <div class="child_select w-1/2" data-id="child_select">
                  <label>Children <span> (0-12 yr)</span></label>
                  <!-- <div class="selc_btn">
                     <span><i class="fa fa-minus"></i></span>
                     <span class="pax_counter">{{this.searchData?.child}}</span>
                     <span><i class="fa fa-plus"></i></span>
                  </div> -->
                  <counterbox :value="this.searchData?.child" :minValue="0"/>
               </div>
            </div>
            <div class="mt-2 text-left"><button type="button" class="btn2 text-sm capitalize cursor-pointer mt-1 px-3 py-1 pax_done">Done</button></div>
         </div>
         <input type="hidden" name="inventory" :value="this.searchData?.inventory">
         <input type="hidden" name="adult" :value="this.searchData?.adult">
         <input type="hidden" name="child" :value="this.searchData?.child">
      </div>
      <div class="searchbtn mt-6">
         <input type="hidden" name="slug" :value="this.searchData?.search_slug">
         <input type="submit" class="btn btn-primary p-2 pl-4 pr-3 h-auto" value="Search"> 
      </div>
   </div>
</form-wrapper>
</template>

<script>
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import axios from "axios";
import counterbox from './counterbox.vue';

const FormWrapper = styled.form`
& #guest_rooms+.passenger_wrap { position: absolute; max-height: fit-content; }
`;

export default {
    name:'HotelSearchForm',
    created() {
      if(store.searchData) {
        if(store.searchData.default_filters) {
          this.searchData = store.searchData.default_filters;
        } else {
          this.searchData = store.searchData;
        }
      }
    },
    mounted(){
      $('.pax_done').click(function(){
        var inventory = $(this).parent().parent().find('.guest_room_select .pax_counter').html();
        var adult = $(this).parent().parent().find('.adults_select .pax_counter').html();
        var child = $(this).parent().parent().find('.child_select .pax_counter').html();
        $(document).find('input[name=inventory]').val(parseInt(inventory));
        $(document).find('input[name=adult]').val(parseInt(adult));
        $(document).find('input[name=child]').val(parseInt(child));
        var guest_title = adult+' Adults';
        if(parseInt(child) > 0) {
          guest_title = guest_title + ' + '+ child+' Child';
        }
        if(parseInt(inventory) > 0) {
          guest_title = guest_title + ' | '+ inventory+' Room(s)';
        }
        $(document).find('#guest_rooms').val(guest_title);
        $('.passenger_wrap').fadeToggle("slow");
      });

    },
    data() {
        return {
            store,
            searchData: {},
        }
    },
    methods:{
      validateSearchHotelForm(e) {
        e.preventDefault();
        let currentObj = this;
        var search = true;
        $('#search_hotels_ul li').each(function(){
          if( $(this).hasClass('active')) {
            search = false;
            $(this).trigger('click');
          }
        });
        // return search;
        if(search) {
          if($('#search_hotels_form input[name=slug]').val() == '') {
            $('#search_hotels_form input[name=slug]').attr('disabled',true);
          }
          let query_string = $('#search_hotels_form').serialize();
          var search_url = store.websiteSettings.HOTEL_URL.replace(store.websiteSettings.FRONTEND_URL,'');
          currentObj.$inertia.get(`${search_url}?${query_string}`);
        } else {
          return search;
        }

      },
      searchHotels(event) {
        var search = true;
        var li_count = $('#search_hotels_ul li').length;
        var curent_active = -1;
        var each_counter = 0;
        $('#search_hotels_ul li').each(function(){
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

            $('#search_hotels_ul li').removeClass('active');
            $('#search_hotels_ul li').eq(curent_active).addClass('active');

            if(search) {
              $('#search_hotels_ul').hide();
              $('#search_hotels_ul').empty();
              $('#search_hotels_form input[name=slug]').val('');
              $('#search_hotels_form input[name=slug]').attr('disabled',true);

              var text = event.target.value;
              if(text.length > 2) {
                this.loadHotels(text);
              }
            }    
          },
          loadHotels(text='') {
            var currentObj = this;
            axios.post(store.websiteSettings.ajaxSearchHotels, {
              text: text,
            })  
            .then(function (resp) {  
              let response = resp.data
              $('#search_hotels_ul').empty();
              if(response.success) {
                if(response.result) {
                  $.each(response.result, function(index,row){
                    var row_li = '<li data-slug="'+row.slug+'">'+row.title+'</li>'
                    $('#search_hotels_ul').append(row_li);
                  });
                  $('#search_hotels_ul').show();
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
        counterbox
    },
    props: [],
}
$(document).on('click','#search_hotels_ul li',function(){
      var slug = $(this).attr('data-slug');
      var title = $(this).text();
      $('#search_hotels_ul').hide();
      $('#search_hotels_ul').empty();
      $('#search_hotels').val(title);
      $('#search_hotels_form input[name=slug]').val(slug);
      $('#search_hotels_form input[name=slug]').attr('disabled',false);
    });
</script>