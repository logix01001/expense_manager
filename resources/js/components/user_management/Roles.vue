
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
                                    <tr v-for="role in roles" @click="openRoleDialog('update',role)" :key="role.name">
                                        <td>{{ role.name }}</td>
                                        <td>{{ role.description }}</td>
                                        <td>{{ role.created_at }}</td>
                                    </tr>
                                </tbody>
                                </template>
                            </v-simple-table>
                        </v-container>
                        <!-- USER HAS ROLE CREATE PERMISSION -->
                        <v-btn v-if="$gates.haspermission('role-create')" class="float-right" @click="openRoleDialog('insert')">Add Role</v-btn>

                     </v-col>
                 </v-row>
            </div>



            <!-- MODAL DIALOG -->
            <v-dialog
                v-model="roledialog"
                width="500"
                persistent
                >
                <v-card>
                    <v-card-title class="headline grey lighten-2">
                    {{ roletitle }}
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
                                            v-model="roleproperties.name"
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
                                            v-model="roleproperties.description"
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
                    <v-btn v-if="$gates.haspermission('role-delete') && roletype == 'update'" @click="deleterole">
                        Delete
                    </v-btn>

                    <v-spacer></v-spacer>
                    <v-btn @click="roledialog = false">
                        Cancel
                    </v-btn>

                    <!-- USER HAS ROLE UPDATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('role-update') && roletype == 'update'" @click="saving('update')">
                        Update
                    </v-btn>
                    <!-- USER HAS ROLE CREATE PERMISSION -->
                    <v-btn v-if="$gates.haspermission('role-create') && roletype == 'insert'" @click="saving('insert')">
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
import Role from '@/js/class/Role';
import basepath from '@/js/basepath';
import iziToastClass from '@/js/class/iziToastClass';

export default {
    data(){
        return {
            roles: [],
            roletitle: '',
            iziToastClass: new iziToastClass,
            roleproperties : new Role,
            roledialog: false,
            roletype: '',
            dataloaded: false,
        }
    },
    computed:{
        ...mapState('storeRules',['rules'])
    },
    methods:{
        ...mapActions('storeBreadCrumbs',['setItems','setTitle']),
        cancelDialog(){
            this.roledialog = false
            this.resetform()
        },

        resetform(){
            if(this.$refs.dataform != undefined){
                this.$refs.dataform.resetValidation()

            }

        },
        openRoleDialog(type,role = null){
            this.roletype = type



            this.resetform()

            if(type == 'insert'){
                this.roledialog = true
                this.roletitle = 'Add Role'


                this.roleproperties.id = ''
                this.roleproperties.name = ''
                this.roleproperties.description = ''

            }else{
                // USER HAS ROLE UPDATE PERMISSION -->
                if( this.$gates.haspermission('role-update') && role.id != 1){
                    this.roledialog = true
                    this.roletitle = 'Update Role'

                    this.roleproperties.id = role.id
                    this.roleproperties.name = role.name
                    this.roleproperties.description = role.description
                }
            }
        },
        saving(type){

            if(this.$refs.dataform.validate()){
                if(type == 'insert'){
                    this.roleproperties.save().then((res) => {
                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_roles()
                        this.roledialog = false

                    }).catch((err) => {
                        if(err.response.status == 403){
                            this.roleproperties.unauthorized()
                        }
                    });
                    //Async

                }else{
                    this.roleproperties.update()
                    .then((res) => {
                        this.iziToastClass.show('success','System Notification','Successfully Saved.')
                        this.get_roles()
                        this.roledialog = false

                    }).catch((err) => {

                        if(err.response.status == 403){
                            this.roleproperties.unauthorized()
                        }

                    });
                    //Async

                }

            }


        },
        get_roles(){
            var self = this
            this.dataloaded = false
            this.roleproperties.get_data(self.$gates.user.token)
            .then((res) => {
                self.dataloaded = true
                self.roles = res.data
            }).catch((err) => {

            });
        },
        deleterole(){
            if(confirm('Are you sure you want to delete this record?')){
                this.roleproperties.destroy().then((res) => {
                this.iziToastClass.show('success','System Notification','Successfully Deleted.')
                this.get_roles()
                this.roledialog = false

            }).catch((err) => {

                if(err.response.status == 403){
                    this.roleproperties.unauthorized()
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
