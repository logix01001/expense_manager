
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
                                        <th class="text-left">Name</th>
                                        <th class="text-left">Email address</th>
                                        <th class="text-left">Role</th>
                                        <th class="text-left">Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" @click="openuserdialog('update',user)" :key="user.name">
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.role_name }}</td>
                                        <td>{{ user.created_at }}</td>
                                    </tr>
                                </tbody>
                                </template>
                            </v-simple-table>
                        </v-container>
                        <!-- USER HAS ROLE CREATE PERMISSION -->
                        <v-btn v-if="$gates.haspermission('user-create')" class="float-right" @click="openuserdialog('insert')">
                            Add User
                        </v-btn>
                    </v-col>
                </v-row>
            </div>

            <!-- MODAL DIALOG -->
            <v-dialog
                v-model="userdialog"
                width="500"
                persistent
                >
                <v-card>
                    <v-card-title class="headline grey lighten-2">
                    {{ usertitle }}
                    </v-card-title>
                    <v-card-text>
                        <v-form ref="dataform">
                            <v-container>
                                <v-row dense>
                                    <v-col cols="4">
                                    <span class="float-right">Name</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="userproperties.name"
                                            required
                                            dense
                                            :rules="rules.required"
                                            outlined
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4">
                                        <span class="float-right">Email Address</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="userproperties.email"
                                            required
                                            dense
                                            :rules="rules.email"
                                            outlined
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4">
                                        <span class="float-right">Roles</span>
                                    </v-col>
                                    <v-col>
                                        <v-select
                                            :items="roles"
                                            dense
                                            outlined
                                            item-value="id"
                                            item-text="name"
                                            v-model="userproperties.role_id"
                                            :rules="rules.required"
                                            ></v-select>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>

                    </v-card-text>
                        <v-divider></v-divider>
                    <v-card-actions>
                    <!-- USER HAS ROLE DELETE PERMISSION -->

                    <v-btn v-if="$gates.haspermission('user-delete') && usertype == 'update'" @click="deleteuser">

                            Delete

                    </v-btn>


                    <v-spacer></v-spacer>
                    <v-btn @click="cancelDialog">
                        Cancel
                    </v-btn>


                    <!-- USER HAS ROLE UPDATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('user-update') && usertype == 'update'" @click="saving('update')">
                        Update
                    </v-btn>
                    <!-- USER HAS ROLE CREATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('user-create') && usertype == 'insert'" @click="saving('insert')">
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
import User from '@/js/class/User';
import Role from '@/js/class/Role';
import basepath from '@/js/basepath';
import iziToastClass from '@/js/class/iziToastClass';

export default {
    data(){
        return {
            users: [],
            usertitle: '',
            iziToastClass: new iziToastClass,
            userproperties : new User,
            roleproperties : new Role,
            userdialog: false,
            usertype: '',
            roles: [],
            dataloaded: false


        }
    },
    computed:{
        ...mapState('storeRules',['rules'])
    },
    methods:{
        ...mapActions('storeBreadCrumbs',['setItems','setTitle']),

        cancelDialog(){
            this.userdialog = false
            this.resetform()
        },

        resetform(){
            if(this.$refs.dataform != undefined){
                this.$refs.dataform.resetValidation()
            }

        },
        openuserdialog(type,user = null){
            this.usertype = type


            this.resetform()


            if(type == 'insert'){
                this.userdialog = true
                this.usertitle = 'Add User'


                this.userproperties.id = ''
                this.userproperties.name = ''
                this.userproperties.email = ''
                this.userproperties.role_id = ''

            }else{
                // USER HAS ROLE UPDATE PERMISSION -->
                if( this.$gates.haspermission('user-update')){
                    this.userdialog = true
                    this.usertitle = 'Update User'


                    this.userproperties.id = user.id
                    this.userproperties.name = user.name
                    this.userproperties.email = user.email
                    this.userproperties.role_id = user.role_id_fk
                }
            }

        },
        saving(type){

            if(this.$refs.dataform.validate()){

                if(type == 'insert'){
                    this.userproperties.save().then((res) => {

                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_users()
                        this.userdialog = false

                    }).catch((err) => {
                        if(err.response.status == 403){
                            this.userproperties.unauthorized()
                        }
                    });
                    //Async

                }else{
                    this.userproperties.update()
                    .then((res) => {
                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_users()
                        this.userdialog = false

                    }).catch((err) => {

                        if(err.response.status == 403){
                            this.userproperties.unauthorized()
                        }

                    });
                    //Async
                }

            }
        },
        get_users(){
            var self = this
            this.dataloaded = false
            this.userproperties.get_data(self.$gates.user.token).then((res) => {
                self.dataloaded = true
                self.users = res.data
            }).catch((err) => {

            });
        },
        get_roles(){
            var self = this

            this.roleproperties.get_data(this.$gates.user.token)
            .then((res) => {

                self.roles = res.data
            }).catch((err) => {

            });
        },
        deleteuser(){
            if(confirm('Are you sure you want to delete this record?')){
                this.userproperties.destroy().then((res) => {
                this.iziToastClass.show('success','System Notification','Successfully Deleted.')
                this.get_users()
                this.userdialog = false

            }).catch((err) => {

                if(err.response.status == 403){
                    this.userproperties.unauthorized()
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
        this.get_users()
        this.get_roles()


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
