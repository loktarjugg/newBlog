<template>
    <div id="content">
        <header>
            <h3>基础社</h3>
        </header>
        <section>
            <el-form ref="form" :model="form" label-width="80px">

                <el-form-item label="微博" prop="weibo_link">
                    <el-input v-model="form.weibo_link"></el-input>
                </el-form-item>

                <el-form-item label="Dribbble" prop="dribbble_link">
                    <el-input v-model="form.dribbble_link"></el-input>
                </el-form-item>

                <el-form-item label="邮箱" prop="email">
                    <el-input type="email" v-model="form.email"></el-input>
                </el-form-item>

                <el-form-item label="微信号" prop="wechat_personal">
                    <el-input v-model="form.wechat_personal"></el-input>
                </el-form-item>

                <el-form-item label="公众号" prop="wechat_public">
                    <el-input v-model="form.wechat_public"></el-input>
                </el-form-item>

                <blockquote>以上除邮箱外 均为链接</blockquote>

                <el-form-item>
                    <el-button type="primary" @click="submit">保存</el-button>
                </el-form-item>
            </el-form>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form : {
                    weibo_link : '',
                    dribbble_link :'',
                    email:'',
                    wechat_personal:'',
                    wechat_public:'',
                },
            }
        },
        created(){
            this.init();
        },
        methods: {
            init(){
                window.axios.get('settings')
                    .then(response => {
                        this.form = response.data;
                    }).catch(error => {
                    this.$message.error(error.error);
                });
            },
            submit() {
                let data = this.form;

                let loading = this.$loading({fullscreen :true});

                window.axios.put('settings', data )
                    .then(response => {
                        loading.close();
                        this.$notify({
                            title: '成功',
                            message: '操作成功',
                            duration:1500,
                            type: 'success'
                        });
                    }).catch(error => {
                        loading.close();
                        this.$message.error(error.error);
                });
            },

        }
    }
</script>

<style scoped>
    blockquote{
        margin-left: 80px;
    }
</style>