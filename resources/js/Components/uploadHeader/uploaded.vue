<template>
    <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
        <i class="fas fa-file-alt text-3xl absolute left-5"></i>
        <div class="details absolute  left-15">
            <span class="name">image_01 + Uploaded</span>
            <div class="size">70KB</div>
        </div>
        <div class="controls absolute right-3 text-2xl flex gap-3">
            <i class="fas fa-check"></i>
            <i class="fa-solid fa-trash"></i>
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
        //
    }
}
</script>