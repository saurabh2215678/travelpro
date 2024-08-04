<template>
    <WalletWrapper>
    <div class="container-fluid">
        <div class="user_profile_inner">
            <div class="leftpanel w-1/5">
                <UserLeftpanel />
            </div>
             <div class="rightpanel w-4/5">
              <div class="profile_info">
               <div class="title-inner">
                <div class="title">
                 <span>My Wallet</span> 
                 <!-- <p>Basic info, for a faster booking experience</p> -->
                </div>
               </div>
               
               <div class="wallet-wrapper mt-5">
                    <div class="table-responsive">
                        <table id="" class="table">
                            <thead>
                                <tr role="row">
                                <th>Transaction No</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Payment Method</th>
                                <th style="text-align: right;">Debit (Dr)</th>
                                <th style="text-align: right;">Credit (Cr)</th>
                                <th style="text-align: right;">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:2%;">
                        <h4 class="text-right">Total Summary from 06/09/2023 to 06/10/2023</h4>
                        <div class="total"><strong>Credit (Total) :</strong> <span>₹0.00</span><br>
                            <strong>Debit (Total) :</strong> <span>₹0.00</span><br>
                            <strong>Total Wallet Amount :</strong> <span>₹0.00</span>
                        </div>
                    </div>
                    <h4 class="text-right pt-3 pb-1">Overall Summary</h4>
                    <div class="total"><strong>Credit (Total) :</strong> <span>₹0.00</span><br>
                        <strong>Debit (Total) :</strong> <span>₹0.00</span><br>
                        <strong>Total Wallet Amount :</strong> <span>₹0.00</span>
                    </div>
                    </div>
                </div>
              </div>
            </div>

        </div>
    </WalletWrapper>
</template>
<script>
import Layout from '../grandindiatour/components/layout.vue';
import { store } from '../grandindiatour/store.js';
import UserLeftpanel from '../user/components/UserLeftPanel.vue'
import { WalletWrapper } from '../user/styles/WalletWrapper';

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
    WalletWrapper,
    UserLeftpanel,
},
    layout: Layout,

    props: [""],
};
</script>