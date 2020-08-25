import Roles from '@/js/components/user_management/Roles'
import User from '@/js/components/user_management/User'
export default [

    {
        path: '/user_management/roles',
        name: 'roles',
        component: Roles,
        meta: { permission: 'role',
                requiredLogin: true}
    },
    {
        path: '/user_management/users',
        name: 'users',
        component: User,
        meta: { permission: 'user',
                requiredLogin: true
            }
    }

]
