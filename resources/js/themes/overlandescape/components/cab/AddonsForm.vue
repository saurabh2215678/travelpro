<template>
    <AddonsFormWrapper>
    <form @submit.prevent="handleSubmit" class="rts_flight">
        <div class="fts_top" ref="cabRef">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="border border-slate-300 text-left">Items</th>
                        <th class="border border-slate-300 text-center">Qty</th>
                        <th class="border border-slate-300 text-center">Days</th>
                        <th class="border border-slate-300 text-right">Price</th>
                    </tr>
                </thead>
                <tr v-for="addon in addons.data">
                    <td style="white-space: nowrap;">
                        <strong> <input type="checkbox" :name="`addons_${addon.id}`" :value="addon.id" @change="handleChange($event)" > {{addon.name}} </strong>
                    </td>
                    <td align="center">
                     <div class="inputval">
                        <button type="button" @click="(e) => handleMinus(e,addon.id,'qty')"><i class="fa-solid fa-minus"></i></button>
                         <input type="text" :name="`qty_${addon.id}`" :value="this.formData[`qty_${addon.id}`]"  @change="handleChange($event)" class="form-control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" readonly>
                         <button type="button" @click="(e) => handlePlus(e,addon.id,'qty')"><i class="fa-solid fa-plus"></i></button>
                        <div class="error" v-if="this.errors[`qty_${addon.id}`]">{{this.errors[`qty_${addon.id}`][0]}}</div>
                     </div>
                    </td>
                    <td align="center">
                        <div class="inputval">
                        <template v-if="addon.daily_rental==1">
                            <button type="button" @click="(e) => handleMinus(e,addon.id,'days')"><i class="fa-solid fa-minus"></i></button>
                             <input type="text" :name="`days_${addon.id}`" :value="this.formData[`days_${addon.id}`]" @change="handleChange($event)" class="form-control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" readonly>
                             <button type="button" @click="(e) => handlePlus(e,addon.id,'days')"><i class="fa-solid fa-plus"></i></button>
                            <div class="error" v-if="this.errors[`days_${addon.id}`]">{{this.errors[`days_${addon.id}`][0]}}</div>
                        </template>
                    </div>
                    </td>
                    <td align="right">
                        <template v-if="addon.daily_rental==1">
                            <span v-if="addon.price && this.formData[`qty_${addon.id}`] && this.formData[`days_${addon.id}`]">
                                {{showPrice(addon.price*this.formData[`qty_${addon.id}`]*this.formData[`days_${addon.id}`])}}
                            </span>
                            <span v-else>
                                {{showPrice(addon.price)}}
                            </span>
                        </template>
                        <template v-else>
                            <span v-if="addon.price && this.formData[`qty_${addon.id}`]">
                                {{showPrice(addon.price*this.formData[`qty_${addon.id}`])}}
                            </span>
                            <span v-else>
                                {{showPrice(addon.price)}}
                            </span>
                        </template>
                    </td>
                </tr>
            </table>
        </div>
        <div class="fts_bottom">
            <input type="hidden" name="action" value="addons">
            <span class="stp_btn" @click="clicked">Back</span>
            <button class="stp_btn" v-if="this.processing">Processing...</button>
            <button class="stp_btn" type="submit" v-else>Next</button>
        </div>
    </form>
</AddonsFormWrapper>
</template>

<script>
import axios from "axios";
import { validateEmail, validatePhone, isEmpty, showErrorToast, showPrice } from '../../utils/commonFuntions';
import {AddonsFormWrapper} from '../../styles/AddonsFormWrapper.js';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';

