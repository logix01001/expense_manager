export default {

    state:{
        title: 'My Expense',
        items: ['Dashboard'],
    },
    getters: {
        getTitle(state){
            return state.title
        },
        getItems(state){
            return state.items
        }
    },

    actions: {
        setItems({commit},data){
            commit('set_items',data)

        },
        setTitle({commit},title){
            commit('set_titles',title)

        }
    },
    mutations:{
        set_items(state,data){
            state.items = data
        },
        set_titles(state,data){
            state.title = data
        },
        clearstate(state){
            state.title = 'My Expense'
            state.items = ['Dashboard']
        }

    },
}
