<template>
    <header class="text-black text-4xl text-center mt-7 font-medium">File Uploader</header>
    <form method="POST" action="/users/upload" enctype="multipart/form-data" class="w-5/6 h-1/4 mt-10 mx-auto flex flex-col items-center justify-center text-black border-blue-500 border-3 border-dashed rounded-2xl gap-4 cursor-pointer" @dragover.prevent="handleDragOver" @drop.prevent="handleDrop" @click="handleFormClick">
        <i class="fa-solid fa-images text-5xl"></i>
        <span class="text-2xl">Choose a file or drag it here</span>
        <input type="file"  ref="fileInput" name="myFiles" class="filepond file-input" multiple hidden @change="handleFileChange">
    </form>
    <div class="mt-12 h-2/4 w-5/6 mx-auto overflow-hidden flex flex-col text-black">
        <ul class="progress-area h-2/4 mb-3 flex flex-col gap-2 overflow-y-scroll scrollbar-none scroll-behavior-smooth">
            <progressComponent ref="progressRef" @upload-finished="handleUploadFinished"></progressComponent>
            <!-- <template v-for="file in allFile">
                <progressComponent
                    v-if="file.status === 'upload'"
                    :key="file.id"
                    :file-data="file"
                    ref="progressRef"
                    @upload-finished="handleUploadFinished"
                ></progressComponent>
            </template> -->
        </ul>
        <ul class="uploaded-area h-2/4 mt-3 flex flex-col gap-2 overflow-y-scroll scrollbar-none scroll-behavior-smooth">
            <template v-for="file in allFile">
                <uploadComponent
                    v-if="file.status === 'upload'"
                    :key="file.id"
                    :file-data="file"
                    :ref="'uploadRef_' + file.id"
                ></uploadComponent>
            </template>
        </ul>
    </div>
</template>
<script>
import progressComponent from './uploadForm/progress.vue';
import uploadComponent from './uploadForm/uploaded.vue';
export default{
    components:{
        progressComponent:progressComponent,
        uploadComponent:uploadComponent
    },
    data(){
        return{
            id:0,
            newData:[],
            allFile:[
                {
                    //format
                    // id:id,
                    // file:'random.png',
                    // status:['upload','done'],
                }
            ],
        }
    },
    methods:{
        handleFormClick() {
            this.$refs.fileInput.click();
        },
        handleFileChange(event) {
            this.handleFiles(event.target.files);
        },
        makeUpload() {
            this.newData = '';
        },
        handleDragOver(event) {
            event.preventDefault();
        },
        handleDrop(event) {
            event.preventDefault();
            this.handleFiles(event.dataTransfer.files);
        },
        handleFiles(files) {
            const progressRef = this.$refs.progressRef;
            if(progressRef){
                progressRef.validationFile('vdvsvav');
            }else{
                console.log('ra kenekekkk');
            }
            return;
            for (let i = 0; i < files.length; i++) {
                var resValidation = this.$refs.progressRef.validationFile(files[i]);
                console.log('mari');
                console.log(resValidation);
                return;
                this.allFile.push({
                    id:id,
                    file:files[i].name,
                    status:'upload'
                });
                if(resValidation.status == 'error'){
                    //show uploaded error
                }else{
                    //success upload file
                    this.$refs['progressRef_' + resValidation.data.id].uploadFile(files[i]);
                }
                console.log('');
            }
            console.log(this.allFile)
        },
        handleUploadFinished(){
            //
        }
    }
}
</script>