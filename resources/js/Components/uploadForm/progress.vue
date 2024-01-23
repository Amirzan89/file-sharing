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
            try {
                console.log('cobak validasi file');
                return 'tersrah aku lah';
                const res = axios.post('/file/upload', {
                name: file.name,
                format: file.format,
                size: file.size,
                });

                return { status: 'success', data: res.data };
            } catch (err) {
                return { status: 'error', message: err.response.data.message };
            }
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

        handleFileChange(event) {
            this.files = Array.from(event.target.files);
        },
        startUpload() {
            // Assume you have a server endpoint for handling resumable uploads
            const uploadUrl = '/upload';

            // Initialize the resumable upload for each file
            this.files.forEach((file) => {
                this.initializeUpload(uploadUrl, file);
            });
        },
        initializeUpload(uploadUrl, file) {
            const chunkSize = 1024 * 1024; // 1MB chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            let currentChunk = 1;

            const uploadNextChunk = () => {
                if (this.isPaused) {
                // Upload is paused, wait for resume
                    return;
                }

                const start = (currentChunk - 1) * chunkSize;
                const end = Math.min(currentChunk * chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('currentChunk', currentChunk);
                formData.append('totalChunks', totalChunks);

                // Send the chunk to the server
                this.uploadChunk(uploadUrl, formData, () => {
                    // Continue with the next chunk
                    currentChunk++;
                    if (currentChunk <= totalChunks) {
                        uploadNextChunk();
                    } else {
                        // Upload complete for the current file
                        console.log(`Upload complete for file: ${file.name}`);
                    }
                });
            };

            // Start uploading chunks for the current file
            uploadNextChunk();
        },
        uploadChunk(uploadUrl, formData, onComplete) {
            // Make an HTTP request (e.g., using axios) to upload the chunk
            const xhr = axios.create();
            xhr.upload.onprogress = (event) => {
                const percent = (event.loaded / event.total) * 100;
                console.log(`Progress: ${percent.toFixed(2)}%`);
            };

            xhr.post(uploadUrl, formData)
                .then(() => {
                console.log(`Chunk ${formData.get('currentChunk')} uploaded successfully.`);
                onComplete();
                })
                .catch((error) => {
                console.error('Error uploading chunk:', error);
                // Handle errors or implement retry logic if needed
                });
        },
        pauseUpload() {
            this.isPaused = true;
        },
        resumeUpload() {
            this.isPaused = false;
            // Continue uploading from the last paused point for each file
            this.files.forEach((file) => {
                this.initializeUpload('/resume-upload', file);
            });
        },
        cancelUpload() {
        // Implement logic to cancel the upload if needed for each file
            this.files.forEach((file) => {
                console.log(`Upload canceled for file: ${file.name}`);
            // You can add logic to cancel the ongoing upload on the server side
            });
        },
    }
}
</script>