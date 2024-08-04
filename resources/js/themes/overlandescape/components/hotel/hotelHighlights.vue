<template>
    <HeighlightWrapper id="content_features" v-if="featuresArray && featuresArray.length > 0">
        <div class="facility-highlights-item" :style="`--fullheight: ${listHeight}px`">
            <div class="highlights_facility_top mb-3 border border-slate-20 p-3">
                <h3 class="font-semibold pb-3">Highlights</h3>

                <div class="highlights_warapper" :class="{ active: isActive }">

                    <div class="arrow_btn cursor-pointer" @click="toggleActive" v-if="rows > 1">
                        <span class="show_more">{{ restItem }} more</span>
                        <span class="hide_option"><i class="fa-solid fa-angle-up"></i></span>
                    </div>

                    <ul class="flex flex-wrap highlights_facility" ref="listRef">
                        <li v-for="feature in featuresArray">
                            <img :src="feature.src" />
                            <span class="text-gray-600">{{feature.name}}</span>
                        </li>
                    </ul>
                </div>

            </div>                              
        </div>
    </HeighlightWrapper>
</template>

<script>
import styled from 'vue3-styled-components';

function getNumberOfRows(element) {
  const items = element.querySelectorAll('li');

  let containerWidth = element.offsetWidth;
  let currentRowWidth = 0;
  let itemsInCurrentRow = 0;
  let numberOfRows = 0;

  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    const itemWidth = item.offsetWidth;

    // Check if the item fits in the current row or not
    if (currentRowWidth + itemWidth <= containerWidth) {
      currentRowWidth += itemWidth;
      itemsInCurrentRow++;
    } else {
      // Move to the next row
      numberOfRows++;
      currentRowWidth = itemWidth;
      itemsInCurrentRow = 1;
    }
  }

  // If there are items in the last row, add it as a row
  if (itemsInCurrentRow > 0) {
    numberOfRows++;
  }

  return numberOfRows;
};



function getLengthOfLiExceptFirstRow(element) {
  const items = element.querySelectorAll('li');
  const containerRect = element.getBoundingClientRect();
  const containerTop = containerRect.top;

  let count = 0;

  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    const itemRect = item.getBoundingClientRect();
    const itemTop = itemRect.top;

    // Calculate the position of the item from the top of the container
    const positionFromTop = itemTop - containerTop;

    // Count the item if its position from top is greater than zero
    if (positionFromTop > 0) {
      count++;
    }
  }

  return count;
}


const HeighlightWrapper = styled.div`
& .highlights_warapper{
    height: 3rem;
    overflow: hidden;
    transition: 0.5s;
}
& .flex.highlights_facility li{
    height: 3rem;
    margin: 0;
    padding-right: 1rem;
}
& .flex.highlights_facility{
    margin-right: 2rem;
}
& .highlights_warapper.active {
    height: var(--fullheight);
}
& .hide_option{
    opacity: 0;
    transition: 0.5s;
}

& .highlights_warapper.active .hide_option {
    opacity: 1;
}
& .highlights_warapper.active .show_more {
    opacity: 0;
}
& .arrow_btn {
    --size: 3rem;
    width: var(--size);
    height: var(--size);
    top: 0!important;
    text-align: center;
    display: inline-grid!important;
    place-items: center;
    line-height: 1;
    border: 1px solid var(--black100);
    border-radius: 50%;
}
& .show_more {
    font-size: 0.85rem;
    width: 80%;
    transition: 0.5s;
    position: absolute;
}
`;

export default {
    created() {},
    data() {
        return {
            isActive: false,
            rows : 0,
            restItem : 0,
            listHeight : 0, 
        }
    },
    mounted () {
        this.setFeatureToggle();
        window.addEventListener('resize', this.setFeatureToggle);
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.setFeatureToggle);
    },
    methods:{
        toggleActive() {
            this.isActive = !this.isActive;
        },
        setFeatureToggle(){
            let listElement = this.$refs.listRef;
            this.rows = getNumberOfRows(listElement);
            this.restItem = getLengthOfLiExceptFirstRow(listElement);
            this.listHeight = listElement.offsetHeight;
        }
    },
    components:{
        HeighlightWrapper
    },
    props: ["featuresArray"],
}
</script>