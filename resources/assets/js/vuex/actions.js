/**
 * Created by apple on 2017/8/3.
 */

export default {
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

    getTags: ({commit} , params )=>{
        return window.axios.get('tags',{
            params: params || {
                page : 1
            }
        }).then(response =>{
            commit('SET_TAGS' , response.data);
        });
    },
    getTagGroups: ({commit} , params )=>{
        return window.axios.get('tag-groups',{
            params: params || {
                page : 1
            }
        }).then(response =>{
            commit('SET_TAG_GROUPS' , response.data);
        });
    },
}