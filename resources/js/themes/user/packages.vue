<template>
    <PackageWrapper>
    <div class="container-fluid">
        <div class="user_profile_inner">
            <div class="leftpanel w-1/5">
                <UserLeftpanel />
            </div>
            <div class="rightpanel w-4/5">
                <form action="" method="GET" class="mt-1">
                    <div class="row">
                     <div class="col-md-3">
                        <div class="mb-3">
                            <label for="city_id">Name:</label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                    </div>

                   
                    <div class="col-md-3">
                        <label for="city_id">Destination:</label>
                        <select name="destination" id="" class="form-control" data-placeholder="Select Destination">
                          <option value="">Select Destination</option>
                          <option value="668">Andaman Island</option><option value="672">antika</option><option value="669">baratang</option><option value="677">Goa</option><option value="617">Gwalior</option><option value="618">--lashkar gwalior (Sub)</option><option value="658">hello</option><option value="664">--yashh (Sub)</option><option value="651">Mumbai</option><option value="674">Muzaffarpur</option><option value="675">Patna 1</option><option value="663">Port Blair</option><option value="613">Bihar</option><option value="614">--patna (Sub)</option><option value="624">----bailey road (Sub)</option><option value="648">----boaring road (Sub)</option><option value="1">Ladakh</option><option value="591">--Moving camp (Sub)</option><option value="592">----Rumbak (Sub)</option><option value="593">----Yurutse (Sub)</option><option value="594">----Gandala Base (Sub)</option><option value="595">----Stok Base Camp (Sub)</option><option value="596">----Skyu (Sub)</option><option value="597">----Markha (Sub)</option><option value="598">----Shingo (Sub)</option><option value="619">----Rumbak (Sub)</option><option value="620">----Rumbak (Sub)</option><option value="621">----Rumbak (Sub)</option><option value="22">--Trekking Camp (Sub)</option><option value="138">--CHADAR CAMP (Sub)</option><option value="24">Srilanka</option><option value="2">Himachal</option><option value="570">--keylong (Sub)</option><option value="602">----old Keylong (Sub)</option><option value="25">Dubai</option><option value="3">Kashmir</option><option value="6">Sikkim</option><option value="10">Bhutan</option><option value="590">--new (Sub)</option><option value="8">Nepal</option><option value="9">Tibet</option><option value="27">Darjeeling</option><option value="589">--Rammam (Sub)</option>
                      </select>
                  </div>

                     <div class="col-md-3">
                        <label>&nbsp;</label>
                        <div class="mb-3">
                     <button type="submit" class="btn s-btn rounded-full">Search</button>
                     <!-- <a href="https://overlandescape.tekzini.com/user/packages" class="btn ">Reset</a> -->
                     <a class="btn2 edit_pofile_btn" href="https://overlandescape.tekzini.com/user/packages">Reset</a>
                </div>
                </div>
                
                </div>
            </form>
                 <div class="booking-lists table-responsive mt-2">
                        <table class="table table-bordered table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>Booking Date</th>
                                    <th style="width: 13%;">Booking Order No<br />(Trip Type)</th>
                                    <th>Sector / Destination / Location</th>
                                    <th>Date of Travel</th>
                                    <th>Total Amount</th>
                                    <th>Amount Paid</th>
                                    <th>Amount Due</th>
                                    <th>Markup</th>
                                    <th>Discount/ Comm.</th>
                                    <th>Payment/ <br /> API Status</th>
                                    <th>Order Status</th>
                                    <th>Method</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                        </table>
                        
                    </div>
            </div>
        </div>
    </div>
    </PackageWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { PackageWrapper } from '../user/styles/PackageWrapper';

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
    PackageWrapper,
    UserLeftpanel,
},
    layout: Layout,

    props: [""],
};
</script>