import * as types from './mutationTypes';

export default {
    [types.SET_LOADED](state) {
        state.isLoading = false;
    },
    [types.RECEIVE_MODS_SUCCESS](state, mods) {
        state.mods = mods;
    },
};
