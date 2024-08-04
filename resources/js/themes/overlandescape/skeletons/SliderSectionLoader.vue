<template>
    <div class="loading_sec p-8">
        <div class="xl:container xl:mx-auto">
            <div class="top_loader flex justify-between px-2 mb-8">
                <p class="w-36 skeleton_loading py-4">&nbsp;</p>
                <p class="w-36 skeleton_loading py-4">&nbsp;</p>
            </div>
            <div class="btm_loader flex">
                <div class="w-1/6 px-2"><div class="skeleton_loading py-40"></div></div>
                <div class="w-1/6 px-2"><div class="skeleton_loading py-40"></div></div>
                <div class="w-1/6 px-2"><div class="skeleton_loading py-40"></div></div>
                <div class="w-1/6 px-2"><div class="skeleton_loading py-40"></div></div>
                <div class="w-1/6 px-2"><div class="skeleton_loading py-40"></div></div>
                <div class="w-1/6 px-2"><div class="skeleton_loading py-40"></div></div>
            </div>
        </div>
    </div>
</template>
<script>
export default{
    name : "SliderSectionLoader"
}
</script>