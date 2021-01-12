import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';
import mutations from './mutations';
import state from './state';

Vue.use(Vuex);

export const createStore = () =>
    new Vuex.Store({
        strict: process.env.NODE_ENV !== 'production',
        actions,
        getters,
        mutations,
        state: state(),
    });
