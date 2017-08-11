/**
 * Created by apple on 2017/8/3.
 */

export default {
    UPDATE_LOADING_STATUS : (state, payload) => {
        state.isLoading = payload.isLoading;
    },
    SET_ARTICLES : (state, articles) => {
        state.articles = articles;
    },
    SET_TAGS : (state , tags ) => {
        state.tags = tags;
    },
    SET_SHARES : (state , shares ) => {
        state.shares = shares;
    },
    SET_SETTINGS : (state , settings ) => {
        state.settings = settings;
    },
}
