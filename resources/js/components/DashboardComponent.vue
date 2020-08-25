
<template>
    <div>
        DASHBOARD
        <v-container>
            <div v-if="!dataloaded">
                <v-text-field color="cyan darken-4" loading disabled></v-text-field>
            </div>
            <div v-else>
                <v-row>
                    <v-col>
                        <v-simple-table border>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">Expense Categories</th>
                                        <th class="text-left">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="expense in myexpenses" :key="expense.id">
                                        <td>{{ expense.category_name }}</td>
                                        <td>${{ expense.total.toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                </template>
                        </v-simple-table>
                    </v-col>
                    <v-col>
                        <div id="chart">
                            <apexchart type="pie" width="500" :options="chartOptions" :series="myexpenses.map(obj=>{return obj.total })"></apexchart>
                        </div>
                    </v-col>
                </v-row>
            </div>
        </v-container>

    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

import breadcrumbsClass from '@/js/class/breadcrumbs'
import Expense from '@/js/class/Expense';


import { mapState,mapActions } from 'vuex';
import basepath from '@/js/basepath';
export default {
    components: {
        apexchart: VueApexCharts,
    },
    data(){
        return {
            myexpenses: [],
            dataloaded: false

        }
    },
    computed:{
        chartOptions(){
            return {
                    chart: {
                    width: 500,
                    type: 'pie',
                    },
                    labels: this.myexpenses.map(obj=>{return obj.category_name }),
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                            width: 500
                            }
                        }
                    }],

                    legend: {
                        show: false
                    },

                }
        }
    },
    methods:{
        ...mapActions('storeBreadCrumbs',['setItems','setTitle']),

        get_myexpense(){
             this.dataloaded = false
            axios({
                method:'get',
                url: basepath + '/api/myexpense',
                headers: {
                     'Authorization': `Bearer ${this.$gates.user.token}`
                },
            }).then((res) => {
                this.dataloaded = true
                this.myexpenses = res.data
            }).catch((err) => {

            });
        }
    },
    created(){
        var path = window.location.pathname.split('/')
        path = path.slice(2);
        let breadcrumbs = new breadcrumbsClass( path )

        this.setItems(breadcrumbs.breadcrumbspath)
        this.setTitle('My Expenses')
    },
    mounted(){
        this.get_myexpense()
    }



}
</script>
