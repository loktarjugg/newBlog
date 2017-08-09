<template>
    <div id="content">
        <header>
            <h3>新增文章</h3>
        </header>
        <section>
            <el-form ref="form" :model="form" :rules="rules" label-width="80px">

                <el-form-item label="标题" prop="title">
                    <el-input v-model="form.title"></el-input>
                </el-form-item>

                <el-form-item label="封面" prop="cover_link" >
                    <Upload v-on:watch-file="watchFile" :fileList="files"></Upload>
                    <input type="text" v-model="form.cover_link" hidden>
                </el-form-item>

                <el-form-item label="原文链接" prop="source_link">
                    <el-input v-model="form.source_link"></el-input>
                </el-form-item>

                <el-form-item label="类型">
                    <el-radio-group v-model="form.type">
                        <el-radio label="articles">文章</el-radio>
                        <el-radio label="works">作品</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="标签">
                    <Tag :value="form.tags" :type="form.type"></Tag>
                </el-form-item>

                <el-form-item label="状态">
                    <el-switch on-text="" off-text="" v-model="form.status"></el-switch>
                </el-form-item>

                <el-form-item label="允许评论">
                    <el-switch on-text="" off-text="" v-model="form.enable_reply"></el-switch>
                </el-form-item>

                <el-form-item label="置顶">
                    <el-switch on-text="" off-text="" v-model="form.is_top"></el-switch>
                </el-form-item>

                <el-form-item label="发布时间" prop="published_at">
                    <el-date-picker
                            v-model="form.published_at"
                            type="datetime"
                            placeholder="选择发布时间">
                    </el-date-picker>
                </el-form-item>

                <el-form-item label="简介" prop="desc">
                    <el-input  type="textarea" v-model="form.desc"></el-input>
                </el-form-item>

                <el-form-item label="内容" prop="body" >
                    <Simplemde @change="handleContentChange"></Simplemde>
                    <textarea v-model="form.body" hidden></textarea>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="submit('form')">立即创建</el-button>
                </el-form-item>
            </el-form>
        </section>
    </div>
</template>

<script>
    import {mapState, mapActions , mapGetters} from 'vuex'
    import Simplemde from '../../components/simplemde.vue'
    import Upload from '../../components/upload.vue'
    import Tag from '../../components/tag.vue'
    export default {
        components :{
            Simplemde,
            Upload,
            Tag,
        },
        data() {
            return {
                form : {
                    title : '',
                    cover_link : '',
                    source_link : '',
                    desc : '',
                    body : '',
                    status : true,
                    enable_reply : true,
                    is_top : false,
                    published_at : '',
                    tags:[],
                    type:'articles'
                },

                files : [],
                rules:{
                    title : [
                        {required : true , message: '请输入文章标题', trigger: 'blur'},
                        {max : 50 , message: '文章标题不能超过50个字符', trigger: 'blur'},
                    ],
                    tags :[
                        {required : true , message :'请选择一个标签' , trigger:'blur'},
                    ],
                },
            }
        },
        created(){
            this.init();
        },
        computed:{
            ...mapState(['tags']),
        } ,

        methods: {
            ...mapActions(['getTags']),
            handleContentChange(body){
                this.form.body = body;
            },
            watchFile(files){
                this.form.cover_link = files.full_path;
            },
            submit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {

                        let data = this.form;

                        if (data.published_at != ''){
                            data.published_at = moment(data.published_at).unix();
                        }

                        let loading = this.$loading({fullscreen :true});

                        window.axios.put('articles/' + this.$route.params.id , data )
                            .then(response => {
                                loading.close();

                                this.$notify({
                                    title: '成功',
                                    message: '操作成功, 1.5秒后自动跳转',
                                    duration:1500,
                                    type: 'success'
                                });

                                setTimeout(()=>{
                                    this.$router.push({name:'articles'});
                                },1500);
                            })
                            .catch(error => {
                                loading.close();
                            });

                    } else {
                        this.$message.error('表单验证没有通过');
                        return false;
                    }
                });
            },
            handleChange(value){
                this.form.tags = value[1];
            },
            init(){
                window.axios.get('articles/' + this.$route.params.id , {
                    params :{
                        include : 'tags'
                    }
                }).then(response => {
                    let data = response.data.data;

                    this.form = {
                        title : data.title,
                        cover_link : data.cover_link,
                        source_link :data.source_link ,
                        desc : data.desc,
                        body : data.body_original,
                        status : data.status,
                        enable_reply : data.enable_reply,
                        is_top : data.is_top,
                        published_at : data.published_at,
                        tags:[],
                        type:'articles',
                    };

                    if (data.tags){
                        let type;
                        this.form.tags = data.tags.map(function (tag) {
                            type = tag.type;
                            return {name : tag.name};
                        });
                        this.form.type = type;
                    }

                })
            },
        },
        filters : {
            date : function (date , format = 'YYYY-MM-DD') {
                return moment.unix(date).format(format);
            }
        }
    }
</script>