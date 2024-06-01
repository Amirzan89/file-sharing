<template>
    <li class="row relative bg-white rounded-xl">
        <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
        <div class="details absolute top-1 left-12">
            <span class="name">{{ internalFile.name }}</span>
            <div class="size">{{ internalFile.size }}</div>
        </div>
        <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
            <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                <div class="progress bg-blue-500 h-3 w-0" :style="{ width: progress }"></div>
            </div>
            <span class="percent">{{ Math.floor(parseFloat(progress)) }}%</span>
        </div>
        <div class="controls absolute right-4 top-4 text-xl flex gap-2">
            <i class="fa-solid fa-play cursor-pointer" v-if="internalFile.status === 'pause'" @click="continueUpload()"></i>
            <i class="fa-solid fa-pause cursor-pointer" v-if="internalFile.status === 'upload'" @click="pauseUpload()"></i>
            <i class="fa-solid fa-x cursor-pointer" @click="cancelUpload()"></i>
        </div>
    </li>
</template>
<script>
export default{
    emits: ['handle-files', 'delete-files'],
    props: ['internalFile', 'progress'],
    data(){
        return{
        }
    },
    methods:{
        continueUpload() {
            this.$emit('continue-upload', this.internalFile.id);
        },
        pauseUpload() {
            this.$emit('pause-upload', this.internalFile.id);
        },
        cancelUpload() {
            this.$emit('cancel-upload', this.internalFile.id);
        },
    }
}
</script>