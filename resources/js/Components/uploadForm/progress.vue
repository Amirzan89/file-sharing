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
            <i class="fa-solid fa-play cursor-pointer" @click="continueUpload()"></i>
            <i class="fa-solid fa-pause cursor-pointer" @click="pauseUpload()"></i>
            <i class="fa-solid fa-x cursor-pointer" @click="cancelUpload()"></i>
        </div>
    </li>
</template>
<script>
export default{
    props: ['internalFile', 'progress'],
    data(){
        return{
            file:[
                //format
                // id:id,
                // file:'random.png',
                // size:5MB,
                // status:['upload' or 'done'],
            ]
        }
    },
    methods:{
        progressBar($data){
            $this.percent = $data;
        },
        pauseUpload(idFile) {
            const xhr = this.file[idFile]?.process;
            if (xhr) {
                xhr.abort();
                this.$set(this.file, `status_${idFile}`, 'paused');
            }
        },
        cancelUpload(idFile) {
            // Get the XMLHttpRequest from the file array and abort it
            const xhr = this.file[idFile]?.process;
            if (xhr) {
                xhr.abort();
                this.$set(this.file, `status_${idFile}`, 'canceled');
            }
        },
        startUpload() {
            // Assume you have a server endpoint for handling resumable uploads
            // Initialize the resumable upload for each file
            this.files.forEach((file) => {
                parent.initializeUpload(uploadUrl, file);
            });
        },
    }
}
</script>