export default {
    created() {
        this.errors = {...this.Errors};


        if(this.addons.data.length > 0) {
            let newFormData = this.formData;
            this.addons.data.forEach((addon) => {
                newFormData[`qty_${addon.id}`] = 1;
                if(addon.daily_rental==1) {
                    newFormData[`days_${addon.id}`] = 1;
                }
            });
            this.formData = newFormData;
        }
        // console.log(this.routeInfo);
    },
    data() {
        return {
            test : "test",
            errors : {},
            axios_errors : {},
            routeInfo: this.routeInfo,
            countryData: this.countryData,
            formData : {
                'action': 'addons'
            },
            store
        }
    },
    methods: {
        showErrorToast,
        showPrice,
        handleChange(event) {
            let currentObj = this;
            if (event?.target?.name) {
                var name = event.target.name;
                var value = event.target.value;
                if (event.target.type == "file") {
                    value = event.target.files[0];
                }
                if (event.target.type == "checkbox") {
                    if (event.target.checked) {
                        //value = 1;
                    } else {
                        value = "";
                    }
                }
                let newFormData = currentObj.formData;
                newFormData[name] = value;
                currentObj.formData = newFormData;
            }
            currentObj.errors = {};
            currentObj.flashMessages = [];
        },
        handlePlus(e, id, field) {
            e.preventDefault();
            e.stopPropagation();
            let value = 1;
            let newFormData = this.formData;
            if(newFormData[`${field}_${id}`]) {
                value = parseInt(newFormData[`${field}_${id}`]);
            }
            if(value > 0) {
                value = value + 1;
            } else {
                value = 1;
            }
            newFormData[`${field}_${id}`] = value;
            this.formData = newFormData;
        },
        handleMinus(e, id, field) {
            e.preventDefault();
            e.stopPropagation();
            let value = 1;
            let newFormData = this.formData;
            if(newFormData[`${field}_${id}`]) {
                value = parseInt(newFormData[`${field}_${id}`]);
            }
            if(value > 1) {
                value = value - 1;
            } else {
                value = 1;
            }
            newFormData[`${field}_${id}`] = value;
            this.formData = newFormData;
        },
        formSubmit() {
            var currentObj = this;
            let formData = new FormData();
            var fd = currentObj.formData;
            formData.append('action', 'addons');
            if(this.addons.data) {
                this.addons.data.forEach((addon) => {
                    let varName = `addons_${addon.id}`;
                    if (fd[varName]) {
                        formData.append(varName, fd[varName]);
                    }
                    let qtyName = `qty_${addon.id}`;
                    if (fd[qtyName]) {
                        formData.append(qtyName, fd[qtyName]);
                    }
                    let daysName = `days_${addon.id}`;
                    if (fd[daysName]) {
                        formData.append(daysName, fd[daysName]);
                    }
                })
            }
            currentObj.processing = true;

            axios.post('/cab/addons/'+this.routeInfo.cab_route_id, formData)  
            .then(function (response) {  
                // currentObj.output = response.data;
                if(response.data.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    currentObj.showErrorToast(response.data.message,10);
                }
                currentObj.processing = false;
            })
            .catch(function (error) {
                currentObj.processing = false;
                if(error.response.data.errors) {
                    currentObj.errors = error.response.data.errors;
                }
                if(error.response.data.message) {
                    currentObj.showErrorToast(error.response.data.message,10);
                }
            });

        },
        handleSubmit(){
            this.formSubmit();
        },
        handleInput(e){
            this.formdata[e.target.name] = e.target.value;
            // console.log(e.target.name);
            e.target.name == 'name' && this.validateName(); 
            e.target.name == 'email' && this.validateEmail(); 
            e.target.name == 'phone' && this.validatePhone(); 
            e.target.name == 'pickup' && this.validatePickup(); 
            e.target.name == 'drop' && this.validateDrop(); 
        },
        clicked() {
            store.loadingName = "searchForm";
            window.history.back();
        }
    },
    mounted () {},
    beforeDestroy () {},
    watch:{
        errors: {
            handler: function(errors) {
                // console.log(errors);
            },
            deep: true
        }
    },
    components: {
        Link,
        AddonsFormWrapper
    },
    props: ["Errors", "goBack", "routeInfo" ,"countryData", "tripType", "addons"],
};
</script>