<template>
    <li class="row relative bg-white rounded-xl flex flex-row min-h-15 items-center">
        <i class="fas fa-file-alt text-3xl absolute left-5"></i>
        <div class="details absolute  left-15">
            <span class="name">{{ internalFile.name }}</span>
            <div class="size">{{ internalFile.size }}</div>
        </div>
        <div class="controls absolute right-3 text-2xl flex gap-3">
            <template v-if="internalFile.status === 'done'">
                <i class="fas fa-check"></i>
                <i class="fa-solid fa-trash cursor-pointer" @click="deleteUpload()"></i>
            </template>
            <template v-if="internalFile.status === 'error validation'">
                <i class="fas fa-exclamation-circle"></i>
                <i class="fa-solid fa-times cursor-pointer" @click="cancelValidation()"></i>
            </template>
            <template v-if="internalFile.status === 'error upload validation'">
                <i class="fa-solid fa-repeat cursor-pointer" @click="reUploadValidation()"></i>
                <i class="fas fa-times cursor-pointer" @click="cancelUpload()"></i>
            </template>
            <template v-if="internalFile.status === 'error upload'">
                <i class="fa-solid fa-repeat cursor-pointer" @click="reUpload()"></i>
                <i class="fas fa-times cursor-pointer" @click="cancelUpload()"></i>
            </template>
        </div>
    </li>
</template>
<script>
export default{
    props:['internalFile'],
    data(){
        return{
            //
        }
    },
    methods:{
        reUploadValidation() {
            this.$emit('re-upload-validation', this.internalFile.fileData, this.internalFile.tempID);
        },
        reUpload(){
            this.$emit('re-upload', this.internalFile.id);
        },
        deleteUpload() {
            this.$emit('delete-upload', this.internalFile.id);
        },
        cancelValidation() {
            this.$emit('cancel-validation', this.internalFile.tempID);
        },
    }
}
</script>