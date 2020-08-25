import ExpenseCategory from '@/js/components/expense_management/ExpenseCategory'
import Expense from '@/js/components/expense_management/Expense'
export default [

    {
        path: '/expense_management/expense_categories',
        name: 'expense_categories',
        component: ExpenseCategory,
        meta: { permission: 'expense-category',
                requiredLogin: true}
    },
    {
        path: '/expense_management/expenses',
        name: 'expenses',
        component: Expense,
        meta: { permission: 'expense',
                requiredLogin: true}
    }

]
