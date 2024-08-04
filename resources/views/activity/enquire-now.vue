<template>
   <!-- <link rel="stylesheet" href="http://localhost:8000/css/overland.css"> -->
   <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <SearchForm />
        <section class="theme_form" style="background-image: url(http://127.0.0.1:8000/assets/overlandescape/images/vision-bg.jpg);">
            <div class="container">
               <div class="theme_form_wrap rounded-lg">
                     <div class="theme_form_inner">
                        <h1 class="text-3xl font-semibold pb-2 px-2">
                           Enquire This Trip
                           <a class="go_back float-right" href="http://127.0.0.1:8000/package/highlights-of-ladakh-winter-package">
                                 <span class="arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                       <path d="M9.375 233.4l128-128c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H480c17.69 0 32 14.31 32 32s-14.31 32-32 32H109.3l73.38 73.38c12.5 12.5 12.5 32.75 0 45.25c-12.49 12.49-32.74 12.51-45.25 0l-128-128C-3.125 266.1-3.125 245.9 9.375 233.4z"/>
                                    </svg>
                                 </span>
                                 Back
                           </a>
                        </h1>
                        <div class="text-lg font700 px-2">Highlights of Ladakh Winter Package
                           <span class="text-xs text-teal-500 font700 px-2">6 NIGHTS</span>
                        </div>
                        <span class="package_tour_type_text px-2 p-1 ml-2 mb-2 bg-orange-700 rounded">Premium</span>
                        <div class="flash-message"></div>
                        <div class="book-space">
                           <div class="bookspace-inner">
                                 <form
                                    method="POST"
                                    action=""
                                    id="form_customize_your_trip"
                                    class="common_main_form needs-validation form_list"
                                    novalidate
                                    accept-charset="UTF-8"
                                    role="form"
                                    enctype="multipart/form-data"
                                 >
                                    <div class="ajax_msg"></div>
                                    <input
                                       type="hidden"
                                       id="g-recaptcha-response"
                                       name="g-recaptcha-response"
                                       class="g-recaptcha-response"
                                    >
                                    <input type="hidden" name="action" value="validate_captcha">
                                    <div class="formMessage"></div>
                                    <input type="hidden" name="_token" value="mh14FYsWOROSR0MHUfdHqpQPCyQ2ecLKCdceIaIq">
                                    <div class="form-floating mb-3 ">
                                       <label for="Name">Name</label>
                                       <input
                                             type="text"
                                             placeholder="Name*"
                                             required
                                             class="form-control "
                                             dataTypeInput="inputname"
                                             name="input42"
                                             value=""
                                       >
                                       <span class="input42_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 phone_code">
                                       <label for="Phone">Phone</label>
                                       <select name="input43_country_code" class="form-select country_code">
                                             <option value="+1-284">++1-284</option>
                                             <option value="+1-340">++1-340</option>
                                             <option value="+1-649">++1-649</option>
                                             <option value="+1-868">++1-868</option>
                                             <option value="0055">+0055</option>
                                             <option value="1">+1</option>
                                             <option value="1">+1</option>
                                             <option value="1">+1</option>
                                             <option value="1242">+1242</option>
                                             <option value="1246">+1246</option>
                                             <option value="1264">+1264</option>
                                             <option value="1268">+1268</option>
                                             <option value="1345">+1345</option>
                                             <option value="1441">+1441</option>
                                             <option value="1473">+1473</option>
                                             <option value="1664">+1664</option>
                                             <option value="1670">+1670</option>
                                             <option value="1671">+1671</option>
                                             <option value="1684">+1684</option>
                                             <option value="1721">+1721</option>
                                             <option value="1758">+1758</option>
                                             <option value="1767">+1767</option>
                                             <option value="1784">+1784</option>
                                             <option value="1787">+1787</option>
                                             <option value="1809">+1809</option>
                                             <option value="1869">+1869</option>
                                             <option value="1876">+1876</option>
                                             <option value="20">+20</option>
                                             <option value="211">+211</option>
                                             <option value="212">+212</option>
                                             <option value="212">+212</option>
                                             <option value="213">+213</option>
                                             <option value="216">+216</option>
                                             <option value="218">+218</option>
                                             <option value="220">+220</option>
                                             <option value="221">+221</option>
                                             <option value="222">+222</option>
                                             <option value="223">+223</option>
                                             <option value="224">+224</option>
                                             <option value="225">+225</option>
                                             <option value="226">+226</option>
                                             <option value="227">+227</option>
                                             <option value="228">+228</option>
                                             <option value="229">+229</option>
                                             <option value="230">+230</option>
                                             <option value="231">+231</option>
                                             <option value="232">+232</option>
                                             <option value="233">+233</option>
                                             <option value="234">+234</option>
                                             <option value="235">+235</option>
                                             <option value="236">+236</option>
                                             <option value="237">+237</option>
                                             <option value="238">+238</option>
                                             <option value="239">+239</option>
                                             <option value="240">+240</option>
                                             <option value="241">+241</option>
                                             <option value="242">+242</option>
                                             <option value="243">+243</option>
                                             <option value="244">+244</option>
                                             <option value="245">+245</option>
                                             <option value="246">+246</option>
                                             <option value="248">+248</option>
                                             <option value="249">+249</option>
                                             <option value="250">+250</option>
                                             <option value="251">+251</option>
                                             <option value="252">+252</option>
                                             <option value="253">+253</option>
                                             <option value="254">+254</option>
                                             <option value="255">+255</option>
                                             <option value="256">+256</option>
                                             <option value="257">+257</option>
                                             <option value="258">+258</option>
                                             <option value="260">+260</option>
                                             <option value="261">+261</option>
                                             <option value="262">+262</option>
                                             <option value="262">+262</option>
                                             <option value="262">+262</option>
                                             <option value="263">+263</option>
                                             <option value="264">+264</option>
                                             <option value="265">+265</option>
                                             <option value="266">+266</option>
                                             <option value="267">+267</option>
                                             <option value="268">+268</option>
                                             <option value="269">+269</option>
                                             <option value="27">+27</option>
                                             <option value="290">+290</option>
                                             <option value="291">+291</option>
                                             <option value="297">+297</option>
                                             <option value="298">+298</option>
                                             <option value="299">+299</option>
                                             <option value="30">+30</option>
                                             <option value="31">+31</option>
                                             <option value="32">+32</option>
                                             <option value="33">+33</option>
                                             <option value="34">+34</option>
                                             <option value="350">+350</option>
                                             <option value="351">+351</option>
                                             <option value="352">+352</option>
                                             <option value="353">+353</option>
                                             <option value="354">+354</option>
                                             <option value="355">+355</option>
                                             <option value="356">+356</option>
                                             <option value="357">+357</option>
                                             <option value="358">+358</option>
                                             <option value="35818">+35818</option>
                                             <option value="359">+359</option>
                                             <option value="36">+36</option>
                                             <option value="370">+370</option>
                                             <option value="371">+371</option>
                                             <option value="372">+372</option>
                                             <option value="373">+373</option>
                                             <option value="374">+374</option>
                                             <option value="375">+375</option>
                                             <option value="376">+376</option>
                                             <option value="377">+377</option>
                                             <option value="378">+378</option>
                                             <option value="379">+379</option>
                                             <option value="380">+380</option>
                                             <option value="381">+381</option>
                                             <option value="382">+382</option>
                                             <option value="383">+383</option>
                                             <option value="385">+385</option>
                                             <option value="386">+386</option>
                                             <option value="387">+387</option>
                                             <option value="389">+389</option>
                                             <option value="39">+39</option>
                                             <option value="40">+40</option>
                                             <option value="41">+41</option>
                                             <option value="420">+420</option>
                                             <option value="421">+421</option>
                                             <option value="423">+423</option>
                                             <option value="43">+43</option>
                                             <option value="44">+44</option>
                                             <option value="441481">+441481</option>
                                             <option value="441534">+441534</option>
                                             <option value="441624">+441624</option>
                                             <option value="45">+45</option>
                                             <option value="46">+46</option>
                                             <option value="47">+47</option>
                                             <option value="47">+47</option>
                                             <option value="48">+48</option>
                                             <option value="49">+49</option>
                                             <option value="500">+500</option>
                                             <option value="500">+500</option>
                                             <option value="501">+501</option>
                                             <option value="502">+502</option>
                                             <option value="503">+503</option>
                                             <option value="504">+504</option>
                                             <option value="505">+505</option>
                                             <option value="506">+506</option>
                                             <option value="507">+507</option>
                                             <option value="508">+508</option>
                                             <option value="509">+509</option>
                                             <option value="51">+51</option>
                                             <option value="52">+52</option>
                                             <option value="53">+53</option>
                                             <option value="54">+54</option>
                                             <option value="55">+55</option>
                                             <option value="56">+56</option>
                                             <option value="57">+57</option>
                                             <option value="58">+58</option>
                                             <option value="590">+590</option>
                                             <option value="590">+590</option>
                                             <option value="590">+590</option>
                                             <option value="591">+591</option>
                                             <option value="592">+592</option>
                                             <option value="593">+593</option>
                                             <option value="594">+594</option>
                                             <option value="595">+595</option>
                                             <option value="596">+596</option>
                                             <option value="597">+597</option>
                                             <option value="598">+598</option>
                                             <option value="599">+599</option>
                                             <option value="599">+599</option>
                                             <option value="60">+60</option>
                                             <option value="61">+61</option>
                                             <option value="61">+61</option>
                                             <option value="61">+61</option>
                                             <option value="62">+62</option>
                                             <option value="63">+63</option>
                                             <option value="64">+64</option>
                                             <option value="65">+65</option>
                                             <option value="66">+66</option>
                                             <option value="670">+670</option>
                                             <option value="672">+672</option>
                                             <option value="672">+672</option>
                                             <option value="672">+672</option>
                                             <option value="673">+673</option>
                                             <option value="674">+674</option>
                                             <option value="675">+675</option>
                                             <option value="676">+676</option>
                                             <option value="677">+677</option>
                                             <option value="678">+678</option>
                                             <option value="679">+679</option>
                                             <option value="680">+680</option>
                                             <option value="681">+681</option>
                                             <option value="682">+682</option>
                                             <option value="683">+683</option>
                                             <option value="685">+685</option>
                                             <option value="686">+686</option>
                                             <option value="687">+687</option>
                                             <option value="688">+688</option>
                                             <option value="689">+689</option>
                                             <option value="690">+690</option>
                                             <option value="691">+691</option>
                                             <option value="692">+692</option>
                                             <option value="7">+7</option>
                                             <option value="7">+7</option>
                                             <option value="81">+81</option>
                                             <option value="82">+82</option>
                                             <option value="84">+84</option>
                                             <option value="850">+850</option>
                                             <option value="852">+852</option>
                                             <option value="853">+853</option>
                                             <option value="855">+855</option>
                                             <option value="856">+856</option>
                                             <option value="86">+86</option>
                                             <option value="870">+870</option>
                                             <option value="880">+880</option>
                                             <option value="886">+886</option>
                                             <option value="90">+90</option>
                                             <option value="91" selected>+91</option>
                                             <option value="92">+92</option>
                                             <option value="93">+93</option>
                                             <option value="94">+94</option>
                                             <option value="95">+95</option>
                                             <option value="960">+960</option>
                                             <option value="961">+961</option>
                                             <option value="962">+962</option>
                                             <option value="963">+963</option>
                                             <option value="964">+964</option>
                                             <option value="965">+965</option>
                                             <option value="966">+966</option>
                                             <option value="967">+967</option>
                                             <option value="968">+968</option>
                                             <option value="970">+970</option>
                                             <option value="971">+971</option>
                                             <option value="972">+972</option>
                                             <option value="973">+973</option>
                                             <option value="974">+974</option>
                                             <option value="975">+975</option>
                                             <option value="976">+976</option>
                                             <option value="977">+977</option>
                                             <option value="98">+98</option>
                                             <option value="992">+992</option>
                                             <option value="993">+993</option>
                                             <option value="994">+994</option>
                                             <option value="995">+995</option>
                                             <option value="996">+996</option>
                                             <option value="998">+998</option>
                                       </select>
                                       <input
                                             type="text"
                                             required
                                             placeholder="Phone"
                                             class="form-control phone"
                                             dataTypeInput="phone"
                                             name="input43"
                                             value=""
                                             maxlength="12"
                                             onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                       >
                                       <span class="input43_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                       <label for="Email">Email</label>
                                       <input
                                             type="email"
                                             placeholder="Email*"
                                             required
                                             class="form-control "
                                             dataTypeInput="email"
                                             name="input44"
                                             value=""
                                       >
                                       <span class="input44_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                       <label for="Country">Country</label>
                                       <select class="form-select " required name="input45">
                                             <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                                             <option value="Virgin Islands (US)">Virgin Islands (US)</option>
                                             <option value="Turks And Caicos Islands">Turks And Caicos Islands</option>
                                             <option value="Trinidad And Tobago">Trinidad And Tobago</option>
                                             <option value="Bouvet Island">Bouvet Island</option>
                                             <option value="Canada">Canada</option>
                                             <option value="United States">United States</option>
                                             <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                             <option value="The Bahamas">The Bahamas</option>
                                             <option value="Barbados">Barbados</option>
                                             <option value="Anguilla">Anguilla</option>
                                             <option value="Antigua And Barbuda">Antigua And Barbuda</option>
                                             <option value="Cayman Islands">Cayman Islands</option>
                                             <option value="Bermuda">Bermuda</option>
                                             <option value="Grenada">Grenada</option>
                                             <option value="Montserrat">Montserrat</option>
                                             <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                             <option value="Guam">Guam</option>
                                             <option value="American Samoa">American Samoa</option>
                                             <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                                             <option value="Saint Lucia">Saint Lucia</option>
                                             <option value="Dominica">Dominica</option>
                                             <option value="Saint Vincent And The Grenadines">Saint Vincent And The Grenadines</option>
                                             <option value="Puerto Rico">Puerto Rico</option>
                                             <option value="Dominican Republic">Dominican Republic</option>
                                             <option value="Saint Kitts And Nevis">Saint Kitts And Nevis</option>
                                             <option value="Jamaica">Jamaica</option>
                                             <option value="Egypt">Egypt</option>
                                             <option value="South Sudan">South Sudan</option>
                                             <option value="Morocco">Morocco</option>
                                             <option value="Western Sahara">Western Sahara</option>
                                             <option value="ALGERIA">ALGERIA</option>
                                             <option value="Tunisia">Tunisia</option>
                                             <option value="Libya">Libya</option>
                                             <option value="Gambia The">Gambia The</option>
                                             <option value="Senegal">Senegal</option>
                                             <option value="Mauritania">Mauritania</option>
                                             <option value="Mali">Mali</option>
                                             <option value="Guinea">Guinea</option>
                                             <option value="Cote D&#039;Ivoire (Ivory Coast)">Cote D&#039;Ivoire (Ivory Coast)</option>
                                             <option value="Burkina Faso">Burkina Faso</option>
                                             <option value="Niger">Niger</option>
                                             <option value="Togo">Togo</option>
                                             <option value="Benin">Benin</option>
                                             <option value="Mauritius">Mauritius</option>
                                             <option value="Liberia">Liberia</option>
                                             <option value="Sierra Leone">Sierra Leone</option>
                                             <option value="Ghana">Ghana</option>
                                             <option value="Nigeria">Nigeria</option>
                                             <option value="Chad">Chad</option>
                                             <option value="Central African Republic">Central African Republic</option>
                                             <option value="Cameroon">Cameroon</option>
                                             <option value="Cape Verde">Cape Verde</option>
                                             <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                             <option value="Equatorial Guinea">Equatorial Guinea</option>
                                             <option value="Gabon">Gabon</option>
                                             <option value="Congo">Congo</option>
                                             <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
                                             <option value="Angola">Angola</option>
                                             <option value="Guinea-Bissau">Guinea-Bissau</option>
                                             <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                             <option value="Seychelles">Seychelles</option>
                                             <option value="Sudan">Sudan</option>
                                             <option value="Rwanda">Rwanda</option>
                                             <option value="Ethiopia">Ethiopia</option>
                                             <option value="Somalia">Somalia</option>
                                             <option value="Djibouti">Djibouti</option>
                                             <option value="Kenya">Kenya</option>
                                             <option value="Tanzania">Tanzania</option>
                                             <option value="Uganda">Uganda</option>
                                             <option value="Burundi">Burundi</option>
                                             <option value="Mozambique">Mozambique</option>
                                             <option value="Zambia">Zambia</option>
                                             <option value="Madagascar">Madagascar</option>
                                             <option value="French Southern Territories">French Southern Territories</option>
                                             <option value="Mayotte">Mayotte</option>
                                             <option value="Reunion">Reunion</option>
                                             <option value="Zimbabwe">Zimbabwe</option>
                                             <option value="Namibia">Namibia</option>
                                             <option value="Malawi">Malawi</option>
                                             <option value="Lesotho">Lesotho</option>
                                             <option value="Botswana">Botswana</option>
                                             <option value="Swaziland">Swaziland</option>
                                             <option value="Comoros">Comoros</option>
                                             <option value="South Africa">South Africa</option>
                                             <option value="Saint Helena">Saint Helena</option>
                                             <option value="Eritrea">Eritrea</option>
                                             <option value="Aruba">Aruba</option>
                                             <option value="Faroe Islands">Faroe Islands</option>
                                             <option value="Greenland">Greenland</option>
                                             <option value="Greece">Greece</option>
                                             <option value="Netherlands">Netherlands</option>
                                             <option value="Belgium">Belgium</option>
                                             <option value="France">France</option>
                                             <option value="Spain">Spain</option>
                                             <option value="Gibraltar">Gibraltar</option>
                                             <option value="Portugal">Portugal</option>
                                             <option value="Luxembourg">Luxembourg</option>
                                             <option value="Ireland">Ireland</option>
                                             <option value="Iceland">Iceland</option>
                                             <option value="ALBANIA">ALBANIA</option>
                                             <option value="Malta">Malta</option>
                                             <option value="Cyprus">Cyprus</option>
                                             <option value="Finland">Finland</option>
                                             <option value="ALAND ISLANDS">ALAND ISLANDS</option>
                                             <option value="Bulgaria">Bulgaria</option>
                                             <option value="Hungary">Hungary</option>
                                             <option value="Lithuania">Lithuania</option>
                                             <option value="Latvia">Latvia</option>
                                             <option value="Estonia">Estonia</option>
                                             <option value="Moldova">Moldova</option>
                                             <option value="Armenia">Armenia</option>
                                             <option value="Belarus">Belarus</option>
                                             <option value="Andorra">Andorra</option>
                                             <option value="Monaco">Monaco</option>
                                             <option value="San Marino">San Marino</option>
                                             <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
                                             <option value="Ukraine">Ukraine</option>
                                             <option value="Serbia">Serbia</option>
                                             <option value="Montenegro">Montenegro</option>
                                             <option value="Kosovo">Kosovo</option>
                                             <option value="Croatia">Croatia</option>
                                             <option value="Slovenia">Slovenia</option>
                                             <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                             <option value="Macedonia">Macedonia</option>
                                             <option value="Italy">Italy</option>
                                             <option value="Romania">Romania</option>
                                             <option value="Switzerland">Switzerland</option>
                                             <option value="Czech Republic">Czech Republic</option>
                                             <option value="Slovakia">Slovakia</option>
                                             <option value="Liechtenstein">Liechtenstein</option>
                                             <option value="Austria">Austria</option>
                                             <option value="United Kingdom">United Kingdom</option>
                                             <option value="Guernsey and Alderney">Guernsey and Alderney</option>
                                             <option value="Jersey">Jersey</option>
                                             <option value="Man (Isle of)">Man (Isle of)</option>
                                             <option value="Denmark">Denmark</option>
                                             <option value="Sweden">Sweden</option>
                                             <option value="Norway">Norway</option>
                                             <option value="Svalbard And Jan Mayen Islands">Svalbard And Jan Mayen Islands</option>
                                             <option value="Poland">Poland</option>
                                             <option value="Germany">Germany</option>
                                             <option value="Falkland Islands">Falkland Islands</option>
                                             <option value="South Georgia">South Georgia</option>
                                             <option value="Belize">Belize</option>
                                             <option value="Guatemala">Guatemala</option>
                                             <option value="El Salvador">El Salvador</option>
                                             <option value="Honduras">Honduras</option>
                                             <option value="Nicaragua">Nicaragua</option>
                                             <option value="Costa Rica">Costa Rica</option>
                                             <option value="Panama">Panama</option>
                                             <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                             <option value="Haiti">Haiti</option>
                                             <option value="Peru">Peru</option>
                                             <option value="Mexico">Mexico</option>
                                             <option value="Cuba">Cuba</option>
                                             <option value="Argentina">Argentina</option>
                                             <option value="Brazil">Brazil</option>
                                             <option value="Chile">Chile</option>
                                             <option value="Colombia">Colombia</option>
                                             <option value="Venezuela">Venezuela</option>
                                             <option value="Guadeloupe">Guadeloupe</option>
                                             <option value="Saint-Barthelemy">Saint-Barthelemy</option>
                                             <option value="Saint-Martin (French part)">Saint-Martin (French part)</option>
                                             <option value="Bolivia">Bolivia</option>
                                             <option value="Guyana">Guyana</option>
                                             <option value="Ecuador">Ecuador</option>
                                             <option value="French Guiana">French Guiana</option>
                                             <option value="Paraguay">Paraguay</option>
                                             <option value="Martinique">Martinique</option>
                                             <option value="Suriname">Suriname</option>
                                             <option value="Uruguay">Uruguay</option>
                                             <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                             <option value="Curaçao">Curaçao</option>
                                             <option value="Malaysia">Malaysia</option>
                                             <option value="Australia">Australia</option>
                                             <option value="Christmas Island">Christmas Island</option>
                                             <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                             <option value="Indonesia">Indonesia</option>
                                             <option value="Philippines">Philippines</option>
                                             <option value="New Zealand">New Zealand</option>
                                             <option value="Singapore">Singapore</option>
                                             <option value="Thailand">Thailand</option>
                                             <option value="East Timor">East Timor</option>
                                             <option value="Antarctica">Antarctica</option>
                                             <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                             <option value="Norfolk Island">Norfolk Island</option>
                                             <option value="Brunei">Brunei</option>
                                             <option value="Nauru">Nauru</option>
                                             <option value="Papua new Guinea">Papua new Guinea</option>
                                             <option value="Tonga">Tonga</option>
                                             <option value="Solomon Islands">Solomon Islands</option>
                                             <option value="Vanuatu">Vanuatu</option>
                                             <option value="Fiji Islands">Fiji Islands</option>
                                             <option value="Palau">Palau</option>
                                             <option value="Wallis And Futuna Islands">Wallis And Futuna Islands</option>
                                             <option value="Cook Islands">Cook Islands</option>
                                             <option value="Niue">Niue</option>
                                             <option value="Samoa">Samoa</option>
                                             <option value="Kiribati">Kiribati</option>
                                             <option value="New Caledonia">New Caledonia</option>
                                             <option value="Tuvalu">Tuvalu</option>
                                             <option value="French Polynesia">French Polynesia</option>
                                             <option value="Tokelau">Tokelau</option>
                                             <option value="Micronesia">Micronesia</option>
                                             <option value="Marshall Islands">Marshall Islands</option>
                                             <option value="Kazakhstan">Kazakhstan</option>
                                             <option value="Russia">Russia</option>
                                             <option value="Japan">Japan</option>
                                             <option value="South Korea">South Korea</option>
                                             <option value="Vietnam">Vietnam</option>
                                             <option value="North Korea">North Korea</option>
                                             <option value="Hong Kong S.A.R.">Hong Kong S.A.R.</option>
                                             <option value="Macau S.A.R.">Macau S.A.R.</option>
                                             <option value="Cambodia">Cambodia</option>
                                             <option value="Laos">Laos</option>
                                             <option value="China">China</option>
                                             <option value="Pitcairn Island">Pitcairn Island</option>
                                             <option value="Bangladesh">Bangladesh</option>
                                             <option value="Taiwan">Taiwan</option>
                                             <option value="Turkey">Turkey</option>
                                             <option value="India" selected>India</option>
                                             <option value="Pakistan">Pakistan</option>
                                             <option value="AFGHANISTAN">AFGHANISTAN</option>
                                             <option value="Sri Lanka">Sri Lanka</option>
                                             <option value="Myanmar">Myanmar</option>
                                             <option value="Maldives">Maldives</option>
                                             <option value="Lebanon">Lebanon</option>
                                             <option value="Jordan">Jordan</option>
                                             <option value="Syria">Syria</option>
                                             <option value="Iraq">Iraq</option>
                                             <option value="Kuwait">Kuwait</option>
                                             <option value="Saudi Arabia">Saudi Arabia</option>
                                             <option value="Yemen">Yemen</option>
                                             <option value="Oman">Oman</option>
                                             <option value="Palestinian Territory Occupied">Palestinian Territory Occupied</option>
                                             <option value="United Arab Emirates">United Arab Emirates</option>
                                             <option value="Israel">Israel</option>
                                             <option value="Bahrain">Bahrain</option>
                                             <option value="Qatar">Qatar</option>
                                             <option value="Bhutan">Bhutan</option>
                                             <option value="Mongolia">Mongolia</option>
                                             <option value="Nepal">Nepal</option>
                                             <option value="Iran">Iran</option>
                                             <option value="Tajikistan">Tajikistan</option>
                                             <option value="Turkmenistan">Turkmenistan</option>
                                             <option value="Azerbaijan">Azerbaijan</option>
                                             <option value="Georgia">Georgia</option>
                                             <option value="Kyrgyzstan">Kyrgyzstan</option>
                                             <option value="Uzbekistan">Uzbekistan</option>
                                       </select>
                                       <span class="input45_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                       <label for="Zip Code">Zip Code</label>
                                       <input
                                             type="text"
                                             required
                                             placeholder="Zip Code"
                                             class="form-control "
                                             dataTypeInput="zipcode"
                                             name="input46"
                                             value=""
                                             maxlength="10"
                                       >
                                       <span class="input46_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                       <label for="When Do You Want To Travel?">When Do You Want To Travel?</label>
                                       <div class="input-group date" dataTypeInput="datepicker">
                                             <input
                                                type="text"
                                                placeholder="When Do You Want To Travel?"
                                                class="form-control datepicker "
                                                name="input47"
                                                value=""
                                             >
                                             <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                             </span>
                                       </div>
                                       <span class="input47_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                       <label for="Number Of Packs">Number Of Packs</label>
                                       <input
                                             type="text"
                                             placeholder="Number Of Packs"
                                             class="form-control "
                                             dataTypeInput="number"
                                             name="input48"
                                             value=""
                                             onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                       >
                                       <span class="input48_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 ">
                                       <label for="Duration Of Stay">Duration Of Stay</label>
                                       <input
                                             type="text"
                                             placeholder="Duration Of Stay"
                                             class="form-control "
                                             dataTypeInput="number"
                                             name="input49"
                                             value=""
                                             onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                       >
                                       <span class="input49_error error"></span>
                                    </div>
                                    <div class="form-floating mb-3 radio_wrapper w-100 pb-2 pt-2 ">
                                       <label for="Do You Need Flight Also?">Do You Need Flight Also?</label>
                                       <input
                                             type="radio"
                                             class="form-check-input "
                                             dataTypeInput="radio"
                                             id="form_radio50_0"
                                             name="input50"
                                             value="1"
                                       >
                                       <label for="form_radio50_0">Yes</label>
                                       <input
                                             type="radio"
                                             class="form-check-input "
                                             dataTypeInput="radio"
                                             id="form_radio50_1"
                                             name="input50"
                                             value="0"
                                       >
                                       <label for="form_radio50_1">No</label>
                                    </div>
                                    <div class="form-floating mb-3 radio_wrapper w-100 pb-2 pt-2 ">
                                       <label for="Have You Travelled With Us Before?">Have You Travelled With Us Before?</label>
                                       <input
                                             type="radio"
                                             class="form-check-input "
                                             dataTypeInput="radio"
                                             id="form_radio51_0"
                                             required
                                             name="input51"
                                             value="1"
                                       >
                                       <label for="form_radio51_0">Yes</label>
                                       <input
                                             type="radio"
                                             class="form-check-input "
                                             dataTypeInput="radio"
                                             id="form_radio51_1"
                                             required
                                             name="input51"
                                             value="0"
                                       >
                                       <label for="form_radio51_1">No</label>
                                    </div>
                                    <div class="form-floating mb-3 w-full">
                                       <label for="Comments">Comments</label>
                                       <textarea
                                             placeholder="Comments*"
                                             required
                                             class="form-control "
                                             dataTypeInput="textarea"
                                             name="input52"
                                             style="height: 100px;"
                                       ></textarea>
                                       <span class="input52_error error"></span>
                                    </div>
                                    <input type="hidden" name="hidden_package" value="274">
                                    <input type="hidden" name="form_slug" value="customize_your_trip">
                                    <input type="hidden" name="refer_url" value="http://127.0.0.1:8000/enquire-this-trip">
                                    <div class="btn-book-space">
                                       <button class="btn primary-btn">Submit</button>
                                    </div>
                                 </form>
                           </div>
                        </div>
                     </div>
               </div>
            </div>
         </section>

    </Layout>
</template>

<script>
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
// https://www.npmjs.com/package/vue-toast-notification
import SearchForm from './components/SearchForm.vue';
import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";




const $toast = useToast();

export default {
    created() {
      document.body.classList.add('enquire-now');
    },
    beforeUnmount() {
      document.body.classList.remove('enquire-now');
   },
    data() {
        return {
            test : "test",
            store,
        }
    },
    methods: {
        showToast(toastObj){
            $toast.open(toastObj);
        }
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        Layout,
        SearchForm,
        Link
    },
    props: ["destinationLists", "airportLists", "sightseeningDestinationLists","originSightSeenRouteData", "routeList", "tourCategories",  "activityPackages", "featuredPackages", "nonFeaturedPackages","metaTitle" , "metaDescription","FLIGHT_URL","CAB_URL","HOTEL_URL","PACKAGE_URL","ACTIVITY_URL"],
};
</script>