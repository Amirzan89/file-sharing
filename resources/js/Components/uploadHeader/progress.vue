<template>
    <li class="row relative bg-white rounded-xl">
        <i class="fas fa-file-alt text-3xl relative left-3 top-2/4 -translate-y-2/4"></i>
        <div class="details absolute top-1 left-12">
            <span class="name">{{ fileName }}</span>
            <div class="size">{{ fileSize }}</div>
        </div>
        <div class="mt-3 mb-1 ml-12  flex flex-row items-center gap-2">
            <div class="progress-bar w-5/6 h-3 bg-blue-500/20 rounded-xl">
                <div class="progress bg-blue-500 w-3/4 h-3"></div>
            </div>
            <span class="percent">{{ percent }}</span>
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
    props:['allFile'],
    data(){
        return{
            fileUpload:[
                {
                    // idFile:'', //from server
                    // process:xhr,
                    // progress:'10%',
                    // status:'upload',// it can be upload or done
                }
            ]
        }
    },
    methods:{
        validationFile(file){
            axios.post('/file/upload',{
                name:file.name,
                format:file.format,
                size:file.size,
            }).then(function(res){
                return {status:'sucess',data:res.body.data}
            }).catch(function(err){
                return {status:'error',message:err.body.message}
            })
        },
        uploadFile(file) {
            let validation = this.validationFile(file);
            if (validation.status === 'error') {
                // Handle validation error
                return;
            }
            let dataRes = validation.data;
            const formData = new FormData();
            formData.append('id', dataRes.id);
            formData.append('file', file);
            const xhr = new XMLHttpRequest();
            const idFile = dataRes.id;
            const progressKey = `progress_${idFile}`;

            xhr.open('POST', '/', true);

            // Set up upload progress event handler
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                const percentage = Math.round((e.loaded / e.total) * 100);
                this.$set(this.fileUpload, progressKey, `${percentage}%`);
                }
            });

            // Set up completion event handler
            xhr.onload = () => {
                if (xhr.status === 200) {
                this.$set(this.fileUpload, progressKey, '100%');
                this.$set(this.fileUpload, `status_${idFile}`, 'done');
                this.$emit('childEvent', 'Hello from Child!');
                } else {
                // Handle upload error
                this.$set(this.fileUpload, progressKey, '0%');
                this.$set(this.fileUpload, `status_${idFile}`, 'error');
                }
            };

            // Send the form data
            xhr.send(formData);

            // Update fileUpload array with initial values
            this.$set(this.fileUpload, idFile, {
                idFile,
                process: xhr,
                progress: '0%',
                status: 'upload',
            });
        },
        pauseUpload(idFile) {
            // Get the XMLHttpRequest from the fileUpload array and abort it
            const xhr = this.fileUpload[idFile]?.process;
            if (xhr) {
                xhr.abort();
                this.$set(this.fileUpload, `status_${idFile}`, 'paused');
            }
        },
        cancelUpload(idFile) {
            // Get the XMLHttpRequest from the fileUpload array and abort it
            const xhr = this.fileUpload[idFile]?.process;
            if (xhr) {
                xhr.abort();
                this.$set(this.fileUpload, `status_${idFile}`, 'canceled');
            }
        },

        startUpload() {
            const uploadUrl = '/upload';
            const chunkSize = 1024 * 1024; // 1MB chunks
            const totalChunks = Math.ceil(this.file.size / chunkSize);
            let currentChunk = 1;

            // Store cancellation tokens for each file upload
            const cancelTokens = [];

            const uploadNextChunk = () => {
                const start = (currentChunk - 1) * chunkSize;
                const end = Math.min(currentChunk * chunkSize, this.file.size);
                const chunk = this.file.slice(start, end);

                const headers = {
                    'Content-Range': `bytes ${start}-${end - 1}/${this.file.size}`,
                };

                // Create a cancellation token for this chunk
                const source = axios.CancelToken.source();
                cancelTokens.push(source);

                this.uploadChunk(uploadUrl, chunk, headers, source, () => {
                    currentChunk++;
                    if (currentChunk <= totalChunks) {
                        uploadNextChunk();
                    } else {
                        console.log('Upload complete');
                    }
                });
            };

            uploadNextChunk();
        },

        uploadChunk(uploadUrl, chunk, headers, cancelTokenSource, onComplete) {
            axios.put(uploadUrl, chunk, { headers, cancelToken: cancelTokenSource.token })
                .then(() => {
                console.log(`Chunk uploaded successfully.`);
                onComplete();
                })
                .catch((error) => {
                if (axios.isCancel(error)) {
                    console.log('Upload canceled or paused.');
                } else {
                    console.error('Error uploading chunk:', error);
                }
                });
        },

        // Pause the upload for a specific file
        pauseUpload(fileIndex) {
            if (cancelTokens[fileIndex]) {
                cancelTokens[fileIndex].cancel('Upload paused');
            }
        },

            // Resume the upload for a specific file
        resumeUpload(fileIndex) {
            if (cancelTokens[fileIndex]) {
                // Create a new cancellation token for this file
                cancelTokens[fileIndex] = axios.CancelToken.source();
            }
        },

            // Cancel the upload for a specific file
        cancelUpload(fileIndex) {
            if (cancelTokens[fileIndex]) {
                cancelTokens[fileIndex].cancel('Upload canceled');
            }
        },
    }
}
</script>