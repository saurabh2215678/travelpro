    <template>
        <form id="adv_hotel_search" name="adv_hotel_search">
            <input type="hidden" name="city" :value="this.store.searchData?.city">
            <input type="hidden" name="pickupDate" :value="this.store.searchData?.pickupDate">
            <input type="hidden" name="pickupTime" :value="this.store.searchData?.pickupTime">
            <input type="hidden" name="dropDate" :value="this.store.searchData?.dropDate">
            <input type="hidden" name="dropTime" :value="this.store.searchData?.dropTime">
            <div class="subtitle text-xl my-3 mb-5">Filter</div>

            <div class="search-box">
                <input type="text" class="search-input" name="key" placeholder="Search.." ref="searchInput" />
                <button class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="acco_category checkbox_list" v-if="locations && locations.length > 0">
                <div class="filter_box">
                    <div class="pb-2 font-semibold text-lg">Location</div>
                    <div class="acco_category checkbox_list">
                        <ul class="term-list">
                            <li class="term-item" v-for="location in locations">
                                <input type="checkbox" :id="`location_${location.id}`" name="locations[]" :value="location.id" :checked="this.checkedFunction('locations',location.id)" >
                                <label :for="`location_${location.id}`"> <span>{{location.name}}</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="acco_category checkbox_list">
                <div class="filter_box">
                    <div class="text-lg font-semibold pb-2">Vehicle Type</div>
                    <div class="acco_category checkbox_list">
                        <ul class="term-list">
                             <li class="term-item" v-for="bikeType,key in bikeTypes">
                                <input type="checkbox" :id="`type_${key}`" name="types[]" :value="key" :checked="this.checkedFunction('types',key)">
                                <label :for="`type_${key}`"> <span>{{bikeType}}</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="acco_category checkbox_list">
                <div class="filter_box">
                    <div class="text-lg font-semibold pb-2">Search By Bike Model</div>
                        <input type="hidden" name="model" :value="this.modelList.modal">
                    <div class="search-box">
                        <!-- <input type="text" class="search-input" name="model" :value="this.search_data.model" placeholder="Search.." />
                        <button class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
 -->    
                         <ModelListSelect :list="this.bike_models" v-model="modelList" placeholder="Model" option-value="modal" option-text="modal"/>
                    </div>
                </div>
            </div>
            <div class="acco_category checkbox_list mt-5 text-center">
                <div class="list_btn_round"><button class="primary-btn" type="submit" >Apply Filter</button>
                 <Link :href="store.websiteSettings?.RENTAL_URL" class="secondary-btn ml-2">Clear</Link>
                </div>
            </div>
        </form>
    </template>
    <script>
    import { store } from '../../store.js';
    import { Link } from "@inertiajs/inertia-vue3";
    import axios from "axios";
    import { ModelListSelect } from 'vue-search-select';
    export default {
        created() {
            if(this.search_data.model){
                const selectedItem = this.bike_models.find(item => item.modal == this.search_data.model);
                this.modelList = selectedItem;
            }

        },
        data() {
            return {
                store,
                modelList:{}
            }
        },
        mounted(){
            if(this.search_data.key){
                this.$refs.searchInput.value = this.search_data.key;
            }

        },
        methods:{
           handleClickfunction(e) {
            this.handleFormSubmit(e);
        },
            handleFormSubmit(e){
             e.preventDefault();
            },
        checkedFunction(module_name,value) {
          let checked = false;
          let checkedArr = [];

          if(this.search_data) {
            if(module_name == 'locations') {
              checkedArr = this.search_data.locations;
            } else if(module_name == 'types') {
              checkedArr = this.search_data.types;

              //console.log('=====>',checkedArr);
            } 
            if(checkedArr) {
              if(checkedArr.indexOf(String(value)) !== -1) {
                checked = true;
              }
            }
          }
          return checked;
        },
        },
        components: {
            Link,
            ModelListSelect
        },
        props: ["cities","locations","bikeTypes","search_data","bike_models"],
    }
    </script>