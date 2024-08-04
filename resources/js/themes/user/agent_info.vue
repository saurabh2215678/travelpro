<template>
    <AgentProfileWrapper>
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
                            <span>Agent Info</span> 
                            <p>Basic info, for a faster booking experience</p>
                        </div>
                        <div class="right_btn">
                            <button class="edit_btn" @click="handleEditFormPopup(true)"><i class="fa-solid fa-pencil"></i> Edit </button>
                        </div> 
                    </div>
                    <ul class="profile_info_list">
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Name</div>
                                <div class="profile_value">Alliance</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Brand Name</div>
                                <div class="profile_value">softech</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">PAN Number</div>
                                <div class="profile_value">AESPY2776C</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">GST Number</div>
                                <div class="profile_value"></div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Owner Name</div>
                                <div class="profile_value">santosh</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Agent Logo</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">PAN Image</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">GST Image</div>
                            </div>
                        </li>
                        <li>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Bookings Per Month</div>
                                <div class="profile_value">
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Source</div>
                                <div class="profile_value">
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Website</div>
                                <div class="profile_value"></div>
                            </div>
                        </li>
                        <li>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Destinations Sell Most</div>
                                <div class="profile_value"></div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Agency Age</div>
                                <div class="profile_value">
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">No Of Employees</div>
                                <div class="profile_value">
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Region</div>
                                <div class="profile_value">
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Profile</div>
                                <div class="profile_value"></div>
                            </div>
                        </li>
                        <!-- <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Comment</div>
                                <div class="profile_value"></div>
                            </div>
                            </li> -->
                        </ul>
                    </div>
            </div>


            <div class="edit_profile_field_wrap" v-if="FormFieldPopup">
             <div class="edit_profile_field">
                <form method="POST" action="" accept-charset="UTF-8" id="update_profile" enctype="multipart/form-data" class="" autocomplete="off">
                <input name="_token" type="hidden" value="">
                <div class="edit_profile_inner_box">
                    <div class="cross" @click="handleEditFormPopup(true)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path>
                        </svg>
                    </div>
                    <div class="edit_profile_inner">
                        <div class="form_group">
                            <label>Company name<em>*</em></label>
                            <input type="text" name="company_name" id="company_name" value="Alliance" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Company brand<em>*</em></label>
                            <input type="text" name="company_brand" id="company_brand" value="softech" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Company owner name<em>*</em></label>
                            <input type="text" name="company_owner_name" id="company_owner_name" value="santosh" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>PAN Number<em>*</em></label>
                            <input type="text" name="pan_no" id="pan_no" value="AESPY2776C" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Upload PAN Card</label>
                            <input type="file" name="pan_image" id="pan_image" value="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>GST Number</label>
                            <input type="text" name="gst_no" id="gst_no" value="" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Upload GST</label>
                            <input type="file" name="gst_image" id="gst_image" value="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Bookings per month</label>
                            <select class="form_control" name="bookings_per_month">
                            <option value="">--Select--</option>
                            <option value="0_5">0 - 5 </option>
                            <option value="5_15">5 - 15 </option>
                            <option value="15_25">15 - 25 </option>
                            <option value="more_than_25">More than 25 </option>
                            </select>
                        </div>
                        <div class="form_group">
                            <label>Agent logo</label>
                            <input type="file" name="agent_logo" id="agent_logo" value="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Source</label>
                            <select class="form_control" name="source">
                            <option value="">--Select--</option>
                            <option value="emails_from_us">Emails from us </option>
                            <option value="we_called_you">We called you </option>
                            <option value="google_bing_search">Google / Bing search </option>
                            <option value="news_magazines">News/ Magazines </option>
                            <option value="linkedin_facebook">LinkedIn / Facebook </option>
                            <option value="other_travel_agents">Other Travel Agents </option>
                            <option value="your_team_member">Your team member </option>
                            <option value="other_misc">Other / Misc </option>
                            </select>
                        </div>
                        <div class="form_group">
                            <label>Website</label>
                            <input type="text" name="website" id="website" value="" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Whatsapp phone</label>
                            <select name="whatsapp_country_code" class="form-select country_code">
                            <option value="93">+93</option>
                            <option value="35818">+35818</option>
                            </select>
                            <input type="text" name="whatsapp_phone" id="whatsapp_phone" value="" placeholder="" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
                        </div>
                        <div class="form_group">
                            <label>destinations sell most</label>
                            <input type="text" name="destinations_sell_most" id="destinations_sell_most" value="" placeholder="" class="form_control">
                        </div>
                        <div class="form_group">
                            <label>Agency age</label>
                            <select class="form_control" name="agency_age">
                            <option value="">--Select--</option>
                            <option value="less_than_2_years_old">Less than 2 years old </option>
                            <option value="2_5_years_old">2 - 5 years old </option>
                            <option value="5_10_years_old">5 - 10 years old </option>
                            <option value="more_than_10_years_old">More than 10 years old </option>
                            </select>
                        </div>
                        <div class="form_group">
                            <label for="no_of_employees" class="control-label required">Number of Employees?</label>
                            <select class="form_control" name="no_of_employees">
                            <option value="">--Select--</option>
                            <option value="less_than_5_employees">Less than 5 employees </option>
                            <option value="5_10_employees">5 - 10 employees </option>
                            <option value="10_15_employees">10 - 15 employees </option>
                            <option value="more_than_15">More than 15 </option>
                            </select>
                        </div>
                        <div class="form_group">
                            <label>Region</label>
                            <select class="form_control" name="region">
                            <option value="">--Select--</option>
                            <option value="us">US </option>
                            <option value="uk">UK </option>
                            <option value="rest_of_europe">Rest of Europe </option>
                            <option value="middle_east">Middle East </option>
                            <option value="india">India </option>
                            <option value="asia">Asia </option>
                            </select>
                        </div>
                        <div class="form_group" style="width: 100%;">
                            <label>Company profile</label>
                            <input type="text" name="company_profile" id="company_profile" value="" placeholder="" class="form_control">
                        </div>
                        <!-- <div class="form_group col-md-12">
                            <label>Comment</label>
                            
                            <input type="text" name="queries" id="query" value=""  placeholder="" class="form_control">
                            </div>  -->
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
                    </div>
                    <button type="submit" class="btn">Submit</button>
                </div>
                </form>
            </div>
            </div>

        </div>
    </div>
    </AgentProfileWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { AgentProfileWrapper } from '../user/styles/AgentProfileWrapper';

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
    AgentProfileWrapper,
    UserLeftpanel,
},
    layout: Layout,

    props: [""],
};
</script>