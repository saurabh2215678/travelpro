<template>
    <GSTWrapper>
    <div class="container-fluid">
        <div class="user_profile_inner">
            <div class="leftpanel w-1/5">
                <UserLeftpanel />
            </div>
             <div class="rightpanel w-4/5">
              <div class="profile_info">
               <div class="title-inner">
                <div class="title">
                 <span>Manage GST</span> 
                 <!-- <p>Basic info, for a faster booking experience</p> -->
                </div>
                 <div class="right_btn">
                    <button class="btn" @click="handleGSTFormPopup(true)"><i class="fa-solid fa-plus mr-1"></i> Add GST</button>
                 </div>
               </div>
               
               <form action="" method="GET" class="mt-5">
                <div class="row">
                    <div class="w-1/4">
                        <label for="city_id">GST Number</label>
                        <input type="text" class="form-control" name="gst_number" value="">
                    </div>
                    <div class="w-1/4">
                        <label for="city_id">Phone</label>
                        <input type="text" class="form-control" name="gst_phone" value="">
                    </div>
                    <div class="w-1/4">
                        <label for="city_id">Email</label>
                        <input type="text" class="form-control" name="gst_email" value="">
                    </div>
                    <div class="w-1/4">
                        <label>&nbsp;</label>
                        <div class="mb-3 flex">
                            <button type="submit" class="btn mr-2">Search</button>
                            <a class="btn2" href="#">Reset</a>
                        </div>
                    </div>
                </div>
                </form>
             <div class="text-sm text-orange-600 italic">There are no GST Info at the present.</div>
                </div>

                <div class="model" v-if="GSTFormPopup">
                <div class="model-box">
                    <form method="POST" action="" class="form" id="gst_form" autocomplete="off" enctype="multipart/form-data">
                        <div class="cross" @click="handleGSTFormPopup(false)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path>
                        </svg>
                        </div>
                        <div class="text-2xl font-semibold pb-2">Add GST Info</div>
                        <div class="form_inner">
                            <div class="flex-wrap">
                                <div class="mb-1 pb-0 w-full">
                                    <label for="">GST Number<em>*</em></label>
                                    <input type="text" class="form-control" name="gst_number" value="">
                                    <sapn class="validation_error"></sapn>
                                </div>
                                <div class="mb-1 pb-0 w-full">
                                    <label for="">GST Company</label>
                                    <input type="text" class="form-control" name="gst_company" value="">
                                </div>
                                <div class="mb-1 pb-0 w-full">
                                    <label for="">Email<em>*</em></label>
                                    <input type="text" class="form-control" name="gst_email" value="">
                                    <sapn class="validation_error"></sapn>
                                </div>
                                <div class="mb-1 pb-0 w-full">
                                    <label for="">Phone<em>*</em></label>
                                    <input type="text" class="form-control" name="gst_phone" value="">
                                    <sapn class="validation_error"></sapn>
                                </div>
                                <div class="mb-1 pb-0 w-full">
                                    <label for="">Address</label>
                                    <input type="text" class="form-control" name="gst_address" value="">
                                </div>
                                <div class="mb-1 pb-0 w-full">
                                    <div class="mt-3 flex">
                                    <button type="submit" class="btn mr-2">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
            </div>
            </div>

              </div>
            </div>

        </div>
    </GSTWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { GSTWrapper } from '../user/styles/GSTWrapper';

export default {
    created() {
      
    },
    
    data() {
        return {
            store,
            GSTFormPopup:false,
        }
    },
    methods: {
        handleGSTFormPopup(isOpened){
            this.GSTFormPopup = isOpened;
        }
    },
  
    components: {
    GSTWrapper,
    UserLeftpanel,
},
    layout: Layout,

    props: [""],
};
</script>