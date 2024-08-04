<template>
    <MyFavouriteWrapper>
    <div class="container-fluid">
        <div class="user_profile_inner">
            <div class="leftpanel w-1/5">
                <UserLeftpanel />
            </div>
             <div class="rightpanel w-4/5">
              <div class="profile_info">
               <div class="title-inner">
                <div class="title">
                 <span>My Favourite</span> 
                 <!-- <p>Basic info, for a faster booking experience</p> -->
                </div>
               </div>
               <div class="card_box flex pt-7">
                <div class="card_box_list w-1/4">
                    <div class="box_shodow">
                    <a class="package_image_box" href="#"><img src="https://andamanisland.tekzini.com/storage/packages/thumb/612a1a4d38fa3109_133.jpg" alt="Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days"></a>
                    <div class="package_info">
                        <div class="day_night"><span>7 Nights -</span> <span>8 Days</span></div>
                        <a href="#">
                            <h3 class="package_title">Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days</h3>
                        </a>
                        <div class="bottom_options"><a class="book_btn" href="">Book Now</a><span class="package_price"><span>From</span> ₹59900.00</span></div>
                    </div>
                  </div>
                </div>
                <div class="card_box_list w-1/4">
                    <div class="box_shodow">
                    <a class="package_image_box" href="#"><img src="https://andamanisland.tekzini.com/storage/packages/thumb/612a1a4d38fa3109_133.jpg" alt="Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days"></a>
                    <div class="package_info">
                        <div class="day_night"><span>7 Nights -</span> <span>8 Days</span></div>
                        <a href="#">
                            <h3 class="package_title">Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days</h3>
                        </a>
                        <div class="bottom_options"><a class="book_btn" href="">Book Now</a><span class="package_price"><span>From</span> ₹59900.00</span></div>
                    </div>
                  </div>
                </div>
                <div class="card_box_list w-1/4">
                    <div class="box_shodow">
                    <a class="package_image_box" href="#"><img src="https://andamanisland.tekzini.com/storage/packages/thumb/612a1a4d38fa3109_133.jpg" alt="Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days"></a>
                    <div class="package_info">
                        <div class="day_night"><span>7 Nights -</span> <span>8 Days</span></div>
                        <a href="#">
                            <h3 class="package_title">Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days</h3>
                        </a>
                        <div class="bottom_options"><a class="book_btn" href="">Book Now</a><span class="package_price"><span>From</span> ₹59900.00</span></div>
                    </div>
                  </div>
                </div>
                <div class="card_box_list w-1/4">
                    <div class="box_shodow">
                    <a class="package_image_box" href="#"><img src="https://andamanisland.tekzini.com/storage/packages/thumb/612a1a4d38fa3109_133.jpg" alt="Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days"></a>
                    <div class="package_info">
                        <div class="day_night"><span>7 Nights -</span> <span>8 Days</span></div>
                        <a href="#">
                            <h3 class="package_title">Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days</h3>
                        </a>
                        <div class="bottom_options"><a class="book_btn" href="">Book Now</a><span class="package_price"><span>From</span> ₹59900.00</span></div>
                    </div>
                  </div>
                </div>
                <div class="card_box_list w-1/4">
                    <div class="box_shodow">
                    <a class="package_image_box" href="#"><img src="https://andamanisland.tekzini.com/storage/packages/thumb/612a1a4d38fa3109_133.jpg" alt="Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days"></a>
                    <div class="package_info">
                        <div class="day_night"><span>7 Nights -</span> <span>8 Days</span></div>
                        <a href="#">
                            <h3 class="package_title">Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days</h3>
                        </a>
                        <div class="bottom_options"><a class="book_btn" href="">Book Now</a><span class="package_price"><span>From</span> ₹59900.00</span></div>
                    </div>
                  </div>
                </div>
                <div class="card_box_list w-1/4">
                    <div class="box_shodow">
                    <a class="package_image_box" href="#"><img src="https://andamanisland.tekzini.com/storage/packages/thumb/612a1a4d38fa3109_133.jpg" alt="Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days"></a>
                    <div class="package_info">
                        <div class="day_night"><span>7 Nights -</span> <span>8 Days</span></div>
                        <a href="#">
                            <h3 class="package_title">Andaman Islands Honeymoon Trip for 7 Nights &amp; 8 Days</h3>
                        </a>
                        <div class="bottom_options"><a class="book_btn" href="">Book Now</a><span class="package_price"><span>From</span> ₹59900.00</span></div>
                    </div>
                  </div>
                </div>
                </div>
                
                </div>
              </div>
            </div>

        </div>
    </MyFavouriteWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { MyFavouriteWrapper } from '../user/styles/MyFavouriteWrapper';

export default {
    created() {
      
    },
    
    data() {
        return {
            store,
        }
    },
    methods: {
        
    },
  
    components: {
    MyFavouriteWrapper,
    UserLeftpanel,
},
    layout: Layout,

    props: [""],
};
</script>