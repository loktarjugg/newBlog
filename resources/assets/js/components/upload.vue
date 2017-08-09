<template>
    <div id="upload">
        <el-upload
                drag
                :on-success="handleSuccess"
                :on-error="handleError"
                :on-remove="handleRemove"
                :on-change="handleChange"
                :action="action"
                :file-list="files"
                :headers="headers"
                name="files"
                v-bind:multiple="false"
                :mutiple="mutiple">
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
        </el-upload>
    </div>
</template>

<script>


    export default {
        data(){
            return {
                headers : {
                    'X-CSRF-TOKEN' : window.csrf_token ,
                    'X-Requested-With' : 'XMLHttpRequest'
                },
                files:[],
            }
        },
        props:{
            fileList:{
                type:Array,
                default:[],
            },
            action : {
                type : String,
                default : '/api/v1/upload'
            },
            mutiple : {
                type : Boolean,
                default : false
            },
        },
        mounted(){
            this.files = this.fileList;
        },
        methods: {
            handleSuccess(file) {
                this.$emit('watch-file' , file);
            },
            handleError(error) {
                this.$emit('watch-file','');
                this.fileList = [];
                console.log(error);
            },
            handleRemove(){
                this.$emit('watch-file' , '');
                this.fileList = [];
            },
            //控制只显示一张-- 每次上传一张
            handleChange(file,fileList){
                this.files = fileList.slice(-1);
                this.fileList.fileList = fileList.slice(-1);
            }
        }
    }
</script>