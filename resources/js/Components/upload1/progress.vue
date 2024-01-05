<template>
    <li class="row relative bg-white rounded-xl">
        <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
        <div class="details absolute top-1 left-12">
            <span class="name">image_01.png+ Uploading</span>
            <div class="size">4MB</div>
        </div>
        <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
            <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                <div class="progress bg-blue-500 w-3/4 h-3"></div>
            </div>
            <span class="percent">50%</span>
        </div>
        <div class="controls absolute right-4 top-4 text-xl flex gap-2">
            <i class="fa-solid fa-play cursor-pointer"></i>
            <i class="fa-solid fa-pause cursor-pointer"></i>
            <i class="fa-solid fa-x cursor-pointer"></i>
        </div>
    </li>
</template>
<script>
export default{
    props:[''],
    data(){
        return{
            //
        }
    },
    methods:{
        
    }
}
</script>