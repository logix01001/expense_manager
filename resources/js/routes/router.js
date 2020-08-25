import Page404 from '@/js/components/page404'
import Page403 from '@/js/components/page403'
import VueRouter from 'vue-router'
import DashBoardComponent from '@/js/components/DashboardComponent'
import ExpenseManagement from '@/js/routes/ExpenseManagement'
import UserManagement from '@/js/routes/UserManagement'
import Passport from '@/js/components/Passport'

var router = new VueRouter({

    base: '/expense_manager',

    mode: 'history',

    routes: [
        ...UserManagement,
        ...ExpenseManagement,
        {
            path: '*',
            name: 'page404',
            component: Page404,

        },
        {
            path: '/page403',
            name: 'page403',
            component: Page403,

        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashBoardComponent,
            meta: {
                requiredLogin: true
            }

        },
        //PASSPORT
        {
            path: '/passportclient',
            name: 'passportclient',
            component: Passport,

        },

    ]
})



export default router
