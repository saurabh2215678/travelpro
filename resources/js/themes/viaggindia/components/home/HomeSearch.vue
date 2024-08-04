<template>
    <HomeSearchWrapper>
        <div class="container home_search_top">
            <div class="section_title">
                <p class="title_small">Special Features</p>
                <h2 class="title_big">See some benifit of joining us</h2>
            </div>
        </div>
        <SearchForm :isHomePage="true"/>
    </HomeSearchWrapper>
</template>
<script>
import SearchForm from '../SearchForm.vue';

import { HomeSearchWrapper } from '../../styles/HomeSearchWrapper';

export default {
    name:"HomeSearch",
    components: {
        HomeSearchWrapper,
        SearchForm
    }
}
</script>