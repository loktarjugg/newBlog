<template>
    <div id="content">
        <header>
            <h3>标签组列表</h3>
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
                    :data="tags.data"
                    style="width: 100%">

                <el-table-column
                        prop="id"
                        label="ID"
                        width="100">
                </el-table-column>

                <el-table-column
                        prop="name"
                        label="标签名"
                        :show-overflow-tooltip="true"
                        width="120">
                </el-table-column>

                <el-table-column
                        prop="order_column"
                        label="排序"
                        :show-overflow-tooltip="true"
                        width="120">
                </el-table-column>

                <el-table-column
                        prop="type"
                        label="所属"
                        :show-overflow-tooltip="true"
                        width="120">
                </el-table-column>


                <el-table-column
                                 label="操作"
                                 prop="id">
                    <template scope="scope">
                        <el-button type="info" size="small" @click="handleEditTags(scope.row)">编辑</el-button>
                        <el-button size="small" type="warning" @click="handleTagDelete(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>

            </el-table>
        </section>


        <el-dialog :title="title" :visible.sync="dialog" @close="handleDialogClose">
            <el-form :model="form">
                <el-form-item label="标签名" label-width="140">
                    <el-input v-model="form.name" auto-complete="off"></el-input>
                </el-form-item>

                <el-form-item label="类型" label-width="140">
                    <el-select v-model="form.type" placeholder="请选择">
                        <el-option
                                v-for="item in groups"
                                :key="item.name"
                                :label="item.label"
                                :value="item.name">
                        </el-option>
                    </el-select>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="submit">确 定</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    export default {
        data() {
            return {
                params : {
                    page : 1,
                },
                dialog : false,
                form : {
                    name : '',
                    type : 'articles',
                },
                title : '新增标签',
                groups : [
                    {
                        name : 'works',
                        label : '作品'
                    },
                    {
                        name : 'articles',
                        label : '文章'
                    },
                    {
                        name : 'share',
                        label : '分享'
                    },
                ]
            }
        },
        created(){
            this.getTags(this.params);
        },
        computed: mapState(['tags']),
        methods: {
            ...mapActions(['getTags']),

            handleEditTags(tags){
                this.form.id = tags.id;
                this.form.name = tags.name;
                this.form.type = tags.type;
                this.title = '编辑标签';
                this.dialog = true;
            },
            handleDialogClose(){
                this.form = {
                    name : '',
                    type : 'articles',
                };
            },
            submit(){

                let data = this.form;

                if (data.hasOwnProperty('id')){

                    window.axios.put('tags/' + data.id , data)
                        .then(response =>{
                            this.getTags();
                        })

                }else{
                    window.axios.post('tags',data)
                        .then(response => {
                           this.getTags();
                        });
                }
                this.dialog = false;
            },
            handleTagDelete(id){
                this.$confirm('此操作会将该项删除, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    window.axios.delete('tags/' + id )
                        .then(response => {
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });

                            this.getTags(this.params);

                        }).catch(error => {
                        this.$message({
                            type: 'error',
                            message: '置顶失败'
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