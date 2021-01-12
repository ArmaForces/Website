import Vue from 'vue';
import Modlist from './components/Modlist';
import {createStore} from './store';

new Vue({
    el: "#modlist",
    store: createStore(),
    mounted() {
        this.$store.dispatch('initStore');
    },
    render(createElement) {
        return createElement(Modlist)
    },
});
