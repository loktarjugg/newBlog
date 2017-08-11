<template>
    <div id="content">
        <header>
            <h3>分享列表</h3>
            <div class="search-label">

                <el-row :gutter="20">
                    <el-col :span="16">
                        <el-button type="primary" @click="dialog = true">新增<i class="el-icon-plus el-icon--right"></i></el-button>
                    </el-col>

                </el-row>
            </div>
        </header>
        <section>
            <el-table
                    :data="shares.data"
                    style="width: 100%">

                <el-table-column
                        prop="id"
                        label="ID"
                        width="100">
                </el-table-column>

                <el-table-column
                        prop="name"
                        label="名称"
                        :show-overflow-tooltip="true"
                        width="120">
                </el-table-column>

                <el-table-column
                        prop="desc"
                        label="介绍"
                        :show-overflow-tooltip="true"
                        width="120">
                </el-table-column>

                <el-table-column
                        label="链接"
                        :show-overflow-tooltip="true"
                        width="120">
                    <template scope="scope">
                        <a :href="scope.row.link" target="_blank" v-if="scope.row.link"><i class="iconfont">&#xe643;</i></a>
                    </template>
                </el-table-column>

                <el-table-column
                        prop="logo"
                        label="LOGO"
                        :show-overflow-tooltip="true"
                        width="120">
                    <template scope="scope">
                        <a :href="scope.row.logo" target="_blank" v-if="scope.row.logo"><i class="el-icon-picture"></i></a>
                    </template>
                </el-table-column>

                <el-table-column label="标签">
                    <template scope="scope">
                        <el-tag
                                v-if="scope.row.tags"
                                v-for="tag in scope.row.tags"
                                :key="tag.id"
                                type="primary"
                                close-transition>{{ tag.name }}</el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                        label="操作"
                        fixed="right"
                        prop="id">
                    <template scope="scope">
                        <el-button type="info" size="small" @click="handleEdit(scope.row)">编辑</el-button>
                        <el-button size="small" type="warning" @click="handleDelete(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>

            </el-table>
        </section>


        <el-dialog :title="title" :visible.sync="dialog" @close="handleDialogClose">
            <el-form ref="form" :model="form" :rules="rules" label-width="80px">
                <el-form-item label="标签名" label-width="180" prop="name">
                    <el-input v-model="form.name" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item label="介绍" label-width="180" prop="desc">
                    <el-input type="textarea" v-model="form.desc" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item label="链接" label-width="180" prop="link">
                    <el-input v-model="form.link" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item label="标签" label-width="180" prop="tags">
                    <Tag :value="form.tags" type="shares"></Tag>
                </el-form-item>

                <el-form-item label="LOGO" prop="logo">
                    <Upload v-on:watch-file="watchFile" :fileList="files"></Upload>
                    <input type="text" v-model="form.logo" hidden>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="submit('form')">确 定</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
    import Tag from '../../components/tag.vue'
    import Upload from '../../components/upload.vue'

    import {mapState, mapActions} from 'vuex'
    export default {
        components :{
            Upload,
            Tag,
        },
        data() {
            return {
                params : {
                    page : 1,
                    include : 'tags'
                },
                dialog : false,

                form : {
                    name : '',
                    desc : '',
                    link : '',
                    logo : '',
                    tags : [],
                },
                title : '新增分享',
                files : [],
                rules:{
                    name : [
                        {required : true , message : '必须填写分享名称'}
                    ],
                    desc : [{required : true , message : '必须填写分享简介'}],
                    logo : [{required : true , message : '必须上传一张logo'}],
                    tags : [{required : true , message : '必须选择一个标签'}],
                },

            }
        },
        created(){
            this.getShares(this.params);
        },
        computed: mapState(['shares']),
        methods: {
            ...mapActions(['getShares']),
            watchFile(files){
                this.form.logo = files.full_path;
            },
            handleDialogClose(){
                this.form = {
                    name : '',
                    desc : '',
                    link : '',
                    logo : '',
                    tags : [],
                };
                this.$refs['form'].resetFields();
            },
            submit(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {

                        let formData = this.form;

                        if (! formData.hasOwnProperty('id')){
                            window.axios.post('shares' , formData)
                                .then(response => {
                                    this.$notify({
                                        title: '成功',
                                        message: '操作成功',
                                        duration:1000,
                                        type: 'success'
                                    });
                                    this.dialog = false;
                                    this.getShares(this.params);
                                }).catch(error => {
                                this.$message.error(error.error);
                                    this.dialog = false;
                                })
                        }else{
                            window.axios.put('shares/' + formData.id , formData)
                                .then(response => {
                                    this.$notify({
                                        title: '成功',
                                        message: '操作成功',
                                        duration:1000,
                                        type: 'success'
                                    });
                                    this.dialog = false;
                                    this.getShares(this.params);
                                }).catch(error => {
                                this.$message.error(error.error);
                                this.dialog = false;
                            })
                        }

                    } else {
                        this.$message.error('表单验证没有通过');
                        return false;
                    }
                });
            },
            handleEdit(share){
                this.form = {
                    name : share.name,
                    desc : share.desc,
                    link : share.link,
                    id : share.id,
                    logo : share.logo,
                };

                if (share.tags){
                    this.form.tags = share.tags.map(function (tag) {
                        return { name : tag.name};
                    });
                }

                if (share.logo.length > 1){
                    this.files = [{
                        name: share.logo,
                        url : share.logo
                    }];
                }

                this.dialog = true;
            },
            handleDelete(id){
                this.$confirm('此操作会将该项删除, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    window.axios.delete('shares/' + id )
                        .then(response => {
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });

                            this.getShares(this.params);

                        }).catch(error => {
                        this.$message({
                            type: 'error',
                            message: '删除失败'
                        });
                    });
                }).catch(() => {
                });
            },
        },
        filters : {
            date : function (date , format = 'YYYY-MM-DD') {
                return moment.unix(date).format(format);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .el-form-item {
        /*width: 50%;*/
    }
    #tag{
        padding-top: 40px;
    }
</style>