<template lang="html">
    <el-menu :default-active="$route.path"  id="nav-menu" theme="light" :unique-opened="true" :router="true">
        <div class="logo">
            <router-link to="/admin">
                <h1>{{ SERVER_NAME }}</h1>
            </router-link>
        </div>

        <el-menu-item index="/admin">
            <i class="iconfont">&#xe62f;</i>控制台
        </el-menu-item>

        <el-submenu index="1">
            <template slot="title"><i class="iconfont">&#xe88d;</i>文章管理</template>
            <el-menu-item-group>
                <el-menu-item index="/admin/articles">文章列表</el-menu-item>
            </el-menu-item-group>
        </el-submenu>

        <el-menu-item index="/admin/tags">
            <i class="iconfont">&#xe646;</i>标签列表
        </el-menu-item>

        <el-menu-item index="/admin/shares">
            <i class="iconfont">&#xe6d4;</i>分享列表
        </el-menu-item>

        <el-menu-item index="/admin/settings">
            <i class="iconfont">&#xe63b;</i>基础设置
        </el-menu-item>

    </el-menu>

</template>

<script>

    export default {
        data(){
          return {
              SERVER_NAME : 'BLOG'
          }
        },
        watch:{
            //动态设置 active 状态
            defaultActive(value) {
                const item = this.items[value];
                if (item) {
                    this.activedIndex = item.index;
                    this.initOpenedMenu();
                } else {
                    this.activedIndex = '';
                }
            },
        }
    }

</script>