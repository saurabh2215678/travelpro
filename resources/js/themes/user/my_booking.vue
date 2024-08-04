<template>
    <MyBookingWrapper>
    <div class="container-fluid">
        <div class="user_profile_inner">
            <div class="leftpanel w-1/5">
                <UserLeftpanel />
            </div>
            <div class="rightpanel w-4/5">
                 <BookingTab />
                 <UserSearchForm />
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
                            <tbody>
                                <tr>
                                    <td>20 Sep,2023 01:21 PM</td>
                                    <td>
                                        AKERF8604217
                                        <br />
                                        (Activity)
                                    </td>
                                    <td>
                                        Ladakh
                                    </td>
                                    <td>
                                        Sep 21st 2023 04:30 AM
                                    </td>
                                    <td>₹4,887</td>
                                    <td></td>
                                    <td>
                                        <span style="color: orange;">₹4,887</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        ₹543(D)
                                        <br />
                                    </td>
                                    <td>
                                        <span style="color: orange;">Unpaid</span><br />
                                        <a class="user_paynow_btn" href="#" title="Pay Now">Pay Now</a>
                                    </td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td></td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>14 Sep,2023 05:19 PM</td>
                                    <td>
                                        AKSBD6428017
                                        <br />
                                        (Flight)
                                    </td>
                                    <td>
                                        janmejay aws<br />
                                        (BLR-DEL)
                                    </td>
                                    <td>
                                        Sep 24th 2023
                                    </td>
                                    <td>₹3,548.50</td>
                                    <td>
                                        <span style="color: green;">₹3,548.50</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <br />
                                    </td>
                                    <td>
                                        <span style="color: blue;">Refunded</span><br />
                                        <!-- FLIGHT STATUS -->
                                        FAILED
                                    </td>
                                    <td>
                                        <strong><span style="background: #ff1100; color: #fff; padding: 5px 10px; border-radius: 100px;">Cancelled</span></strong>
                                    </td>
                                    <td>wallet</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>14 Sep,2023 03:58 PM</td>
                                    <td>
                                        AKSTR2170985
                                        <br />
                                        (Pay-Online)
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>₹1</td>
                                    <td></td>
                                    <td>
                                        <span style="color: red;">₹1</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        <br />
                                    </td>
                                    <td>
                                        <span style="color: red;">Failed</span><br />
                                        <a class="user_paynow_btn" href="#" title="Pay Now">Pay Now</a>
                                    </td>
                                    <td></td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>14 Sep,2023 03:03 PM</td>
                                    <td>
                                        AKSTC2574130
                                        <br />
                                        (Cab)
                                    </td>
                                    <td>
                                        patna
                                    </td>
                                    <td>
                                        Sep 17th 2023 01:00 AM
                                    </td>
                                    <td>₹5</td>
                                    <td>
                                        <span style="color: green;">₹5</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>13 Sep,2023 06:14 PM</td>
                                    <td>
                                        AKRCS4287530
                                        <br />
                                        (Activity)
                                    </td>
                                    <td>
                                        Ladakh
                                    </td>
                                    <td>
                                        Sep 14th 2023 04:30 AM
                                    </td>
                                    <td>₹4,887</td>
                                    <td>
                                        <span style="color: green;">₹4,887</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        ₹543(D)
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #20a720; color: #fff; padding: 5px 10px; border-radius: 100px;">Confirmed</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="#" title="Activity Voucher" class="cab_view_fancy">
                                            <img style="width: 1.2rem; position: relative; top: 0.15rem;" class="img-responsive" src="https://overlandescape.tekzini.com/assets/overlandescape/images/View-Voucher.png" alt="View-Voucher" />
                                        </a>
                                    </td>
                                </tr>
                            
                                <tr>
                                    <td>05 Sep,2023 10:24 AM</td>
                                    <td>
                                        AKEMK1965324
                                        <br />
                                        (Cab)
                                    </td>
                                    <td>
                                        Delhi
                                    </td>
                                    <td>
                                        Sep 06th 2023 12:45 AM
                                    </td>
                                    <td>₹1,000</td>
                                    <td>
                                        <span style="color: green;">₹1,000</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #ffa500; color: #fff; padding: 5px 10px; border-radius: 100px;">Processing</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Sep,2023 06:12 PM</td>
                                    <td>
                                        AKDCP9127350
                                        <br />
                                        (Pay-Online)
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>₹1</td>
                                    <td>
                                        <span style="color: green;">₹1</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #20a720; color: #fff; padding: 5px 10px; border-radius: 100px;">Confirmed</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Sep,2023 05:42 PM</td>
                                    <td>
                                        AKDBP9836547
                                        <br />
                                        (Rental)
                                    </td>
                                    <td>
                                        Patna rajendra nagar
                                    </td>
                                    <td>
                                        Sep 06th 2023
                                    </td>
                                    <td>₹100</td>
                                    <td>
                                        <span style="color: green;">₹100</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>04 Sep,2023 04:50 PM</td>
                                    <td>
                                        AKDAE8970153
                                        <br />
                                        (Package)
                                    </td>
                                    <td>
                                        Uttarakhand
                                    </td>
                                    <td>
                                        Sep 13th 2023
                                    </td>
                                    <td>₹20,600</td>
                                    <td>
                                        <span style="color: green;">₹20,600</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        ₹400(D)
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Sep,2023 04:47 PM</td>
                                    <td>
                                        AKDAB9478603
                                        <br />
                                        (Activity)
                                    </td>
                                    <td>
                                        Ladakh
                                    </td>
                                    <td>
                                        Sep 10th 2023 05:00 AM
                                    </td>
                                    <td>₹2,500</td>
                                    <td>
                                        <span style="color: green;">₹2,500</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        ₹2,500(D)
                                        <br />
                                    </td>
                                    <td><span style="color: green;">Paid</span><br /></td>
                                    <td>
                                        <strong><span style="background: #20a720; color: #fff; padding: 5px 10px; border-radius: 100px;">Confirmed</span></strong>
                                    </td>
                                    <td>wallet</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Sep,2023 04:34 PM</td>
                                    <td>
                                        AKDAD1308547
                                        <br />
                                        (Package)
                                    </td>
                                    <td>
                                        Uttarakhand
                                    </td>
                                    <td>
                                        Sep 10th 2023
                                    </td>
                                    <td>₹20,600</td>
                                    <td>
                                        <span style="color: green;">₹20,600</span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        ₹400(D)
                                        <br />
                                    </td>
                                    <td><span style="color: blue;">Refunded</span><br /></td>
                                    <td>
                                        <strong><span style="background: #ff1100; color: #fff; padding: 5px 10px; border-radius: 100px;">Cancelled</span></strong>
                                    </td>
                                    <td>wallet</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>04 Sep,2023 04:08 PM</td>
                                    <td>
                                        AKDAH0963812
                                        <br />
                                        (Package)
                                    </td>
                                    <td>
                                        Uttarakhand
                                    </td>
                                    <td>
                                        Sep 10th 2023
                                    </td>
                                    <td>₹20,600</td>
                                    <td></td>
                                    <td>
                                        <span style="color: orange;">₹20,600</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        ₹400(D)
                                        <br />
                                    </td>
                                    <td>
                                        <span style="color: orange;">Unpaid</span><br />
                                        <a class="user_paynow_btn" href="#" title="Pay Now">Pay Now</a>
                                    </td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td></td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Sep,2023 04:07 PM</td>
                                    <td>
                                        AKDAG0849273
                                        <br />
                                        (Package)
                                    </td>
                                    <td>
                                        Uttarakhand
                                    </td>
                                    <td>
                                        Sep 09th 2023
                                    </td>
                                    <td>₹20,600</td>
                                    <td></td>
                                    <td>
                                        <span style="color: orange;">₹20,600</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        ₹400(D)
                                        <br />
                                    </td>
                                    <td>
                                        <span style="color: orange;">Unpaid</span><br />
                                        <a class="user_paynow_btn" href="#" title="Pay Now">Pay Now</a>
                                    </td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04 Sep,2023 11:02 AM</td>
                                    <td>
                                        AKDNB8231674
                                        <br />
                                        (Hotel)
                                    </td>
                                    <td>
                                        Bhutan
                                    </td>
                                    <td>
                                        Sep 10th 2023
                                    </td>
                                    <td>₹4,900</td>
                                    <td></td>
                                    <td>
                                        <span style="color: orange;">₹4,900</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        ₹100(D)
                                        <br />
                                    </td>
                                    <td>
                                        <span style="color: orange;">Unpaid</span><br />
                                        <a class="user_paynow_btn" href="#" title="Pay Now">Pay Now</a>
                                    </td>
                                    <td>
                                        <strong><span style="background: #0275d8; color: #fff; padding: 5px 10px; border-radius: 100px;">New</span></strong>
                                    </td>
                                    <td>Bank Payment</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>04 Sep,2023 10:38 AM</td>
                                    <td>
                                        <a class="view_cancel" href="#" title="Cancel Flight">AKDMH5891460 <i class="fa fa-external-link"></i></a>
                                        <br />
                                        (Flight)
                                    </td>
                                    <td>
                                        janmejay aws<br />
                                        (BLR-BOM)
                                    </td>
                                    <td>
                                        Sep 06th 2023
                                    </td>
                                    <td>₹4,714</td>
                                    <td>
                                        <span style="color: green;">₹4,714</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        ₹5.00
                                    </td>
                                    <td>
                                        ₹500(D)
                                        <br />
                                        ₹450.00(C)
                                    </td>
                                    <td>
                                        <span style="color: green;">Paid</span><br />
                                        <!-- FLIGHT STATUS -->
                                        SUCCESS
                                    </td>
                                    <td>
                                        <strong><span style="background: #20a720; color: #fff; padding: 5px 10px; border-radius: 100px;">Confirmed</span></strong>
                                    </td>
                                    <td>Online Process</td>
                                    <td class="iconlist">
                                        <a href="javascript:void(0);" data-src="#" data-fancybox="" data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="#" title="Flight Voucher" class="cab_view_fancy">
                                            <img style="width: 1.2rem; position: relative; top: 0.15rem;" class="img-responsive" src="https://overlandescape.tekzini.com/assets/overlandescape/images/View-Voucher.png" alt="View-Voucher" />
                                        </a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <table class="totel_markup" width="20%">
                            <tbody><tr>
                                <td>Total Sale </td>
                                <td style="text-align: right;">₹240,503.50</td>
                            </tr>
                        
                            <tr>
                                <td> Total Markup  </td>
                                <td style="text-align: right;">₹5.00</td>
                            </tr>
                            <tr>
                                <td> Total Commission  </td>
                                <td style="text-align: right;">₹450.00</td>
                            </tr>
                            <tr>
                                <td>Discount  </td>
                                <td style="text-align: right;">₹9,406.00</td>
                            </tr>
                            <tr>
                                <td><strong>Net Profit</strong>   </td>
                                <td style="text-align: right;">₹9,861.00</td>
                            </tr>

                        </tbody>
                    </table>
                    </div>
            </div>
        </div>
    </div>
    </MyBookingWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { MyBookingWrapper } from '../user/styles/MyBookingWrapper';
import BookingTab from '../user/components/BookingTab.vue';
import UserSearchForm from '../user/components/UserSearchForm.vue';

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
    MyBookingWrapper,
    UserLeftpanel,
    BookingTab,
    UserSearchForm
},
    layout: Layout,

    props: [""],
};
</script>