<template>
    <header class="text-black text-4xl text-center mt-7 font-medium">File Uploader</header>
    <form method="POST" action="/users/upload" enctype="multipart/form-data" class="w-5/6 h-1/4 mt-10 mx-auto flex flex-col items-center justify-center text-black border-blue-500 border-3 border-dashed rounded-2xl gap-4 cursor-pointer" @dragover.prevent="handleDragOver" @drop.prevent="handleDrop" @click="handleFormClick">
        <i class="fa-solid fa-images text-5xl"></i>
        <span class="text-2xl">Choose a file or drag it here</span>
        <input type="file"  ref="fileInput" name="myFiles" class="filepond file-input" multiple hidden @change="handleFileChange">
    </form>
    <div class="mt-12 h-2/4 w-5/6 mx-auto overflow-hidden flex flex-col text-black">
        <ul class="progress-area h-2/4 mb-3 flex flex-col gap-2 overflow-y-scroll scrollbar-none scroll-behavior-smooth">
            <template v-for="file in progressFiles">
                <progressComponent
                    v-if="file.status !== 'done' || file.status !== 'error'" 
                    :key="file.id"
                    :internalFile="getFileUpload(file)"
                    :progress="file.progress"
                    :ref="'progressRef_' + file.id"
                    @upload-finished="handleUploadFinished"
                    @continue-upload="continueUpload"
                    @pause-upload="pauseUpload" 
                    @cancel-upload="cancelUpload"
                ></progressComponent>
            </template>
        </ul>
        <ul class="uploaded-area h-2/4 mt-3 flex flex-col gap-2 overflow-y-scroll scrollbar-none scroll-behavior-smooth">
            <template v-for="file in uploadedFiles">
                <uploadComponent
                    v-if="file.status === 'done' || file.status === 'error'"
                    :key="file.id"
                    :internalFile="getFileDone(file)"
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
            progressFiles:[],
            uploadedFiles:[],
            progressFiles:[
                // {
                //     format
                //     id:id,
                //     file:'random.png',
                //     size:size
                //     progress:0%,
                //     status:['upload' or 'done','pause','cancel'],
                // }
            ],
            domain: window.location.protocol + '//' + window.location.hostname + ':' + window.location.port,
        }
    },
    methods:{
        handleFormClick() {
            this.$refs.fileInput.click();
        },
        handleFileChange(event) {
            this.handleFiles(event.target.files);
        },
        handleDragOver(event) {
            event.preventDefault();
        },
        handleDrop(event) {
            event.preventDefault();
            this.handleFiles(event.dataTransfer.files);
        },
        getFileUpload(file) {
            const fileData = { ...file };
            delete fileData.fileData;
            delete fileData.process;
            return fileData;
        },
        getFileDone(file) {
            const fileData = { ...file };
            delete fileData.fileData;
            delete fileData.process;
            delete fileData.progress;
            return fileData;
        },
        validationFile(file){
            return axios.post(this.domain + '/upload/validate', {
                name: file.name,
                format: file.name.slice((file.name.lastIndexOf(".") - 1 >>> 0) + 2),
                size: file.size,
            }).then(function(res){
                return { status: 'success', data: res.data };
            }).catch(function(err){
                return { status: 'error', message: err.response.data.message };
            });
        },
        continueUpload(idFile) {
            for (let i = 0; i < this.progressFiles.length; i++) {
                if (this.progressFiles[i].id === idFile) {
                    this.progressFiles[i].status = 'upload';
                    var chunk = this.progressFiles[i].process;
                    var fileData = this.progressFiles[i].fileData;
                    this.initializeUpload(fileData, idFile, chunk);
                }
            };
        },
        pauseUpload(idFile) {
            for (let i = 0; i < this.progressFiles.length; i++) {
                if (this.progressFiles[i].id === idFile) {
                    this.progressFiles[i].status = 'pause';
                    return;
                }
            };
        },
        cancelUpload(idFile) {
            for (let i = 0; i < this.progressFiles.length; i++) {
                if (this.progressFiles[i].id === idFile) {
                    this.progressFiles.splice(i, 1);
                    return;
                }
            };
        },
        uploadChunk(formData, onProgress, onComplete) {
            const xhr = axios.create({
                baseURL: this.domain
            });
            xhr.interceptors.request.use(config => {
                config.onUploadProgress = event => {
                    const percent = (event.loaded / event.total) * 100;
                    onProgress(percent.toFixed(2));
                };
                return config;
            });
            xhr.post('/upload/file', formData).then(() => {
                onComplete();
            }).catch(error => {
                console.error('Error uploading:', error);
            });
        },
        async initializeUpload(file,idFile, currentChunk = 0) {
            const chunkSize = 1024 * 1024;
            const totalChunks = Math.ceil(file.size / chunkSize);
            let uploadedBytes = currentChunk * chunkSize;
            const onProgress = () => {
                const overallProgress = Math.min((uploadedBytes / file.size) * 100, 100).toFixed(2);
                for (let i = 0; i < this.progressFiles.length; i++) {
                    if (this.progressFiles[i].id === idFile) {
                        this.progressFiles[i].progress = `${overallProgress}%`;
                        break;
                    }
                };
            };
            const uploadNextChunk = () => {
                for (let i = 0; i < this.progressFiles.length; i++) {
                    if (this.progressFiles[i].id === idFile){
                        this.progressFiles[i].process = currentChunk;
                        if(this.progressFiles[i].status === 'pause' || this.progressFiles[i].status === 'cancel') {
                            return;
                        }
                    }
                };
                const start = (currentChunk - 1) * chunkSize;
                const end = Math.min(currentChunk * chunkSize, file.size);
                const chunk = file.slice(start, end);
                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('currentChunk', currentChunk);
                formData.append('totalChunks', totalChunks);
                formData.append('identifier', idFile);
                return new Promise((resolve) => {
                    this.uploadChunk(formData, onProgress, () => {
                        currentChunk++;
                        uploadedBytes += end - start;
                        if (currentChunk <= totalChunks) {
                            resolve(uploadNextChunk());
                        } else {
                            for (let i = 0; i < this.progressFiles.length; i++) {
                                if (this.progressFiles[i].id === idFile) {
                                    this.progressFiles[i].status = 'done';
                                    const uploadedItem = this.progressFiles.splice(i, 1)[0];
                                    this.uploadedFiles.push(uploadedItem);
                                    return;
                                }
                            };
                            resolve();
                        }
                    });
                });
            };
            return uploadNextChunk();
        },

        async handleFiles(files) {
            const formatFileSize = function (bytes){
                if (bytes < 1024) {
                    return bytes + ' B';
                } else if (bytes < 1024 * 1024) {
                    return (bytes / 1024).toFixed(2) + ' KB';
                } else if (bytes < 1024 * 1024 * 1024) {
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                } else {
                    return (bytes / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
                }
            };
            const uploadPromises = Array.from(files).map(async (file) => {
                const resValidation = await this.validationFile(file);
                if (resValidation.status === 'error') {
                    // Handle error
                    console.log('file error');
                } else {
                    // Success upload file
                    this.progressFiles.push({
                        id: resValidation.data.data.id,
                        fileData:file,
                        name: file.name,
                        progress:'0%',
                        size:formatFileSize(file.size),
                        status: 'upload'
                    });
                    this.initializeUpload(file,resValidation.data.data.id);
                }
            });
            return Promise.all(uploadPromises);
        },
        handleUploadFinished(){
            // const progressRef = this.$refs.progressRef;
            // // if(progressRef){
            // // }else{
            // // }
            // // return;
            //
        }
    }
}
</script>