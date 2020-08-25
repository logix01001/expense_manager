import Vuex from 'vuex'
import Vue from 'vue';
import breadcrumbs from '@/js/store/breadcrumbs'
import sessions from '@/js/store/session'
import rules from '@/js/store/rules'
import createPersistedState from "vuex-persistedstate";
Vue.use(Vuex)

const store = new Vuex.Store({
    modules : {
        storeBreadCrumbs:{
            namespaced: true,
            ...breadcrumbs
        },
        storeSession:{
            namespaced: true,
            ...sessions
        },
        storeRules:{
            namespaced: true,
            ...rules
        }
    },
    plugins: [
        createPersistedState({
            paths:  [
                        'storeBreadCrumbs',
                        'storeSession'
                    ],
        }),
    ],
})


export default store;
