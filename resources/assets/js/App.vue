<template>
    <div id="app">
        <NavMenu></NavMenu>
        <Container></Container>
    </div>
</template>

<script>
    import NavMenu from './views/layouts/NavMenu.vue'
    import Container from './views/layouts/Container.vue'
    import { mapState } from 'vuex'
    import { Loading } from 'element-ui';
    export default {
        name: 'app',
        components: {
            NavMenu,
            Container
        },
        computed :{
            ...mapState({
                isLoading : (state) => {
                    this.$loading({fullscreen : state.isLoading});
                }
            })
        },
    }
</script>

<style>
    .el-loading-mask{
        background-color:rgba(32, 32, 42, 0.63) !important;
        height:100% !important;
        width:100% !important;
    }
</style>