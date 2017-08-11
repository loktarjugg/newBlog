/**
 * Created by apple on 2017/8/3.
 */

import Vue from 'vue';
import Vuex from 'vuex';
import actions    from './actions'
import mutations  from './mutations'
import getters    from './getters'
import { Loading }  from 'element-ui'
Vue.use(Vuex);


export default new Vuex.Store({
    state:{
        isLoading : false,
        articles : [],
        tags : [],
        shares: [],
        settings : [],
    },
    getters,
    mutations,
    actions
});