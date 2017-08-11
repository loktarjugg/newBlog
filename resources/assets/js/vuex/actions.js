
export default {
    /**
     * get articles
     * @param commit
     * @param params
     * @returns {Promise.<TResult>|*}
     */
    getArticles: ({commit} , params )=>{
        return window.axios.get('articles',{
            params: params || {
                page : 1
            }
        }).then(response =>{
            commit('SET_ARTICLES' , response.data);
            console.log(response.data);
        });
    },

    /**
     * get tags
     * @param commit
     * @param params
     * @returns {Promise.<TResult>|*}
     */
    getTags: ({commit} , params )=>{
        return window.axios.get('tags',{
            params: params || {
                page : 1
            }
        }).then(response =>{
            commit('SET_TAGS' , response.data);
        });
    },

    /**
     * get shares
     * @param commit
     * @param params
     * @returns {Promise.<TResult>|*}
     */
    getShares: ({commit} , params )=>{
        return window.axios.get('shares',{
            params: params || {
                page : 1
            }
        }).then(response =>{
            commit('SET_SHARES' , response.data);
        });
    },
    /**
     * get settings
     * @param commit
     * @returns {Promise.<TResult>|*}
     */
    getSettings: ({commit})=>{
        return window.axios.get('settings').then(response =>{
            commit('SET_SETTINGS' , response.data);
        });
    },
}