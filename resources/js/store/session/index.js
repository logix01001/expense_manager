import axios from 'axios'

import basepath from '@/js/basepath'
import Gates from '@/js/class/gates'
export default {

    state:{
        data: {
            name : '',
            token: '',
            email: '',
            role: '',
            rolename: '',
            permissions: []
        },
        islogged: false
    },
    getters: {
        getData(state){
            return state.data
        },
        isLogged(state){
            return state.islogged
        },

    },

    actions: {
        setClear({commit}){
            commit('set_clearsession')
            commit('storeBreadCrumbs/clearstate',[],{ root: true })

        },
        get_userinfo({commit}){

            axios
                .get(`${basepath}/mysession`)
                .then(res=>{
                    commit('set_mysession',res.data)
                    //window.location = `${basepath}/expense_manager/dashboard`
            })
        }
    },
    mutations:{
        set_mysession(state,data){
            state.data = data
            state.islogged = true
        },
        set_clearsession(state){
            state.data = {
                name : '',
                token: '',
                email: '',
                role: '',
                rolename: '',
                permissions: []
            }
            state.islogged = false
        }
    },
}
