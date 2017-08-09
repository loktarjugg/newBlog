<template>
    <div id="content">
        <header>
            <h3>文章列表</h3>
            <div class="search-label">

                <el-row :gutter="20">
                    <el-col :span="16">
                        <router-link :to="{ name: 'articles.create'}">
                            <el-button type="primary" >新增<i class="el-icon-plus el-icon--right"></i></el-button>
                        </router-link>
                    </el-col>
                    <!--<el-col :span="8">-->
                        <!--<el-input placeholder="请输入内容" v-model="input5">-->
                            <!--<el-select v-model="select" slot="prepend" placeholder="请选择">-->
                                <!--<el-option label="餐厅名" value="1"></el-option>-->
                                <!--<el-option label="订单号" value="2"></el-option>-->
                                <!--<el-option label="用户电话" value="3"></el-option>-->
                            <!--</el-select>-->
                            <!--<el-button slot="append" icon="search"></el-button>-->
                        <!--</el-input>-->
                    <!--</el-col>-->
                </el-row>
            </div>
        </header>
        <section>
            <el-table
                    :data="articles.data"
                    border
                    style="width: 100%">
                <el-table-column
                        fixed
                        prop="id"
                        label="ID"
                        width="100">
                </el-table-column>

                <el-table-column
                        prop="title"
                        label="文章名"
                        :show-overflow-tooltip="true"
                        width="120">
                </el-table-column>

                <el-table-column
                        prop="cover_link"
                        label="封面"
                        align="center"
                        width="120">
                    <template scope="scope">
                        <a :href="scope.row.cover_link" target="_blank" v-if="scope.row.cover_link"><i class="el-icon-picture"></i></a>
                    </template>
                </el-table-column>

                <el-table-column
                        label="外链"
                        align="center"
                        width="120">
                    <template scope="scope">
                        <a :href="scope.row.source_link" v-if="scope.row.source_link"><i class="iconfont">&#xe643;</i></a>
                    </template>
                </el-table-column>

                <el-table-column
                        label="点赞数"
                        align="center"
                        width="100">
                    <template scope="scope">
                        <el-tag>{{ scope.row.vote_count }}</el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                        label="查看数"
                        align="center"
                        width="100">
                    <template scope="scope">
                        <el-tag>{{ scope.row.view_count }}</el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                        label="评论数"
                        align="center"
                        width="100">
                    <template scope="scope">
                        <el-tag>{{ scope.row.replies_count }}</el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                        label="状态"
                        align="center"
                        width="100">
                    <template scope="scope">
                        <i class="el-icon-circle-check" v-if="scope.row.status"></i>
                        <i class="el-icon-circle-close" v-else></i>
                    </template>
                </el-table-column>

                <el-table-column
                        label="允许评论"
                        align="center"
                        width="100">
                    <template scope="scope">
                        <i class="el-icon-circle-check" v-if="scope.row.enable_reply"></i>
                        <i class="el-icon-circle-close" v-else></i>
                    </template>
                </el-table-column>

                <el-table-column
                        label="置顶"
                        align="center"
                        width="100">
                    <template scope="scope">
                        <i class="el-icon-circle-check" v-if="scope.row.is_top"></i>
                        <i class="el-icon-circle-close" v-else></i>
                    </template>
                </el-table-column>

                <el-table-column
                        label="发布时间"
                        width="150"
                        :show-overflow-tooltip="true">
                    <template scope="scope">
                        <el-tooltip class="item" effect="dark" :content="scope.row.published_at|date('YYYY-MM-DD HH:mm')" placement="top-start">
                            <span>{{ scope.row.published_at | date }}</span>
                        </el-tooltip>
                    </template>
                </el-table-column>

                <el-table-column width="150px"
                                 label="操作"
                                 prop="id"
                                 fixed="right" >
                    <template scope="scope">
                        <router-link :to="{ name: 'articles.edit' , params : {id : scope.row.id} }">
                            <el-button type="info" size="small">编辑</el-button>
                        </router-link>
                        <el-button size="small" type="warning" @click="handleDelete(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="pagination" v-for="(meta, key)  in articles.meta" v-show="meta.total_pages > 1">
                <el-pagination
                        ref="paginate"
                        @current-change="handlePaginate"
                        :current-page="meta.current_page"
                        :page-size="meta.per_page"
                        layout="total, prev, pager, next"
                        :total="meta.total" v-if="key === 'pagination'">
                </el-pagination>
            </div>
        </section>
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    export default {
        data() {
            return {
                params : {
                    page : 1
                }
            }
        },
        created(){
            this.getArticles(this.params);
        },
        computed: mapState(['articles']),
        methods: {
            ...mapActions(['getArticles']),
            handleDelete(id){
                this.$confirm('此操作会将该项删除, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    window.axios.delete('articles/' + id )
                        .then(response => {
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });

                            this.getArticles(this.params);

                        }).catch(error => {
                        this.$message({
                            type: 'error',
                            message: '置顶失败'
                        });
                    });
                }).catch(() => {
                });
            },
            handlePaginate(page){
              this.params = {
                  page  : page
              };
              this.getArticles(this.params);
            },
        },
        filters : {
            date : function (date , format = 'YYYY-MM-DD') {
                return moment.unix(date).format(format);
            }
        }
    }
</script>