
<template>
    <div>
        <v-container>
            <div v-if="!dataloaded">
                <v-text-field color="cyan darken-4" loading disabled></v-text-field>
            </div>
            <div v-else>
                <v-row>
                     <v-col cols="9">
                        <v-container>
                        <v-simple-table border>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">Expense Category</th>
                                        <th class="text-left">Amount</th>
                                        <th class="text-left">Entry Date</th>
                                        <th class="text-left">Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="expense in expenses" @click="openexpensedialog('update',expense)" :key="expense.id">
                                        <td>{{ expense.category_name }}</td>
                                        <td>${{ expense.amount }}</td>
                                        <td>{{ expense.entry_date }}</td>
                                        <td>{{ expense.created_at }}</td>
                                    </tr>
                                </tbody>
                                </template>
                            </v-simple-table>
                        </v-container>
                        <!-- USER HAS ROLE CREATE PERMISSION -->
                        <v-btn v-if="$gates.haspermission('expense-create')" class="float-right" @click="openexpensedialog('insert')">
                            Add Expense
                        </v-btn>
                    </v-col>
                </v-row>
            </div>

            <!-- MODAL DIALOG -->
            <v-dialog
                v-model="expensedialog"
                width="500"
                persistent
                >
                <v-card>
                    <v-card-title class="headline grey lighten-2">
                    {{ expensetitle }}
                    </v-card-title>
                    <v-card-text>
                        <v-form ref="dataform">
                            <v-container>
                                <v-row dense>
                                    <v-col cols="4">
                                        <span class="float-right">Expense Category</span>
                                    </v-col>
                                    <v-col>
                                        <v-select
                                            :items="categories"
                                            dense
                                            outlined
                                            item-value="id"
                                            item-text="name"
                                            v-model="expenseproperties.category_id"
                                            :rules="rules.required"
                                            ></v-select>
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4">
                                    <span class="float-right">Amount</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="expenseproperties.amount"
                                            required
                                            dense
                                            :rules="rules.amount"
                                            outlined
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4">
                                        <span class="float-right">Entry Date</span>
                                    </v-col>
                                    <v-col cols="7">
                                        <v-menu
                                            v-model="datedialog"
                                            offset-y
                                            max-width="290px"
                                            min-width="200px"
                                        >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                :value="expenseproperties.entry_date"
                                                v-on="on"
                                                @click:clear="expenseproperties.entry_date = null"
                                                :rules="rules.required"
                                                placeholder="    /  /  "
                                                clearable
                                                dense
                                                outlined
                                                readonly
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="temp_entrydate_watch"
                                            @change="pickerDialog = false"
                                        ></v-date-picker>
                                    </v-menu>
                                    </v-col>
                                    <v-col cols="1">
                                        <v-icon large>mdi-calendar-month</v-icon>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>
                        <v-divider></v-divider>
                    <v-card-actions>

                    <!-- USER HAS ROLE DELETE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('expense-delete') && expensetype == 'update'" @click="deleteexpense">
                            Delete
                    </v-btn>

                    <v-spacer></v-spacer>
                    <v-btn @click="cancelDialog">
                        Cancel
                    </v-btn>

                    <!-- USER HAS ROLE UPDATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('expense-update') && expensetype == 'update'" @click="saving('update')">
                        Update
                    </v-btn>
                    <!-- USER HAS ROLE CREATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('expense-create') && expensetype == 'insert'" @click="saving('insert')">
                        Save
                    </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </v-container>
    </div>
</template>


<script>
import breadcrumbsClass from '@/js/class/breadcrumbs';
import { mapState,mapActions } from 'vuex';
import Expense from '@/js/class/Expense';
import Category from '@/js/class/Category';
import basepath from '@/js/basepath';
import iziToastClass from '@/js/class/iziToastClass';


export default {
    data(){
        return {
            expenses: [],
            expensetitle: '',
            iziToastClass: new iziToastClass,
            expenseproperties : new Expense,
            temp_entrydate_watch : null,
            categoryproperties : new Category,
            expensedialog: false,
            expensetype: '',
            categories: [],
            datedialog: false,
            dataloaded: false,


        }
    },
    watch:{
        temp_entrydate_watch(val){
            if(val != null){
                this.expenseproperties.entry_date = this.formatDate(val)
            }
        }
    },
    computed:{
        ...mapState('storeRules',['rules'])
    },
    methods:{
        ...mapActions('storeBreadCrumbs',['setItems','setTitle']),
        formatDate (date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${month}/${day}/${year}`
        },
        cancelDialog(){
            this.expensedialog = false
            this.resetform()
        },

        resetform(){
            if(this.$refs.dataform != undefined){
                this.$refs.dataform.resetValidation()
            }

        },
        openexpensedialog(type,user = null){
            this.expensetype = type

            this.temp_entrydate_watch = null
            this.resetform()


            if(type == 'insert'){
                this.expensedialog = true
                this.expensetitle = 'Add Expense'


                this.expenseproperties.id = ''
                this.expenseproperties.category_id = ''
                this.expenseproperties.amount = ''
                this.expenseproperties.entry_date = ''

            }else{
                // USER HAS ROLE UPDATE PERMISSION -->
                if( this.$gates.haspermission('expense-update')){
                    this.expensedialog = true
                    this.expensetitle = 'Update Expense'

                    this.expenseproperties.id = user.id
                    this.expenseproperties.category_id = user.category_id_fk
                    this.expenseproperties.amount = user.amount
                    this.expenseproperties.entry_date = this.formatDate(user.entry_date)
                }
            }

        },
        saving(type){

            if(this.$refs.dataform.validate()){

                if(type == 'insert'){
                    this.expenseproperties.save()
                    .then((res) => {

                        this.get_expenses()
                        this.expensedialog = false

                        this.iziToastClass.show('success','System Notification','Successfully Saved.')


                    }).catch((err) => {
                        if(err.response.status == 403){
                            this.expenseproperties.unauthorized()
                        }
                    });
                    //Async

                }else{
                    this.expenseproperties.update()
                    .then((res) => {

                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_expenses()
                        this.expensedialog = false

                    }).catch((err) => {

                        if(err.response.status == 403){
                            this.expenseproperties.unauthorized()
                        }

                    });
                    //Async
                }

            }
        },
        get_expenses(){
            var self = this
            self.dataloaded = false
            this.expenseproperties.get_data(self.$gates.user.token).then((res) => {
                self.expenses = res.data
                self.dataloaded = true
            }).catch((err) => {

            });
        },
        get_categories(){
            var self = this
            this.categoryproperties.get_data(this.$gates.user.token)
            .then((res) => {
                self.categories = res.data
            }).catch((err) => {

            });
        },
        deleteexpense(){
            if(confirm('Are you sure you want to delete this record?')){
                this.expenseproperties.destroy().then((res) => {

                this.iziToastClass.show('success','System Notification','Successfully Deleted.')
                this.get_expenses()
                this.expensedialog = false

            }).catch((err) => {

                if(err.response.status == 403){
                    this.expenseproperties.unauthorized()
                }

            });

            }
        }
    },
    created(){
        var path = window.location.pathname.split('/')
        path = path.slice(2);
        let breadcrumbs = new breadcrumbsClass( path )

        this.setItems(breadcrumbs.breadcrumbspath)
        this.setTitle(breadcrumbs.breadcrumbspath.slice(-1)[0])

    },
    mounted(){

        var self = this
        this.get_expenses()
        this.get_categories()


    }

}
</script>


<style scoped>
    tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }
    .v-data-table > .v-data-table__wrapper > table,
    tr,
    td,
    th{
        border: 1px black solid !important;
    }

</style>
