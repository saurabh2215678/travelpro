<template>
    <FlightTopAreaWrapper class="flight_page search_list_flight">
        <div class="flight-banner">
            <div class="xl:container xl:mx-auto container mx-auto">
                <flight-head-serarch class="head-search">
                    <FormTopMenu activeForm="flight" />
                    <div class="head_band">
                        <div class="container-full">
                            <div class="flight_search_detail" v-if="!modifySearch">
                                <div class="flight_info_scroller">
                                    <div  v-if="store.tripType == 2" class="sch1" v-for="routeInfo, index in this.routeInfos">
                                        <div class="fl_box">
                                            <h3 class="mb-0 font17">{{routeInfo['fromCityOrAirport']['code']??''}}</h3>
                                            <p class="mb-0 font14">{{routeInfo['fromCityOrAirport']['city']??''}}, {{routeInfo['fromCityOrAirport']['country']??''}}</p>
                                        </div>
                                        <div class="fl_icon"><i class="fa-solid fa-jet-fighter"></i></div>
                                        <div class="fl_box">
                                            <h3 class="mb-0 font17">{{routeInfo['toCityOrAirport']['code']??''}}</h3>
                                            <p class="mb-0 font14">{{routeInfo['toCityOrAirport']['city']??''}}, {{routeInfo['toCityOrAirport']['country']??''}}</p>
                                        </div>
                                    </div>
                                    <div v-else class="sch1" v-for="routeInfo, index in this.routeInfos.slice(0,1)">
                                        <div class="fl_box">
                                            <h3 class="mb-0 font17">{{routeInfo['fromCityOrAirport']['code']??''}}</h3>
                                            <p class="mb-0 font14">{{routeInfo['fromCityOrAirport']['city']??''}}, {{routeInfo['fromCityOrAirport']['country']??''}}</p>
                                        </div>
                                        <div class="fl_icon"><i class="fa-solid fa-jet-fighter"></i></div>
                                        <div class="fl_box">
                                            <h3 class="mb-0 font17">{{routeInfo['toCityOrAirport']['code']??''}}</h3>
                                            <p class="mb-0 font14">{{routeInfo['toCityOrAirport']['city']??''}}, {{routeInfo['toCityOrAirport']['country']??''}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="tripType == 0 || tripType == 1" class="sch2">
                                    <div class="pr_box">
                                        <div class="prrt">Departure Date</div>
                                        <div class="prrb">{{DateFormat(this.routeInfos[0]['travelDate'],'ddd, MMM Do YYYY')}}</div>
                                    </div>
                                </div>
                
                                <div v-if="tripType == 1" class="sch2">
                                    <div class="pr_box">
                                        <div class="prrt">Return Date</div>
                                        <div class="prrb">{{DateFormat(this.routeInfos[1]['travelDate'],'ddd, MMM Do YYYY')}}</div>
                                    </div>
                                </div>
                                
                                <div class="sch3">
                                    <div class="pr_box">
                                        <div class="prrt">Passengers & Class</div>
                                        <div class="prrb">{{this.paxInfo.ADULT}} Adults <span v-if="this.paxInfo.CHILD > 0">| {{this.paxInfo.CHILD}} Child</span> <span v-if="this.paxInfo.INFANT > 0">| {{this.paxInfo.INFANT}} Infant</span> | {{ this.cabinClass }}</div>
                                    </div>
                                </div>
                                
                                <div class="sch4">
                                    <div class="pr_box">
                                        <div class="prrt">Preferred Airline</div>
                                        <div class="prrb">None</div>
                                    </div>
                                </div>
                
                                <div class="modify_btn_sec">
                                    <button @click="handleModifySearch">Modify Search <i class="fa-solid fa-angle-down"></i></button>
                                </div>                
                            </div>
                            <div class="search_inner_top_wrapper" v-if="modifySearch">
                                <SearchCountryForm :airportLists="airportLists" :paxInfo="paxInfo" :routeInfos="routeInfos" :cabinClass="cabinClass" :TripType="tripType" />
                                <div class="closeModifySearch"  @click="handleModifySearch"><i class="fa-solid fa-xmark"></i></div>
                            </div>
                        </div>
                    </div>
                </flight-head-serarch>
            </div>
        </div>
    </FlightTopAreaWrapper>
    

    <div class="container_flight_loader" :class="store.loadingName == 'searchForm' ? 'active' : ''">
            <div class="flightloader"> <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/images/loading-ban.gif`" /> </div>
            <div class="text1">Just a moment, we are searching for the flights on this route.</div>
        </div>
    <OneWayPageLoader v-if="store.loadingName == 'searchForm'" /> 
    <FlightResWrapper class="flight_res_sec" :class="(tripType == 1 || tripType == 2) ? 'bottom_action_active' : ''" v-if="searchResult.tripInfos">
        <!-- <div class="right_toggler" v-if="(tripType == 1 || tripType == 2)" :class="toggleSide ? 'opened' : ''" @click="handleToggleSlide"><i class="fa-solid fa-chevron-left"></i></div> -->
        <div :class=" tripType == 1 ? 'container' : 'xl:container xl:mx-auto container mx-auto'">
            <div class="flight_res_wrap">
                <div class="flight_res_left">
                    <element v-if="tripType == 2 && !this.showSingleBooking" v-for="routeInfo, index in this.routeInfos">
                        <div v-if="store.price_range[index] && this.activeTab==index" class="price_silder">
                            <h3 class="font14 fw600">Price</h3>
                            <Slider 
                            v-model="store.price_range[index]['range']" 
                            :min= "store.price_range[index]['min']"
                            :max= "store.price_range[index]['max']"
                            :step= "1000"
                            @change="handlePriceChange"
                            />
                        </div>
                    </element>
                    <div v-else-if="store.price_range[this.activeTab]" class="price_silder">
                        <h3 class="font14 fw600">Price</h3>
                        <Slider 
                        v-model="store.price_range[this.activeTab]['range']" 
                        :min= "store.price_range[this.activeTab]['min']"
                        :max= "store.price_range[this.activeTab]['max']"
                        :step= "1000"
                        @change="handlePriceChange"
                        />
                    </div>
                    <div class="show_sec" v-if="1==2">
                        <label><span>Show Incv</span> <input type="checkbox" value="Show Incv" @click="handleShowIncv"></label>
                        <label><span>Show Net</span> <input type="checkbox" value="Show Net" @click="handleShowNet"></label>
                    </div>
                    <div class="stops_sec">
                        <h3 class="font14 fw600 pb-1">Stops</h3>
                        <div v-if="tripType==1 && !this.showSingleBooking" class="choose_stop">
                            <element v-for="routeInfo, index in this.routeInfos" :key="index">
                                <ul>
                                    <li><label> <input type="radio" :value="index"  name="stopsActiveTab" @click="handleStopsActive" :checked="this.stopsActiveTab==index" /> <span>{{routeInfo['fromCityOrAirport']['code']}}-{{routeInfo['toCityOrAirport']['code']}}</span><i></i></label></li>
                                </ul>
                            </element>
                        </div>
                        <element v-for="routeInfo, index in this.routeInfos" :key="index">
                            <ul v-if="this.stopsActiveTab==index">
                                <li><label> <input type="checkbox" value="0" :checked="this.filter_stops[index] && this.filter_stops[index].indexOf('0') > -1"  @click="handleStops" /> <span>0</span> <i></i></label></li>
                                <li><label> <input type="checkbox" value="1" :checked="this.filter_stops[index] && this.filter_stops[index].indexOf('1') > -1" @click="handleStops" /> <span>1</span> <i></i></label></li>
                                <li><label> <input type="checkbox" value="2" :checked="this.filter_stops[index] && this.filter_stops[index].indexOf('2') > -1" @click="handleStops" /> <span>2</span> <i></i></label></li>
                                <li><label> <input type="checkbox" value="3" :checked="this.filter_stops[index] && this.filter_stops[index].indexOf('3') > -1" @click="handleStops" /> <span>3+</span> <i></i></label></li>
                            </ul>
                        </element>
                    </div>


                    <element v-for="routeInfo, index in this.routeInfos">
                        <element v-if="(tripType != 2) || (tripType == 2 && this.activeTab==index) ">
                        <div class="departure_from_sec time_sec">
                            <h3 class="font14 fw600 pb-1">Departure From {{routeInfo['fromCityOrAirport']['city']??''}}</h3>
                            <ul>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`departure_00-05_${index}`" :checked="this.filter_departure_arrival[index] && this.filter_departure_arrival[index].departure.indexOf('00-05') > -1" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-mountain-sun"></i> 00-06</span> 
                                        <i></i>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`departure_06-11_${index}`" :checked="this.filter_departure_arrival[index] && this.filter_departure_arrival[index].departure.indexOf('06-11') > -1" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-sun"></i> 06-12</span> 
                                        <i></i>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`departure_12-17_${index}`" :checked="this.filter_departure_arrival[index] && this.filter_departure_arrival[index].departure.indexOf('12-17') > -1" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-cloud-moon"></i> 12-18</span> 
                                        <i></i>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`departure_18-23_${index}`" :checked="this.filter_departure_arrival[index] && this.filter_departure_arrival[index].departure.indexOf('18-23') > -1" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-moon"></i> 18-00</span> 
                                        <i></i>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="arrival_from_sec time_sec">
                            <h3 class="font14 fw600 pb-1">Arrival To {{routeInfo['toCityOrAirport']['city']??''}}</h3>
                            <ul>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`arrival_00-05_${index}`" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-mountain-sun"></i> 00-06</span> 
                                        <i></i>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`arrival_06-11_${index}`" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-sun"></i> 06-12</span> 
                                        <i></i>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`arrival_12-17_${index}`" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-cloud-moon"></i> 12-18</span> 
                                        <i></i>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="checkbox" :value="`arrival_18-23_${index}`" @click="handleRouteDepartureArrival">
                                        <span><i class="fa-solid fa-moon"></i> 18-00</span> 
                                        <i></i>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        </element>
                    </element>

                    <element v-for="routeInfo, index in this.routeInfos" v-if="tripType == 2 && 1==2">                    
                        <div class="fare_identifier" v-if="this.activeTab==index">
                            <h3 class="font14 fw600 pb-1">Fare Identifier</h3>
                            <ul v-if="store.fare_identifier[index]">
                                <li v-if="store.fare_identifier[index]['published']"><label><input type="checkbox" name="fare-identifier" value="published" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['published']['counter']}}</span></label></li>
                                <li v-if="store.fare_identifier[index]['sme']"><label><input type="checkbox" name="fare-identifier" value="sme" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['sme']['counter']}}</span></label></li>
                                <li v-if="store.fare_identifier[index]['corporate']"><label><input type="checkbox" name="fare-identifier" value="corporate" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['corporate']['counter']}}</span></label></li>
                                <li v-if="store.fare_identifier[index]['sale']"><label><input type="checkbox" name="fare-identifier" value="sale" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['sale']['counter']}}</span></label></li>
                            </ul>
                        </div>
                    </element>
                    <div class="fare_identifier" v-if="tripType != 2 && 1==2" >
                        <h3 class="font14 fw600 pb-1">Fare Identifier</h3>
                        <ul v-if="store.fare_identifier[this.activeTab]">
                            <li v-if="store.fare_identifier[this.activeTab]['published']"><label><input type="checkbox" name="fare-identifier" value="published" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['published']['counter']}}</span></label></li>
                            <li v-if="store.fare_identifier[this.activeTab]['sme']"><label><input type="checkbox" name="fare-identifier" value="sme" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['sme']['counter']}}</span></label></li>
                            <li v-if="store.fare_identifier[this.activeTab]['corporate']"><label><input type="checkbox" name="fare-identifier" value="corporate" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['corporate']['counter']}}</span></label></li>
                            <li v-if="store.fare_identifier[this.activeTab]['sale']"><label><input type="checkbox" name="fare-identifier" value="sale" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['sale']['counter']}}</span></label></li>
                        </ul>
                    </div>
                    <element  v-for="routeInfo, index in this.routeInfos">
                        <div class="airline_filter" v-if="tripType == 2 && this.activeTab==index" >
                            <h3 class="font14 fw600 pb-1">Airlines</h3>
                            <ul>
                                <li v-for="airline,code in store.airlines[index]"><label>{{airline.name}} <div class="airline_total-counter">{{airline.counter}}</div> <span>{{showPrice(airline.price)}}</span><input type="checkbox" :value="code" @click="(e)=>handleAirline(e,index)" ></label></li>
                            </ul>
                        </div>
                    </element>

                    <div class="airline_filter" v-for="routeInfo, index in this.routeInfos" v-if="tripType != 2" >
                        <h3 v-if="tripType == 0" class="font14 fw600">Airlines</h3>
                        <h3 v-if="tripType == 1" class="font14 fw600">{{(index==0)?'Onward ':'Return '}}Airlines</h3>
                        <ul>
                            <li v-for="airline,code in store.airlines[index]"><label>{{airline.name}}<div class="airline_total-counter"> {{airline.counter}} </div><span>{{showPrice(airline.price)}}</span><input type="checkbox" :value="code" @click="(e)=>handleAirline(e,index)" ></label></li>
                        </ul>
                    </div>

                </div>
                <div class="flight_res_right" :class="toggleSide ? 'opened_right' : ''" v-if="searchResult.tripInfos">
                    <div class="flights_head" v-if="1==2">
                        <h4 v-if="tripType==0" class="font13">Found {{this.totalFlights}} Flights from {{this.routeInfos[0]['fromCityOrAirport']['city']}} to {{this.routeInfos[0]['toCityOrAirport']['city']}}</h4>
                        <div class="share_by">
                            <span><i class="fa-solid fa-share-nodes"></i> Share By: </span>
                            <span><i class="fa-brands fa-whatsapp"></i> Whatsapp</span>
                            <span><i class="fa-solid fa-envelope"></i> Email</span>
                            <span><i class="fa-solid fa-eye"></i> View</span>
                        </div>
                    </div>

                    <div :class="handleTableClass()">
                        <div v-if="this.searchResult.tripInfos && this.searchResult.tripInfos.COMBO" class="flight_table search_table_inr">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sort By :
                                            <span @click="handleSortData('COMBO','duration')">Duration</span>
                                            <span class="asc" @click="handleSortData('COMBO','duration','desc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='duration' && this.sortAscDesc['COMBO']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('COMBO','duration','asc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='duration' && this.sortAscDesc['COMBO']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th>
                                            <span @click="handleSortData('COMBO','departure')">Departure</span>
                                            <span class="asc" @click="handleSortData('COMBO','departure','desc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='departure' && this.sortAscDesc['COMBO']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('COMBO','departure','asc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='departure' && this.sortAscDesc['COMBO']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th></th>
                                        <th>
                                            <span @click="handleSortData('COMBO','arrival')">Arrival</span>
                                            <span class="asc" @click="handleSortData('COMBO','arrival','desc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='arrival' && this.sortAscDesc['COMBO']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('COMBO','arrival','asc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='arrival' && this.sortAscDesc['COMBO']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th colspan="2">
                                            <div class="last_options">
                                                <div class="results_option_left">
                                                    <span @click="handleSortData('COMBO','price')">Price</span>
                                                    <span class="asc" @click="handleSortData('COMBO','price','desc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='price' && this.sortAscDesc['COMBO']['sortOrder']=='asc'">asc</span>
                                                    <span class="dsc" @click="handleSortData('COMBO','price','asc')" v-if="this.sortAscDesc['COMBO'] && this.sortAscDesc['COMBO']['sortBy']=='price' && this.sortAscDesc['COMBO']['sortOrder']=='desc'">desc</span>
                                                </div>
                                                <!-- <div class="results_option_right">
                                                    <span class="result_day_option">Previous Day</span>
                                                    <span>|</span>
                                                    <span class="result_day_option">Next Day</span>
                                                </div> -->
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="this.filteredSearchResult.tripInfos && this.filteredSearchResult.tripInfos.COMBO" v-for="flight, index in this.filteredSearchResult.tripInfos.COMBO" :key="flight.totalPriceList[0].id">
                                        <FlightCard :id="index" :info="flight" :routeInfos="this.routeInfos" :paxInfo="this.paxInfo" :priceIdName="`COMBO`" :totalFlights="this.filteredSearchResult.tripInfos.COMBO.length" :routeIndex="0" :tripType="tripType" :showSingleBooking="this.showSingleBooking" />
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <div v-if="this.searchResult.tripInfos && this.searchResult.tripInfos.ONWARD" class="flight_table search_table_inr">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sort By :
                                            <span @click="handleSortData('ONWARD','duration')">Duration</span>
                                            <span class="asc" @click="handleSortData('ONWARD','duration','desc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='duration' && this.sortAscDesc['ONWARD']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('ONWARD','duration','asc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='duration' && this.sortAscDesc['ONWARD']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th>
                                            <span @click="handleSortData('ONWARD','departure')">Departure</span>
                                            <span class="asc" @click="handleSortData('ONWARD','departure','desc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='departure' && this.sortAscDesc['ONWARD']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('ONWARD','departure','asc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='departure' && this.sortAscDesc['ONWARD']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th></th>
                                        <th>
                                            <span @click="handleSortData('ONWARD','arrival')">Arrival</span>
                                            <span class="asc" @click="handleSortData('ONWARD','arrival','desc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='arrival' && this.sortAscDesc['ONWARD']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('ONWARD','arrival','asc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='arrival' && this.sortAscDesc['ONWARD']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th colspan="2">
                                            <div class="last_options">
                                                <div class="results_option_left">
                                                    <span @click="handleSortData('ONWARD','price')">Price</span>
                                                    <span class="asc" @click="handleSortData('ONWARD','price','desc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='price' && this.sortAscDesc['ONWARD']['sortOrder']=='asc'">asc</span>
                                                    <span class="dsc" @click="handleSortData('ONWARD','price','asc')" v-if="this.sortAscDesc['ONWARD'] && this.sortAscDesc['ONWARD']['sortBy']=='price' && this.sortAscDesc['ONWARD']['sortOrder']=='desc'">desc</span>
                                                </div>
                                                <div class="results_option_right">
                                                    <span class="result_day_option" @click="handleSearchDate('ONWARD','previous')">Previous Day</span>
                                                    <span>|</span>
                                                    <span class="result_day_option" @click="handleSearchDate('ONWARD','next')">Next Day</span>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="this.filteredSearchResult.tripInfos && this.filteredSearchResult.tripInfos.ONWARD" v-for="flight, index in this.filteredSearchResult.tripInfos.ONWARD" :key="flight.totalPriceList[0].id">
                                        <FlightCard :id="index" :info="flight" :routeInfos="this.routeInfos" :paxInfo="this.paxInfo" :priceIdName="`ONWARD`" :totalFlights="this.filteredSearchResult.tripInfos.ONWARD.length" :routeIndex="0" :tripType="tripType" :showSingleBooking="this.showSingleBooking" />
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="this.searchResult.tripInfos && this.searchResult.tripInfos.RETURN" class="flight_table search_table_inr">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sort By :
                                            <span @click="handleSortData('RETURN','duration')">Duration</span>
                                            <span class="asc" @click="handleSortData('RETURN','duration','desc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='duration' && this.sortAscDesc['RETURN']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('RETURN','duration','asc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='duration' && this.sortAscDesc['RETURN']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th>
                                            <span @click="handleSortData('RETURN','departure')">Departure</span>
                                            <span class="asc" @click="handleSortData('RETURN','departure','desc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='departure' && this.sortAscDesc['RETURN']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('RETURN','departure','asc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='departure' && this.sortAscDesc['RETURN']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th></th>
                                        <th>
                                            <span @click="handleSortData('RETURN','arrival')">Arrival</span>
                                            <span class="asc" @click="handleSortData('RETURN','arrival','desc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='arrival' && this.sortAscDesc['RETURN']['sortOrder']=='asc'">asc</span>
                                            <span class="dsc" @click="handleSortData('RETURN','arrival','asc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='arrival' && this.sortAscDesc['RETURN']['sortOrder']=='desc'">desc</span>
                                        </th>
                                        <th colspan="2">
                                            <div class="last_options">
                                                <div class="results_option_left">
                                                    <span @click="handleSortData('RETURN','price')">Price</span>
                                                    <span class="asc" @click="handleSortData('RETURN','price','desc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='price' && this.sortAscDesc['RETURN']['sortOrder']=='asc'">asc</span>
                                                    <span class="dsc" @click="handleSortData('RETURN','price','asc')" v-if="this.sortAscDesc['RETURN'] && this.sortAscDesc['RETURN']['sortBy']=='price' && this.sortAscDesc['RETURN']['sortOrder']=='desc'">desc</span>
                                                </div>
                                                <div class="results_option_right">
                                                    <span class="result_day_option" @click="handleSearchDate('RETURN','previous')">Previous Day</span>
                                                    <span>|</span>
                                                    <span class="result_day_option" @click="handleSearchDate('RETURN','next')">Next Day</span>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <template v-if="this.filteredSearchResult.tripInfos && this.filteredSearchResult.tripInfos.RETURN" v-for="flight, index in this.filteredSearchResult.tripInfos.RETURN" :key="flight.totalPriceList[0].id">
                                        <FlightCard :id="index" :info="flight" :routeInfos="this.routeInfos" :paxInfo="this.paxInfo" :priceIdName="`RETURN`" :routeIndex="1" :tripType="tripType" :showSingleBooking="this.showSingleBooking" />

                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="this.searchResult.tripInfos.length > 0" class="flight_table search_table_inr">
                                <div class="sticky_top_tab">
                                    <button 
                                    v-for="routeInfo, index in this.routeInfos" :key="index"
                                    @click="handleTab(index)"
                                    type="button"
                                    class="stiky_tab_btn" 
                                    :class="activeTab == index ? 'active' : ''"
                                    >
                                        <span class="sticky_tab_country_name">{{routeInfo['fromCityOrAirport']['city']}} <i class="fa-solid fa-arrow-right"></i> {{routeInfo['toCityOrAirport']['city']}}</span> 
                                        <span class="sticky_tab_date">{{DateFormat(routeInfo['travelDate'],'ddd, MMM Do YYYY')}}</span>
                                    </button>
                                </div>
                                <element v-for="tripInfos, index in this.filteredSearchResult.tripInfos" :key="index" :class="activeTab == index ? 'active' : ''">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Sort By :
                                                    <span @click="handleSortData(index,'duration')">Duration</span>
                                                    <span class="asc" @click="handleSortData(index,'duration','desc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='duration' && this.sortAscDesc[index]['sortOrder']=='asc'">asc</span>
                                                    <span class="dsc" @click="handleSortData(index,'duration','asc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='duration' && this.sortAscDesc[index]['sortOrder']=='desc'">desc</span>
                                                </th>
                                                <th>
                                                    <span @click="handleSortData(index,'departure')">Departure</span>
                                                    <span class="asc" @click="handleSortData(index,'departure','desc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='departure' && this.sortAscDesc[index]['sortOrder']=='asc'">asc</span>
                                                    <span class="dsc" @click="handleSortData(index,'departure','asc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='departure' && this.sortAscDesc[index]['sortOrder']=='desc'">desc</span>
                                                </th>
                                                <th></th>
                                                <th>
                                                    <span @click="handleSortData(index,'arrival')">Arrival</span>
                                                    <span class="asc" @click="handleSortData(index,'arrival','desc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='arrival' && this.sortAscDesc[index]['sortOrder']=='asc'">asc</span>
                                                    <span class="dsc" @click="handleSortData(index,'arrival','asc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='arrival' && this.sortAscDesc[index]['sortOrder']=='desc'">desc</span>
                                                </th>
                                                <th colspan="2">
                                                    <div class="last_options">
                                                        <div class="results_option_left">
                                                            <span @click="handleSortData(index,'price')">Price</span>
                                                            <span class="asc" @click="handleSortData(index,'price','desc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='price' && this.sortAscDesc[index]['sortOrder']=='asc'">asc</span>
                                                            <span class="dsc" @click="handleSortData(index,'price','asc')" v-if="this.sortAscDesc[index] && this.sortAscDesc[index]['sortBy']=='price' && this.sortAscDesc[index]['sortOrder']=='desc'">desc</span>
                                                        </div>
                                                        <div class="results_option_right">
                                                            <!-- <span class="result_day_option">Previous Day</span>
                                                            <span>|</span>
                                                            <span class="result_day_option">Next Day</span> -->
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>    
                                            <template v-if="tripInfos" v-for="flight, flightIndex in tripInfos" :key="flight.totalPriceList[0].id">
                                                <FlightCard :id="flightIndex" :info="flight" :routeInfos="this.routeInfos"  :paxInfo="this.paxInfo" :priceIdName="index" :routeIndex="index" :tripType="tripType" :showSingleBooking="this.showSingleBooking" />

                                            </template>
                                        </tbody>
                                    </table>
                                </element>                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div v-if="!this.showSingleBooking" class="bottom_action_bar">
            
            <div v-if="store.priceIds.price_chk_ONWARD || store.priceIds.price_chk_RETURN" class="container flight_bottom_container">
                <FlightBookCard v-if="store.priceIds.price_chk_ONWARD" :info="store.priceIds.price_chk_ONWARD_info" :id="store.priceIds.price_chk_ONWARD" :key="store.priceIds.price_chk_ONWARD" :paxInfo="paxInfo" />

                <FlightBookCard v-if="store.priceIds.price_chk_RETURN" :info="store.priceIds.price_chk_RETURN_info" :id="store.priceIds.price_chk_RETURN" :key="store.priceIds.price_chk_RETURN" :paxInfo="paxInfo" />
                <table>
                    <tr><td>Total</td></tr>
                    <tr><td>{{showPrice(this.getBookingTotalPrice())}}</td></tr>
                </table>
                <button class="btn" @click="handleBooking" >Book</button>
            </div>

            <div class="container flight_bottom_container" v-else>
                <element v-for="tripInfos, index in searchResult.tripInfos" :key="index">
                    <FlightBookCard v-if="store.priceIds[`price_chk_${index}`]" :info="store.priceIds[`price_chk_${index}_info`]" :id="store.priceIds[`price_chk_${index}`]" :key="store.priceIds[`price_chk_${index}`]" :paxInfo="paxInfo" />
                </element>
                <table>
                    <tr><td>Total</td></tr>
                    <tr><td>{{showPrice(this.getBookingTotalPrice())}}</td></tr>
                </table>
                <button class="btn" @click="handleBooking" >Book</button>
            </div>

        </div>
    </FlightResWrapper>

    <section class="p-0" v-else>
        <div class="no_flight_found">
            <p class="errorMessage">
                <strong>Sorry</strong>
                <p>No Flights Found!</p>
            </p>
            <LottieAnimation
                path="../assets/lottieFiles/no-flight-found.json"
                :loop="true"
                :autoPlay="true"
                :speed="1"
            />
        </div>
    </section>

    <div class="pageLoader" :class="loading ? 'active' : ''">
        <LottieAnimation
        path="../assets/lottieFiles/loader.json"
        :loop="true"
        :autoPlay="true"
        :speed="1"
        :width="256"
        :height="256"
        />
    </div>
    
    <div class="styles_overlay" :class="sessionPopup ? 'active' : ''">
        <div class="styles_modal promtbox_wrapper">
            <div class="promtbox_wrapper_area">
            <h2 class="promtbox_wrapper-mainheading">Search Result</h2>
            <p class="promtbox_wrapper-content">Your session has expired. Please reload the page to continue.</p>
            <div class="promtbox_wrapper-button"><button class="btn btn-warning asr-book" @click="reloadPage">Reload Page</button></div>
        </div>
        </div>
        </div>
</template>

<script>
import {DateFormat, showPrice, showErrorToast, getInfoTotalPrice, showCabinClass, getTotalDuration} from '../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import Slider from '@vueform/slider';
import '@vueform/slider/themes/default.css';
import FlightCard from "../components/flight/FlightCard.vue";
import FlightBookCard from "../components/flight/FlightBookCard.vue";
import SearchCountryForm from '../components/flight/SearchCountryForm.vue';
import moment from 'moment';
import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";
// import {searchResultResponse} from "./data.js";
import { store } from '../store';
import Layout from '../components/layout.vue'
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import styled from 'vue3-styled-components';
import FormTopMenu from '../components/FormTopMenu.vue';
import { FlightResWrapper } from './FlightResWrapper';
import {FlightTopAreaWrapper} from '../styles/FlightTopAreaWrapper';

// const FlightResWrapper = 

const FlightHeadSerarch = styled.div`
.headerType2 .flight_page.search_list_flight .head-search&{
    margin-top: 2rem; padding-top: 3rem;
}
.headerType2 .flight_page.search_list_flight .flight-banner & ul.menu_list{
    display: flex;
}
`

export default {
    created() {
        // console.log('this.routeInfos',this.routeInfos[0]);
        document.body.classList.add('headerType2');
        if(this.errors && this.errors.length > 0) {
            let currentObj = this;
            this.errors.forEach((error,index) => {
                currentObj.showErrorToast(error['message']);
            });
            setTimeout(() => {
                window.location.href='/flight';
                // currentObj.$inertia.get(`/flight`);
            },3000);
            
        }
        
        store.loadingName = false
        store.routeInfos = this.routeInfos;

        setInterval(() => {
            this.timer += 1;
            if(this.timer == 1800){
                this.sessionPopup = true;
            //   window.location.reload(true);
            }
          }, 1000);
        

    },
    beforeUnmount() {
        document.body.classList.remove('headerType2');
    },
    
    data() {
        return {
            showErrorToast,
            visible: false,
            searchResult: this.searchResult,
            filteredSearchResult: {'tripInfos':{}},
            errors: this.errors,
            paxInfo: this.paxInfo,
            routeInfos: this.routeInfos,
            cabinClass: this.cabinClass,
            totalFlights: this.totalFlights,
            form_data: this.form_data,
            url: this.url,
            store,
            modifySearch: false,
            filter_stops: {},
            filter_departure_arrival: {},
            filter_fare_identifier: [],
            filter_airlines: {},
            loading: false,
            stopsActiveTab : 0,
            activeTab : 0,
            showSingleBooking : false,
            sortAscDesc : {},

            fromCountry:{},
            toCountry:{},
            departureDate:'',
            returnDate:'',
            passangers: {
                adult: 1,
                children: 0,
                infant: 0,
                total: 1,
            },
            BookingClass : 'Economy',
            showModal: false,
            timer: 0,
            sessionPopup: false,
            toggleSide: false,
            bookingTotalPrice: 0
        }
    },

    beforeUnmount() {
    //console.log(this.$el)
    document.body.classList.remove('headerType2');
  },

    methods : {
        DateFormat,
        showPrice,
        getInfoTotalPrice,
        showCabinClass,
        getTotalDuration,
        handleModifySearch() {
            this.modifySearch = !this.modifySearch;
        },
        handleToggleSlide(){
            this.toggleSide = !this.toggleSide
        },
        reloadPage() {
          window.location.reload();
        },
        
        handleSortData(tripKey, sortBy, sortOrder) {
            let allFlights = this.searchResult.tripInfos[tripKey];

            let newFlights = [];
            if(allFlights) {
                allFlights.forEach((flight, index) => {
                    let newTotalPriceList = flight.totalPriceList;
                    newTotalPriceList = newTotalPriceList.sort((a, b) => {
                        return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                    });
                    let priceList = newTotalPriceList[0];
                    if(newTotalPriceList[0] && newTotalPriceList[0].fareIdentifier != 'SPECIAL_RETURN' || tripKey=='COMBO') {
                        newFlights.push(flight);
                    }
                });
            }
            let flights = newFlights;

            if(this.sortAscDesc[tripKey] && this.sortAscDesc[tripKey]['sortBy'] && this.sortAscDesc[tripKey]['sortBy'] == sortBy) {
                sortOrder = (this.sortAscDesc[tripKey]['sortOrder']=='asc')?'desc':'asc';
            }
            if(!sortOrder) {
                sortOrder = 'asc';
            }
            this.sortAscDesc[tripKey] = {'sortBy':sortBy,'sortOrder':sortOrder};
            if(sortOrder == 'desc') {
                flights.sort((flight_a, flight_b) => {
                    if(sortBy == 'duration') {
                        var duration_a = parseInt(this.getTotalDuration(flight_a.sI));
                        var duration_b = parseInt(this.getTotalDuration(flight_b.sI));
                        return duration_b-duration_a;
                    } else if(sortBy == 'departure') {
                        var departure_a = moment(flight_a.sI[0].dt);
                        var departure_b = moment(flight_b.sI[0].dt);
                        return departure_b-departure_a;
                    } else if(sortBy == 'arrival') {
                        var arrival_a = moment(flight_a.sI[flight_a.sI.length-1].at);
                        var arrival_b = moment(flight_b.sI[flight_b.sI.length-1].at);
                        return arrival_b-arrival_a;
                    } else if(sortBy == 'price') {
                        let newTotalPriceList = flight_a.totalPriceList;
                        newTotalPriceList = newTotalPriceList.sort((a, b) => {
                            return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                        });
                        let priceList = newTotalPriceList[0];
                        var price_a = parseInt(this.getTotalPrice(priceList));

                        let newTotalPriceList_b = flight_b.totalPriceList;
                        newTotalPriceList_b = newTotalPriceList_b.sort((a, b) => {
                            return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                        });
                        let priceList_b = newTotalPriceList_b[0];
                        var price_b = parseInt(this.getTotalPrice(priceList_b));

                        return price_b-price_a;
                    }
                });
            } else {
                flights.sort((flight_a, flight_b) => {
                    var fareIdentifierA = flight_a.totalPriceList[0]?.fareIdentifier;
                    var fareIdentifierB = flight_b.totalPriceList[0]?.fareIdentifier;
                    if( (fareIdentifierA && fareIdentifierA.indexOf('IIAIR') > -1) || (fareIdentifierB && fareIdentifierB.indexOf('IIAIR') > -1)  ) {
                        return 1;
                    } else if(sortBy == 'duration') {
                        var duration_a = parseInt(this.getTotalDuration(flight_a.sI));
                        var duration_b = parseInt(this.getTotalDuration(flight_b.sI));
                        return duration_a-duration_b;
                    } else if(sortBy == 'departure') {
                        var departure_a = moment(flight_a.sI[0].dt);
                        var departure_b = moment(flight_b.sI[0].dt);
                        return departure_a-departure_b;
                    } else if(sortBy == 'arrival') {
                        var arrival_a = moment(flight_a.sI[flight_a.sI.length-1].at);
                        var arrival_b = moment(flight_b.sI[flight_b.sI.length-1].at);
                        return arrival_a-arrival_b;
                    } else if(sortBy == 'price') {
                        let newTotalPriceList = flight_a.totalPriceList;
                        newTotalPriceList = newTotalPriceList.sort((a, b) => {
                            return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                        });
                        let priceList = newTotalPriceList[0];
                        var price_a = parseInt(this.getTotalPrice(priceList));

                        let newTotalPriceList_b = flight_b.totalPriceList;
                        newTotalPriceList_b = newTotalPriceList_b.sort((a, b) => {
                            return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                        });
                        let priceList_b = newTotalPriceList_b[0];
                        var price_b = parseInt(this.getTotalPrice(priceList_b));

                        return price_a-price_b;
                    }
                });
            }
            
            // return flights;
            if(flights[0]) {
                let flight = flights[0];
                let newTotalPriceList = flight.totalPriceList;
                newTotalPriceList = newTotalPriceList.sort((a, b) => {
                    return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                });
                let priceList = newTotalPriceList[0];

                store.priceIds[`price_chk_${tripKey}`] = priceList.id;
                store.priceIds[`price_chk_${tripKey}_info`] = flight;
            }
            this.filteredSearchResult.tripInfos[tripKey] = flights;
        },
        handleTableClass(){
            var tableClass = 'longTable';
            if(this.searchResult.tripInfos) {
                if(this.searchResult.tripInfos.COMBO) {
                    tableClass = 'longTable';
                } else if(this.searchResult.tripInfos.ONWARD || this.searchResult.tripInfos.RETURN) {
                    tableClass = 'longTable';
                    if(this.searchResult.tripInfos.RETURN) {
                        tableClass = 'splitTable'
                    }
                } else {
                    tableClass = 'longTable';
                }
            }
            return tableClass;
        },
        handleTab(tabId){
            this.activeTab = tabId;
            this.stopsActiveTab = tabId;
            store.activeRouteIndex = tabId;
            // console.log('this.filter_stops=',this.filter_stops);
        },
        handlePriceChange(value) {
            this.loading = true;
            store.price_range[this.activeTab]['range'] = value;
            setTimeout(this.filterSearchResult,300);
        },
        handleShowIncv(){
            store.filter_showIncv = !store.filter_showIncv;
        },
        handleShowNet() {
            store.filter_showNet = !store.filter_showNet;
        },
        handleStopsActive (e) {
            // this.loading = true;
            this.stopsActiveTab = e.target.value;
            // setTimeout(this.filterSearchResult,300);
        },
        handleStops (e) {
            this.loading = true;
            var filter_stops = [];
            if(this.filter_stops[this.stopsActiveTab]) {
                filter_stops = this.filter_stops[this.stopsActiveTab];
            }

            if(e.target.checked){
                filter_stops.push(e.target.value);
            } else {
                const index = filter_stops.indexOf(e.target.value);
                if (index > -1) { // only splice array when item is found
                  filter_stops.splice(index, 1); // 2nd parameter means remove one item only
                }
            }
            this.filter_stops[this.stopsActiveTab] = filter_stops;
            setTimeout(this.filterSearchResult,300);
        },
        handleRouteDepartureArrival(e) {
            this.loading = true;
            var value = e.target.value;
            var value_arr = value.split('_');
            var routeInfosIndex = value_arr[2];

            var filter_departure_arrival = [];
            if(this.filter_departure_arrival[routeInfosIndex]) {
                filter_departure_arrival = this.filter_departure_arrival[routeInfosIndex];
            } else {
                filter_departure_arrival = [];
            }

            if(value_arr[0]=='departure') {
                if(!filter_departure_arrival['departure']) {
                    filter_departure_arrival['departure'] = [];
                }
                if(e.target.checked){
                    filter_departure_arrival['departure'].push(value_arr[1]);
                } else {
                    const index = filter_departure_arrival['departure'].indexOf(value_arr[1]);
                    if (index > -1) { // only splice array when item is found
                      filter_departure_arrival['departure'].splice(index, 1); // 2nd parameter means remove one item only
                    }
                }
            } else {
                if(!filter_departure_arrival['arrival']) {
                    filter_departure_arrival['arrival'] = [];
                }
                if(e.target.checked){
                    filter_departure_arrival['arrival'].push(value_arr[1]);
                } else {
                    const index = filter_departure_arrival['arrival'].indexOf(value_arr[1]);
                    if (index > -1) { // only splice array when item is found
                      filter_departure_arrival['arrival'].splice(index, 1); // 2nd parameter means remove one item only
                    }
                }
            }
            this.filter_departure_arrival[routeInfosIndex] = filter_departure_arrival;
            // console.log('this.filter_departure_arrival=',this.filter_departure_arrival);
            setTimeout(this.filterSearchResult,300);
        },
        handleFareIdentifier (e) {
            this.loading = true;
            var allCheckboxes = [...document.querySelectorAll(`[name="${e.target.name}"]`)];
            var currentCheckBox = e.target;

            const index = allCheckboxes.indexOf(currentCheckBox);
            if (index > -1) {allCheckboxes.splice(index, 1);}
            allCheckboxes.forEach(item=>{item.checked = false;});
         

            var filter_fareIdentifier = '';
            if(store.filter_fareIdentifier[this.activeTab]) {
                filter_fareIdentifier = store.filter_fareIdentifier[this.activeTab];
            } else {
                filter_fareIdentifier = '';
            }
            if(e.target.checked){
                filter_fareIdentifier = e.target.value;
            } else {
                filter_fareIdentifier = '';
            }
            store.filter_fareIdentifier[this.activeTab] = filter_fareIdentifier;
            setTimeout(this.filterSearchResult,300);
        },
        handleAirline(e,routeInfosIndex) {
            // console.log('routeInfosIndex=',routeInfosIndex);
            // console.log('e.target.value=',e.target.value);
            this.loading = true;
            var filter_airlines = this.filter_airlines;
            if(e.target.checked){
                if(!filter_airlines[routeInfosIndex]) {
                    filter_airlines[routeInfosIndex] = [];
                }
                filter_airlines[routeInfosIndex].push(e.target.value);
            } else {
                if(filter_airlines[routeInfosIndex]) {
                    const index = filter_airlines[routeInfosIndex].indexOf(e.target.value);
                    if (index > -1) { // only splice array when item is found
                      filter_airlines[routeInfosIndex].splice(index, 1); // 2nd parameter means remove one item only
                    }                    
                }
            }
            this.filter_airlines = filter_airlines;
            setTimeout(this.filterSearchResult,300);
        },
        filterSearchResult () {
            var newSearchResult = [];
            var newTripInfos = [];
            if(this.tripType==2) {
                if(this.searchResult.tripInfos.COMBO) {
                    var newFlights = [];
                    this.searchResult.tripInfos.COMBO.forEach((flight, index2) => {
                        if(this.isFlightAvailable(flight,0)) {
                            newFlights.push(flight);
                        }
                    });
                    newTripInfos['COMBO'] = newFlights;
                } else {
                    this.searchResult.tripInfos.forEach((tripInfos, index) => {
                        if(tripInfos) {
                            var newFlights = [];
                            tripInfos.forEach((flight, index2) => {
                                if(this.isFlightAvailable(flight,index)) {
                                    newFlights.push(flight);
                                }
                            });
                            newTripInfos[index] = newFlights;
                        }
                    });
                }
            } else {
                if(this.searchResult.tripInfos.ONWARD) {
                    var newFlights = [];
                    this.searchResult.tripInfos.ONWARD.forEach((flight, index2) => {
                        if(this.isFlightAvailable(flight,0)) {
                            newFlights.push(flight);
                        }
                    });
                    newTripInfos['ONWARD'] = newFlights;
                }
                if(this.searchResult.tripInfos.RETURN) {
                    var newFlights = [];
                    this.searchResult.tripInfos.RETURN.forEach((flight, index2) => {
                        if(this.isFlightAvailable(flight,1)) {
                            newFlights.push(flight);
                        }
                    });
                    newTripInfos['RETURN'] = newFlights;
                }
            }
            newSearchResult['tripInfos'] = newTripInfos;
            // console.log('newSearchResult=',newSearchResult);
            this.filteredSearchResult = newSearchResult;
            // console.log('this.filteredSearchResult=',this.filteredSearchResult);
            this.loading = false;
        },
        isFlightAvailable(flight, routeInfosIndex) {
            var isFlightAvailable = false;

            var price_range = [];
            if(store.price_range[this.activeTab]['range']) {
                price_range = store.price_range[this.activeTab]['range'];
            }

            var filter_stops = [];
            if(this.filter_stops[this.stopsActiveTab] && this.stopsActiveTab == routeInfosIndex) {
                filter_stops = this.filter_stops[this.stopsActiveTab];
            }

            var filter_departure_arrival = [];
            if(this.filter_departure_arrival[this.activeTab]) {
                filter_departure_arrival = this.filter_departure_arrival[this.activeTab];
            }

            var filter_fareIdentifier = '';
            if(store.filter_fareIdentifier[this.activeTab]) {
                filter_fareIdentifier = store.filter_fareIdentifier[this.activeTab];
            }

            var filter_airlines = [];
            if(this.filter_airlines[this.activeTab]) {
                filter_airlines = this.filter_airlines[this.activeTab];
            }

            var price_range_exists = true;
            var flight_stop_exists = true;
            var flight_departure_arrival_exists = true;
            var fare_identifier_exists = true;
            var filter_airlines_exists = true;

            // console.log('price_range=',price_range);
            if(price_range && price_range.length > 0) {
                price_range_exists = false;
                var min = price_range[0];
                var max = price_range[1];

                // flight.totalPriceList.forEach((priceList) => {
                //     var price = this.getTotalPrice(priceList);
                //     if(price >= min && price <= max) {
                //         price_range_exists = true;
                //     }
                // });

                let newTotalPriceList = flight.totalPriceList;
                newTotalPriceList = newTotalPriceList.sort((a, b) => {
                    return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                });
                var price = this.getTotalPrice(newTotalPriceList[0]);
                if(price >= min && price <= max) {
                    price_range_exists = true;
                }
            }

            if(filter_stops && filter_stops.length > 0) {
                flight_stop_exists = false;

                if(flight.sI && flight.sI.length > 0) {
                    let flightSegments = [];
                    let counter = -1;
                    flight.sI.forEach((flightData, index) => {
                        if(flightData.sN==0) {
                            counter = counter + 1;
                        }
                        if(!flightSegments[counter]) {
                            flightSegments[counter] = [];
                        }
                        flightSegments[counter].push(flightData);
                    });
                    if(flightSegments.length > 0) {
                        flightSegments.forEach((flightSegment) => {
                            var flight_stop = parseInt(flightSegment.length-1);
                            filter_stops.forEach((stop, index3) => {
                                var stop = parseInt(stop);
                                if( (flight_stop == stop) || (flight_stop > 3 && stop == 3)) {
                                    flight_stop_exists = true;
                                }
                            });
                        })
                    }
                }
            }

            if(filter_departure_arrival.departure && filter_departure_arrival.departure.length > 0) {
                flight_departure_arrival_exists = false;
                filter_departure_arrival.departure.forEach((da, index4) => {
                    // console.log('departure_arrival.da',da);                    
                    var travelDate = this.routeInfos[routeInfosIndex].travelDate;
                    var da_time_arr = da.split('-');
                    var da_time_start = travelDate+'T'+da_time_arr[0]+':00:01';
                    var da_time_end = travelDate+'T'+da_time_arr[1]+':59:59';
                    // console.log('flight.sI[0].dt=',flight.sI[0].dt);
                    // console.log('da_time_start=',da_time_start);
                    // console.log('da_time_end=',da_time_end);
                    if(moment(flight.sI[0].dt) >= moment(da_time_start) && moment(flight.sI[0].dt) <= moment(da_time_end)) {
                        flight_departure_arrival_exists = true;
                    }
                });
            }

            if(flight_departure_arrival_exists && filter_departure_arrival.arrival && filter_departure_arrival.arrival.length > 0) {
                flight_departure_arrival_exists = false;
                filter_departure_arrival.arrival.forEach((aa, index4) => {
                    // console.log('departure_arrival.da',da);                    
                    var travelDate = this.routeInfos[routeInfosIndex].travelDate;
                    var da_time_arr = aa.split('-');
                    var da_time_start = travelDate+'T'+da_time_arr[0]+':00:01';
                    var da_time_end = travelDate+'T'+da_time_arr[1]+':59:59';
                    // console.log('flight.sI[0].dt=',flight.sI[0].dt);
                    // console.log('da_time_start=',da_time_start);
                    // console.log('da_time_end=',da_time_end);
                    if(moment(flight.sI[flight.sI.length-1].at) >= moment(da_time_start) && moment(flight.sI[flight.sI.length-1].at) <= moment(da_time_end)) {
                        flight_departure_arrival_exists = true;
                        // console.log('arrival.flight_departure_arrival_exists=',flight.sI[flight.sI.length-1].at);
                    }
                });
            }



            /*if(filter_departure_arrival && filter_departure_arrival.length > 0) {
                filter_departure_arrival.forEach((da, index4) => {
                    console.log('departure_arrival.da',da);
                    var da_arr = da.split('_');
                    var fromCity = da_arr[2].substr(0,3);
                    var toCity = da_arr[2].substr(-3);
                    if(flight.sI[0].da.code==fromCity && flight.sI[flight.sI.length-1].aa.code==toCity) {
                        if(da_arr[0]=='departure') {
                            flight_departure_arrival_exists = false;
                            var travelDate = da_arr[2].substr(3,4)+'-'+da_arr[2].substr(7,2)+'-'+da_arr[2].substr(9,2);
                            var da_time_arr = da_arr[1].split('-');
                            var da_time_start = travelDate+'T'+da_time_arr[0]+':00:01';
                            var da_time_end = travelDate+'T'+da_time_arr[1]+':59:59';
                            // console.log('flight.sI[0].dt=',flight.sI[0].dt);
                            // console.log('da_time_start=',da_time_start);
                            // console.log('da_time_end=',da_time_end);
                            if(moment(flight.sI[0].dt) >= moment(da_time_start) && moment(flight.sI[0].dt) <= moment(da_time_end)) {
                                flight_departure_arrival_exists = true;
                                // console.log('departure.flight_departure_arrival_exists=',flight.sI[0].dt);
                            }
                        } else if(da_arr[0]=='arrival') {
                            // console.log('arrival=',da_arr[0]);
                            flight_departure_arrival_exists = false;
                            var travelDate = da_arr[2].substr(3,4)+'-'+da_arr[2].substr(7,2)+'-'+da_arr[2].substr(9,2);
                            var da_time_arr = da_arr[1].split('-');
                            var da_time_start = travelDate+'T'+da_time_arr[0]+':00:01';
                            var da_time_end = travelDate+'T'+da_time_arr[1]+':59:59';
                            // console.log('flight.sI[0].dt=',flight.sI[0].dt);
                            // console.log('da_time_start=',da_time_start);
                            // console.log('da_time_end=',da_time_end);
                            if(moment(flight.sI[flight.sI.length-1].at) >= moment(da_time_start) && moment(flight.sI[flight.sI.length-1].at) <= moment(da_time_end)) {
                                flight_departure_arrival_exists = true;
                                // console.log('arrival.flight_departure_arrival_exists=',flight.sI[flight.sI.length-1].at);
                            }
                        }
                    }
                });
            }*/

            // console.log('filter_fareIdentifier',filter_fareIdentifier);
            if(filter_fareIdentifier) {
                fare_identifier_exists = false;

                let newTotalPriceList = flight.totalPriceList;
                newTotalPriceList = newTotalPriceList.sort((a, b) => {
                    return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
                });
                let priceList = newTotalPriceList[0];

                // flight.totalPriceList.forEach((priceList) => {
                    if(filter_fareIdentifier == 'published' && store.filter_published_arr.indexOf(priceList.fareIdentifier) > -1) {
                        fare_identifier_exists = true;
                    } else if(filter_fareIdentifier == 'sme' && store.filter_sme_arr.indexOf(priceList.fareIdentifier) > -1) {
                        fare_identifier_exists = true;
                    } else if(filter_fareIdentifier == 'corporate' && store.filter_corporate_arr.indexOf(priceList.fareIdentifier) > -1) {
                        fare_identifier_exists = true;
                    } else if(filter_fareIdentifier == 'sale' && store.filter_sale_arr.indexOf(priceList.fareIdentifier) > -1) {
                        fare_identifier_exists = true;
                    }
                // });
            }

            if(filter_airlines && filter_airlines.length > 0) {
                filter_airlines_exists = false;
                if(filter_airlines.indexOf(flight.sI[0]['fD']['aI']['code']) > -1) {
                    filter_airlines_exists = true;
                }
            }

            // console.log('price_range_exists',price_range_exists);
            // console.log('flight_stop_exists',flight_stop_exists);
            // console.log('flight_departure_arrival_exists',flight_departure_arrival_exists);
            // console.log('fare_identifier_exists',fare_identifier_exists);
            // console.log('filter_airlines_exists',filter_airlines_exists);
            if(price_range_exists && flight_stop_exists && flight_departure_arrival_exists && fare_identifier_exists && filter_airlines_exists) {
                isFlightAvailable = true;
            }
            return isFlightAvailable;
        },
        getTotalPrice : function(priceList) {
            let totalPrice = 0;
            let totalAdultPrice = 0;          
            let totalChildPrice = 0;
            let totalInfantPrice = 0;
            if(this.paxInfo && priceList.fd) {
                if(this.paxInfo.ADULT > 0) {
                    totalAdultPrice = priceList.fd.ADULT.fC.TF * this.paxInfo.ADULT; 
                }
                if(this.paxInfo.CHILD > 0) {
                    totalChildPrice = priceList.fd.CHILD.fC.TF * this.paxInfo.CHILD; 
                }
                if(this.paxInfo.INFANT > 0) {
                    totalInfantPrice = priceList.fd.INFANT.fC.TF * this.paxInfo.INFANT; 
                }
            }
            totalPrice = totalAdultPrice + totalChildPrice + totalInfantPrice;
            return parseFloat(totalPrice).toFixed(2);
        },        
        handleBooking() {
            // console.log('store.priceIds=',store.priceIds);
            var priceIds = [];
            var routeInfosLength = 0;
            if(this.searchResult.tripInfos) {
                if(this.searchResult.tripInfos.COMBO) {
                    if(store.priceIds.price_chk_COMBO) {
                        priceIds.push(store.priceIds.price_chk_COMBO);
                    }
                    routeInfosLength += 1;
                } else if(this.searchResult.tripInfos.ONWARD || this.searchResult.tripInfos.RETURN) {
                    if(this.searchResult.tripInfos.ONWARD) {
                        if(store.priceIds.price_chk_ONWARD) {
                            priceIds.push(store.priceIds.price_chk_ONWARD);
                        }
                        routeInfosLength += 1;
                    }

                    if(this.searchResult.tripInfos.RETURN) {
                        if(store.priceIds.price_chk_RETURN) {
                            priceIds.push(store.priceIds.price_chk_RETURN);
                        }
                        routeInfosLength += 1;
                    }
                } else {
                    this.routeInfos.forEach((value, index) => {
                        if(store.priceIds[`price_chk_${index}`]) {
                            var price_id = store.priceIds[`price_chk_${index}`];
                            priceIds.push(price_id);
                        }
                        routeInfosLength += 1;
                    });
                }
            }
            if(priceIds.length == routeInfosLength) {
                store.loadingName = 'searchForm';
                store.bookingTotalPrice = this.bookingTotalPrice;
                var priceIdsstr = priceIds.join(',');
                this.$inertia.get(`/flight/itinerary/${priceIdsstr}`);
                // console.log(store.loadingName);
            } else {
                alert('Please select all segments flights to Book');
            }
        },
        initiateFlightFilters(flight,activeTab=0) {
            var fare_identifier = store.fare_identifier;
            var airlines = store.airlines;

            let newTotalPriceList = flight.totalPriceList;
            newTotalPriceList = newTotalPriceList.sort((a, b) => {
                return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
            });

            let priceList = newTotalPriceList[0];
            // flight.totalPriceList.forEach((priceList) => {
                var price = parseInt(this.getTotalPrice(priceList));
                if(!store.price_range[activeTab]) {
                    store.price_range[activeTab] = {
                        'min':0,
                        'max':0,
                        'range':[0,0],
                    }
                }
                var min = parseInt(store.price_range[activeTab]['min']);
                var max = parseInt(store.price_range[activeTab]['max']);
                if(price < min) {
                    min = price;
                } else if(min==0) {
                    min = price;
                }
                if(price > max) {
                    max = price;
                }
                store.price_range[activeTab] = {
                    'min':min,
                    'max':max,
                    'range':[min,max],
                }

                // fareIdentifier Start
                if(store.filter_published_arr.indexOf(priceList.fareIdentifier) > -1) {
                    if(fare_identifier[activeTab] && fare_identifier[activeTab]['published']) {
                        fare_identifier[activeTab]['published']['counter'] = fare_identifier[activeTab]['published']['counter']+1;
                    } else {
                        if(!fare_identifier[activeTab]) {
                            fare_identifier[activeTab] = {};
                        }
                        fare_identifier[activeTab]['published'] = {'counter':1};
                    }
                } else if(store.filter_sme_arr.indexOf(priceList.fareIdentifier) > -1) {
                    if(fare_identifier[activeTab] && fare_identifier[activeTab]['sme']) {
                        fare_identifier[activeTab]['sme']['counter'] = fare_identifier[activeTab]['sme']['counter']+1;
                    } else {
                        if(!fare_identifier[activeTab]) {
                            fare_identifier[activeTab] = {};
                        }
                        fare_identifier[activeTab]['sme'] = {'counter':1};
                    }
                } else if(store.filter_corporate_arr.indexOf(priceList.fareIdentifier) > -1) {
                    if(fare_identifier[activeTab] && fare_identifier[activeTab]['corporate']) {
                        fare_identifier[activeTab]['corporate']['counter'] = fare_identifier[activeTab]['corporate']['counter']+1;
                    } else {
                        if(!fare_identifier[activeTab]) {
                            fare_identifier[activeTab] = {};
                        }
                        fare_identifier[activeTab]['corporate'] = {'counter':1};
                    }
                } else if(store.filter_sale_arr.indexOf(priceList.fareIdentifier) > -1) {
                    if(fare_identifier[activeTab] && fare_identifier[activeTab]['sale']) {
                        fare_identifier[activeTab]['sale']['counter'] = fare_identifier[activeTab]['sale']['counter']+1;
                    } else {
                        if(!fare_identifier[activeTab]) {
                            fare_identifier[activeTab] = {};
                        }
                        fare_identifier[activeTab]['sale'] = {'counter':1};
                    }
                }
                // fareIdentifier End

                // Airline Start
                var value = flight.sI[0];
                if(airlines[0] && airlines[0][value.fD.aI.code]) {
                    airlines[0][value.fD.aI.code]['counter'] = airlines[0][value.fD.aI.code]['counter']+1;
                } else {
                    if(!airlines[0]) {
                        airlines[0] = {};
                    }
                    airlines[0][value.fD.aI.code] = {'name':value.fD.aI.name,'price':this.getTotalPrice(priceList),'counter':1};
                }
                // Airline End
            //});

            store.fare_identifier = fare_identifier;
            store.airlines = airlines;
        },
        getBookingTotalPrice() {
            let bookingTotalPrice = 0;
            if(!this.showSingleBooking) {
                if(this.searchResult.tripInfos) {
                    if(this.searchResult.tripInfos.COMBO) {
                        //bookingTotalPrice += this.getInfoTotalPrice(store.priceIds.price_chk_COMBO_info,store.priceIds.price_chk_COMBO,this.paxInfo);
                    } else if(this.searchResult.tripInfos.ONWARD || this.searchResult.tripInfos.RETURN) {
                        if(this.searchResult.tripInfos.ONWARD) {
                            bookingTotalPrice += this.getInfoTotalPrice(store.priceIds.price_chk_ONWARD_info,store.priceIds.price_chk_ONWARD,this.paxInfo);
                        }
                        if(this.searchResult.tripInfos.RETURN) {
                            bookingTotalPrice += this.getInfoTotalPrice(store.priceIds.price_chk_RETURN_info,store.priceIds.price_chk_RETURN,this.paxInfo);
                        }
                    } else {
                        this.searchResult.tripInfos.forEach((tripInfos, index) => {
                            bookingTotalPrice = bookingTotalPrice + this.getInfoTotalPrice(store.priceIds[`price_chk_${index}_info`],store.priceIds[`price_chk_${index}`],this.paxInfo);
                        });
                    }
                }
            }
            this.bookingTotalPrice = bookingTotalPrice;
            return bookingTotalPrice;
        },
        handleSearchDate(tripKey, date_type) {
            // alert(tripKey+' = '+date_type);
            // console.log('this.routeInfos[0]=',this.routeInfos[0].travelDate)
            var routeInfos = this.routeInfos;
            if(tripKey == 'ONWARD' && routeInfos[0] && routeInfos[0].travelDate) {
                var cur_date = new Date();
                cur_date = new Date(moment(cur_date).format('YYYY-MM-DD'));
                var dep = routeInfos[0].travelDate;
                var new_dep = dep;
                if(date_type == 'previous') {
                    new_dep = new Date(moment(new Date(dep)).subtract(1, 'days'));
                } else {
                    new_dep = new Date(moment(new Date(dep)).add(1, 'days'));
                }
                if(moment(new_dep).isSameOrAfter(moment(cur_date))) {
                    if(routeInfos[1] && routeInfos[1].travelDate) {
                        var ret = routeInfos[1].travelDate;
                        ret = new Date(moment(ret).format('YYYY-MM-DD'));
                        if(moment(ret).isSameOrAfter(moment(new_dep))) {

                        } else {
                            alert('Please select date less than or equals to return date');
                            return false;
                        }
                    }
                    routeInfos[0].travelDate = moment(new_dep).format('YYYY-MM-DD');
                    this.routeInfos = routeInfos;
                    this.handleSearchFlights();
                } else {
                    alert('Please select date greater than or equals to today');
                    return false;
                }
            } else if(tripKey == 'RETURN' && routeInfos[1] && routeInfos[1].travelDate) {
                var cur_date = new Date();
                cur_date = new Date(moment(cur_date).format('YYYY-MM-DD'));
                var ret = routeInfos[1].travelDate;
                var new_ret = ret;
                if(date_type == 'previous') {
                    new_ret = new Date(moment(new Date(ret)).subtract(1, 'days'));
                } else {
                    new_ret = new Date(moment(new Date(ret)).add(1, 'days'));
                }
                if(moment(new_ret).isSameOrAfter(moment(cur_date))) {
                    var dep = routeInfos[0].travelDate;
                    dep = new Date(moment(dep).format('YYYY-MM-DD'));
                    if(moment(new_ret).isSameOrAfter(moment(dep))) {
                        routeInfos[1].travelDate = moment(new_ret).format('YYYY-MM-DD');
                        this.routeInfos = routeInfos;
                        this.handleSearchFlights();
                    } else {
                        alert('Please select date greater than or equals to departure date.');
                        return false;
                    }
                } else {
                    alert('Please select date greater than or equals to today');
                    return false;
                }
            }
        },
        handleErrors(){
            let TripType = this.tripType;

            if(this.routeInfos) {
                let multicity = {};
                this.routeInfos.forEach((routeInfo,index)=>{
                    // console.log('this.routeInfo=',this.routeInfo);
                    if(TripType == 0) {
                        if(index==0) {
                            this.fromCountry = routeInfo.fromCityOrAirport;
                            this.toCountry = routeInfo.toCityOrAirport;
                            this.departureDate = routeInfo.travelDate;
                            this.fromCountryList = this.airportLists;
                            this.toCountryList = this.airportLists;
                        }
                    } else if(TripType == 1) {
                        if(index==0) {
                            this.fromCountry = routeInfo.fromCityOrAirport;
                            this.toCountry = routeInfo.toCityOrAirport;
                            this.departureDate = routeInfo.travelDate;
                        }
                        if(index==1) {
                            // this.fromCountry = routeInfo.fromCityOrAirport;
                            // this.toCountry = routeInfo.toCityOrAirport;
                            this.returnDate = routeInfo.travelDate;
                        }

                        this.fromCountryList = this.airportLists;
                        this.toCountryList = this.airportLists;
                    } else {
                        if(index==0) {
                            this.fromCountry = routeInfo.fromCityOrAirport;
                            this.toCountry = routeInfo.toCityOrAirport;
                            this.departureDate = routeInfo.travelDate;

                            this.fromCountryList = this.airportLists;
                            this.toCountryList = this.airportLists;
                        } else {
                            multicity[`origin_${index}`] = routeInfo.fromCityOrAirport;
                            multicity[`destination_${index}`] = routeInfo.toCityOrAirport;
                            multicity[`depDate_${index}`] = routeInfo.travelDate;

                            this.multifromCountryList[index] = this.airportLists;
                            this.multitoCountryList[index] = this.airportLists;
                        }
                    }
                });
                if(Object.keys(multicity).length > 0){
                    this.multicity = multicity;
                }

                if(TripType==2 && this.routeInfos && this.routeInfos.length) {
                    this.multicityCounter = this.routeInfos.length-1;
                }
            }

            // console.log('this.paxInfo=',this.paxInfo);
            if(this.paxInfo){
                let paxInfo = this.paxInfo;
                if(paxInfo.ADULT) {
                    this.passangers.adult = parseInt(paxInfo.ADULT);
                }
                if(paxInfo.CHILD) {
                    this.passangers.children = parseInt(paxInfo.CHILD);
                }
                if(paxInfo.INFANT) {
                    this.passangers.infant = parseInt(paxInfo.INFANT);
                }
            }
            if(this.cabinClass) {
                this.BookingClass = this.showCabinClass(this.cabinClass);
            }

            var errors = {};
            var form_data = {
                ADT: this.passangers.adult,
                CHD: this.passangers.children,
                INF: this.passangers.infant,
                tripType: this.tripType,
                pClass : this.BookingClass
            }
            
            if(store.tripType == 2) {
                if(this.fromCountry.code) {
                    form_data['origin_0'] = this.fromCountry.code;
                    delete errors['fromCountryError'];
                } else {
                    errors['fromCountryError'] = 'Origin is required';
                }
                if(this.toCountry.code) {
                    form_data['destination_0'] = this.toCountry.code;
                    delete errors['toCountryError'];
                } else {
                    errors['toCountryError'] = 'Destination is required';
                }
                if(this.departureDate) {
                    form_data['depDate_0'] = moment(this.departureDate).format('YYYY-MM-DD');
                    delete errors['fromDateError'];
                } else {
                    errors['fromDateError'] = 'Departure Date is required';
                }

                var multicity = this.multicity;
                var multicityCounter = this.multicityCounter;
                for (var counter = 1; counter <= multicityCounter; counter++) {
                    if(multicity[`origin_${counter}`]['code']) {
                        form_data[`origin_${counter}`] = multicity[`origin_${counter}`]['code'];
                        delete errors[`fromCountryError${counter}`];
                    } else {
                        errors[`fromCountryError${counter}`] = 'Origin is required'
                    }
                    if(multicity[`destination_${counter}`]['code']) {
                        form_data[`destination_${counter}`] = multicity[`destination_${counter}`]['code'];
                        delete errors[`toCountryError${counter}`];
                    } else {
                        errors[`toCountryError${counter}`] = 'Destination is required';
                    }
                    if(multicity[`depDate_${counter}`]) {
                        form_data[`depDate_${counter}`] = moment(multicity[`depDate_${counter}`]).format('YYYY-MM-DD');
                        delete errors[`fromDateError${counter}`];
                    } else {
                        errors[`fromDateError${counter}`] = 'Departure Date is required';
                    }
                }
            } else {
                if(this.fromCountry.code) {
                    form_data['from'] = this.fromCountry.code;
                    delete errors['fromCountryError'];
                } else {
                    errors['fromCountryError'] = 'Origin is required';
                }
                if(this.toCountry.code) {
                    form_data['to'] = this.toCountry.code;
                    delete errors['toCountryError'];
                } else {
                    errors['toCountryError'] = 'Destination is required';
                }
                if(this.departureDate) {
                    form_data['dep'] =  moment(this.departureDate).format('YYYY-MM-DD');
                    delete errors['fromDateError'];
                } else {
                    errors['fromDateError'] = 'Departure Date is required';
                }
                if(store.tripType == 1) {
                    if(this.returnDate) {
                        form_data['ret'] = moment(this.returnDate).format('YYYY-MM-DD');
                        delete errors['toDateError'];
                    } else {
                        errors['toDateError'] = 'Return Date is required';
                    }
                }
            }
            this.errors = errors;
            return form_data;
        },
        handleSearchFlights(){
            this.isSearched = true;
            var form_data = this.handleErrors();
            // console.log('errors=',errors);
            // console.log(this.errors)
            if(Object.keys(this.errors).length <= 0){
                this.loading = true;
                this.errors = {};
                store.tripType = this.tripType;
                this.$inertia.get(`/flight/list`, form_data);
            }
        },
    },
    
    mounted () {
        var fare_identifier = store.fare_identifier;
        var airlines = store.airlines;
        var activeTab = this.activeTab;

        var showSingleBooking = false;
        if(this.searchResult.tripInfos) {
            if(this.searchResult.tripInfos.COMBO) {
                showSingleBooking = true;
                this.searchResult.tripInfos.COMBO.forEach((flight, index) => {
                    this.initiateFlightFilters(flight, activeTab);
                });
                this.handleSortData('COMBO','price','asc');
            } else if(this.searchResult.tripInfos.ONWARD || this.searchResult.tripInfos.RETURN) {
                showSingleBooking = true;
                if(this.searchResult.tripInfos.ONWARD) {
                    this.searchResult.tripInfos.ONWARD.forEach((flight, index) => {
                        this.initiateFlightFilters(flight, activeTab);
                    });
                    this.handleSortData('ONWARD','price','asc');
                }
                if(this.searchResult.tripInfos.RETURN) {
                    showSingleBooking = false;
                    this.searchResult.tripInfos.RETURN.forEach((flight, index) => {
                        this.initiateFlightFilters(flight,0);
                    });
                    this.handleSortData('RETURN','price','asc');
                }
            } else {
                this.searchResult.tripInfos.forEach((tripInfo, index) => {
                    tripInfo.forEach((flight, index2) => {
                        this.initiateFlightFilters(flight,index);
                    });
                    this.handleSortData(index,'price','asc');
                });
            }
        }
        this.showSingleBooking = showSingleBooking;

        this.routeInfos.forEach((value, index) => {
            store.filter_fareIdentifier[index] = '';
        });
        store.tripType = this.tripType;
        setTimeout(() => {
            $('.splitTable .flight_table:first-child').click(function(){
                $('.flight_res_right').removeClass('opened_right');
                $(this).siblings('.flight_table').find('tr').removeClass('active');
            });
            $('.splitTable .flight_table:nth-child(2)').click(function(){
                $('.flight_res_right').addClass('opened_right');
                $(this).siblings('.flight_table').find('tr').removeClass('active');
            });

            let startX, startY, endX, endY;

            $(".splitTable").on('touchstart', (event) => {
                startX = event.originalEvent.touches[0].clientX;
                startY = event.originalEvent.touches[0].clientY;
            });

            $(".splitTable").on('touchmove', (event) => {
                endX = event.originalEvent.touches[0].clientX;
                endY = event.originalEvent.touches[0].clientY;
            });

            $(".splitTable").on('touchend', () => {
                const deltaX = endX - startX;
                const deltaY = endY - startY;

                if (Math.abs(deltaX) > Math.abs(deltaY)) {
                // Horizontal swipe
                if (deltaX > 0) {
                    $('.flight_res_right').removeClass('opened_right');
                    $('.splitTable .flight_table:nth-child(2)').find('tr').removeClass('active');
                } else {
                    $('.flight_res_right').addClass('opened_right');
                    $('.splitTable .flight_table:first-child').find('tr').removeClass('active');
                }
                } else {
                // Vertical swipe
                if (deltaY > 0) {
                    console.log('Swiped down');
                } else {
                    console.log('Swiped up');
                }
                }
            });



            // $(".splitTable").on('swiperight',function(){
            //     console.log("Swiped right");
            // });
            
            // $(".splitTable").on('swipeleft',function(){
            //     console.log("Swiped left");
            // });

            // $(".splitTable").swipe({
            //     swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
            //         if (direction === "left") {
            //         // Handle swipe left
            //         console.log("Swiped left");
            //         } else if (direction === "right") {
            //         // Handle swipe right
            //         console.log("Swiped right");
            //         }
            //     }
            // });
        }, 100);
    },
    beforeDestroy (){

    },
    watch:{

    },
    components: {
        Link,
        Slider,
        FlightCard,
        FlightBookCard,
        SearchCountryForm,
        LottieAnimation,
        OneWayPageLoader,
        FlightResWrapper,
        FormTopMenu,
        'flight-head-serarch' : FlightHeadSerarch,
        FlightTopAreaWrapper
    },
    layout: Layout,
    props:["searchResult", "errors", "paxInfo", "routeInfos", "cabinClass", "tripType","totalFlights", "airportLists", "form_data", "url", "metaTitle","metaDescription","FLIGHT_URL","CAB_URL","HOTEL_URL","PACKAGE_URL","ACTIVITY_URL"]
}
</script>
