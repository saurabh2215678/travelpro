<template>
    <UserProfileWrapper>
    <div class="container-fluid">
        <div class="user_profile_inner">
            <div class="leftpanel w-1/5">
                <UserLeftpanel />
            </div>
            <div class="rightpanel w-4/5">
                 <BookingTab />
                 <UserSearchForm />
                 <div class="profile_info">
                    <div class="title-inner">
                        <div class="title">
                            <span>Profile</span> 
                            <p>Basic info, for a faster booking experience</p>
                        </div>
                        <div class="right_btn">
                            <button class="edit_btn" @click="handleEditFormPopup(true)"><i class="fa-solid fa-pencil"></i> Edit </button>
                        </div> 
                    </div>
                    
                    <ul class="profile_info_list">
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Name</div>
                                <div class="profile_value">janmejay  testing aws</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Email</div>
                                <div class="profile_value">janmejay@indiainternets.com</div>
                            </div>
                        </li>
                        <li>
                                                        <div class="profile_info_box">
                                <div class="profile_title">Phone</div>
                                <div class="profile_value">+91-7488515453</div>
                            </div>
                                                    </li> 
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Address1</div>
                                <div class="profile_value">Testing</div>
                            </div>
                        </li> 
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Address2</div>
                                <div class="profile_value"></div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">City</div>
                                <div class="profile_value">Arrah</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">State</div>
                                <div class="profile_value">bihar</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Country</div>
                                <div class="profile_value">India</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Zipcode</div>
                                <div class="profile_value">201301</div>
                            </div>
                        </li>
                    </ul>
                    </div>
            </div>


            <div class="edit_profile_field_wrap" v-if="FormFieldPopup">
             <div class="edit_profile_field">
                <form method="POST" action="#" accept-charset="UTF-8" id="update_profile" enctype="multipart/form-data" class="" autocomplete="off">
                    <input name="_token" type="hidden" value="">
                    <div class="edit_profile_inner_box">
                        <div class="cross" @click="handleEditFormPopup(false)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path>
                        </svg>
                        </div>
                        <div class="edit_profile_inner">
                        <div class="form_group">
                            <label>Name</label>
                            <input type="text" name="name" id="name" value="janmejay  testing aws" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Email</label>
                            <input type="text" name="email" id="email" placeholder="" value="janmejay@indiainternets.com" readonly="" disabled="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Phone</label>
                            <select name="country_code" class="form-select country_code">
                                <option value="93">+91</option>
                            </select>
                            <input type="text" name="phone" id="phone" placeholder="" value="7488515453" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
                        </div>
                        <div class="crop_change">
                            <div id="demo-basic" style="height:180px; display:none" class="croppie-container">
                                <div class="cr-boundary" aria-dropeffect="none">
                                    <img class="cr-image" alt="preview" aria-grabbed="false">
                                    <div class="cr-viewport cr-vp-square" tabindex="0" style="width: 150px; height: 200px;"></div>
                                    <div class="cr-overlay"></div>
                                </div>
                                <div class="cr-slider-wrap"><input class="cr-slider" type="range" step="0.0001" aria-label="zoom"></div>
                            </div>
                        </div>
                        <div class="form_group">
                            <label>Address 1<em>*</em></label>
                            <input type="text" name="address1" id="address1" placeholder="" value="Testing" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Address 2</label>
                            <input type="text" name="address2" id="address2" placeholder="" value="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>City<em>*</em></label>
                            <input type="text" name="city" id="city" placeholder="" value="Arrah" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>State<em>*</em></label>
                            <input type="text" name="state" id="state" placeholder="" value="bihar" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Country<em>*</em></label>
                            <select class="form_control" name="country" id="country">
                                <option value="">Please Select</option>
                                
                            </select>
                        </div>
                        <div class="form_group">
                            <label>Zipcode<em>*</em></label>
                            <input type="text" name="zipcode" id="zipcode" placeholder="" value="201301" class="form_control">
                        </div>
                        </div>
                        <button type="submit" class="btn">Submit</button>
                    </div>
                </form>
            </div>
            </div>

        </div>
    </div>
    </UserProfileWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { UserProfileWrapper } from '../user/styles/UserProfileWrapper';

export default {
    created() {
      
    },
    
    data() {
        return {
            store,
            FormFieldPopup:false,
        }
    },
    methods: {
        handleEditFormPopup(isOpened){
            this.FormFieldPopup = isOpened;
        }
    },
  
    components: {
    UserProfileWrapper,
    UserLeftpanel,
},
    layout: Layout,

    props: [""],
};
</script>