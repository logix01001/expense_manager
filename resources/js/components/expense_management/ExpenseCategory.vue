
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
                                    <th class="text-left">Display Name</th>
                                    <th class="text-left">Description</th>
                                    <th class="text-left">Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="category in categories" @click="openCategoryDialog('update',category)" :key="category.name">
                                        <td>{{ category.name }}</td>
                                        <td>{{ category.description }}</td>
                                        <td>{{ category.created_at }}</td>
                                    </tr>
                                </tbody>
                                </template>
                            </v-simple-table>
                        </v-container>
                        <!-- USER HAS ROLE CREATE PERMISSION -->
                        <v-btn v-if="$gates.haspermission('expense-category-create')" class="float-right" @click="openCategoryDialog('insert')">
                            Add Category
                        </v-btn>

                     </v-col>
                 </v-row>
            </div>
            <!-- MODAL DIALOG -->
            <v-dialog
                v-model="categorydialog"
                width="500"
                persistent
                >
                <v-card>
                    <v-card-title class="headline grey lighten-2">
                    {{ categorytitle }}
                    </v-card-title>
                    <v-card-text>
                        <v-form ref="dataform">
                            <v-container>
                                <v-row dense>
                                    <v-col cols="4">
                                    <span class="float-right">Display Name</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="categoryproperties.name"
                                            required
                                            dense
                                            outlined
                                            :rules="rules.required"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4">
                                    <span class="float-right">Description</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="categoryproperties.description"
                                            required
                                            dense
                                            outlined
                                            :rules="rules.required"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>

                    </v-card-text>
                        <v-divider></v-divider>
                    <v-card-actions>
                    <!-- USER HAS ROLE DELETE PERMISSION -->

                    <v-btn v-if="$gates.haspermission('expense-category-delete') && categorytype == 'update'"  @click="deletecategory">
                        Delete
                    </v-btn>

                    <v-spacer></v-spacer>
                    <v-btn @click="categorydialog = false">
                        Cancel
                    </v-btn>

                    <!-- USER HAS ROLE UPDATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('expense-category-update') && categorytype == 'update'" @click="saving('update')">
                        Update
                    </v-btn>
                    <!-- USER HAS ROLE CREATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('expense-category-create') && categorytype == 'insert'" @click="saving('insert')">
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
import Category from '@/js/class/Category';
import basepath from '@/js/basepath';
import iziToastClass from '@/js/class/iziToastClass';

export default {
    data(){
        return {
            categories: [],
            categorytitle: '',
            iziToastClass: new iziToastClass,
            categoryproperties : new Category,
            categorydialog: false,
            categorytype: '',
            dataloaded: false,
            dataloaded: false,


        }
    },
    computed:{
        ...mapState('storeRules',['rules'])
    },
    methods:{
        ...mapActions('storeBreadCrumbs',['setItems','setTitle']),
        cancelDialog(){
            this.categorydialog = false
            this.resetform()
        },
        resetform(){
            if(this.$refs.dataform != undefined){
                this.$refs.dataform.resetValidation()
            }
        },

        openCategoryDialog(type,category = null){
            this.categorytype = type

            this.resetform()

            if(type == 'insert'){

                this.categorytitle = 'Add Category'

                this.categorydialog = true
                this.categoryproperties.id = ''
                this.categoryproperties.name = ''
                this.categoryproperties.description = ''

            }else{
                // USER HAS ROLE UPDATE PERMISSION -->
                if( this.$gates.haspermission('expense-category-update')){
                    this.categorydialog = true

                    this.categorytitle = 'Update Category'
                    this.categoryproperties.id = category.id
                    this.categoryproperties.name = category.name
                    this.categoryproperties.description = category.description
                }
            }
        },
        saving(type){

            if(this.$refs.dataform.validate()){
                if(type == 'insert'){
                    this.categoryproperties.save().then((res) => {

                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_categories()
                        this.categorydialog = false

                    }).catch((err) => {
                        if(err.response.status == 403){
                            this.categoryproperties.unauthorized()
                        }
                    });
                    //Async

                }else{
                    this.categoryproperties.update()
                    .then((res) => {

                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_categories()
                        this.categorydialog = false

                    }).catch((err) => {

                        if(err.response.status == 403){

                            this.categoryproperties.unauthorized()
                        }

                    });
                    //Async

                }

            }


        },
        get_categories(){
            var self = this
            this.dataloaded = false
            this.categoryproperties.get_data(self.$gates.user.token)
            .then((res) => {
                self.dataloaded = true
                self.categories = res.data
            }).catch((err) => {

            });
        },
        deletecategory(){
            if(confirm('Are you sure you want to delete this record?')){
                this.categoryproperties.destroy()
                .then((res) => {

                    this.iziToastClass.show('success','System Notification','Successfully Deleted.')
                    this.get_categories()
                    this.categorydialog = false
                }).catch((err) => {


                    if(err.response.status == 403){
                        this.categoryproperties.unauthorized()
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
