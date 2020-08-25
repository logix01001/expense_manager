
<template>
  <v-app id="inspire">
        <div>
            <v-navigation-drawer permanent
                v-model="drawer"
                app
                color="cyan darken-4"
                dark>

                <v-list-item>
                    <v-list-item-content>
                    <v-list-item-title>
                        <img height="100" src="/img/buildbot-hero.png" class="profileimg" alt="">
                    </v-list-item-title>
                    <v-list-item-title class="title">
                        Expense Manager
                    </v-list-item-title>
                        <v-menu transition="slide-x-transition"
                            open-on-hover bottom offset-y>

                            <template v-slot:activator="{ on, attrs }">
                                <v-list-item-subtitle class="cursor" v-on="on">
                                    {{ $gates.user.name }} ({{ $gates.user.rolename }})
                                </v-list-item-subtitle>
                            </template>

                            <v-list>
                                <v-list-item
                                class="cursor"
                                dense
                                @click="opendialog"
                                >
                                    <v-list-item-title>Change Password</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-list-item-content>
                </v-list-item>

                <v-divider></v-divider>

                <v-list
                    dense
                    nav
                >
                    <v-list-item link :to="{ name: 'dashboard'}" >
                        <v-list-item-content>
                            <v-list-item-title>Dashbord</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item v-if="$gates.user.role == 1">
                        <v-list-item-content>
                            <v-list-item-title>User Management</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        link
                        :to="{ name: 'roles'}"
                        v-if="$gates.user.role == 1"
                        class="linkmargin"
                    >
                        <v-list-item-content>
                            <v-list-item-title>Roles</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        link
                        :to="{ name: 'users'}"
                        v-if="$gates.user.role == 1"
                        class="linkmargin"
                    >
                        <v-list-item-content>
                            <v-list-item-title>Users</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Expense Management</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                        <v-list-item
                            link
                            :to="{ name: 'expense_categories'}"
                            v-if="$gates.user.role == 1"
                            class="linkmargin"
                        >
                        <v-list-item-content>
                            <v-list-item-title>Expenses Categories</v-list-item-title>
                        </v-list-item-content>
                        </v-list-item>
                        <v-list-item
                            link
                            :to="{ name: 'expenses'}"
                            class="linkmargin"
                        >
                        <v-list-item-content>
                            <v-list-item-title>Expenses</v-list-item-title>
                        </v-list-item-content>
                        </v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-app-bar
                app
                dense
            >
            <!-- clipped-left -->
                <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer" ></v-app-bar-nav-icon> -->
                <v-toolbar-title>Expense Manager</v-toolbar-title>

                <v-spacer></v-spacer>
                    <v-col cols="5">
                        <v-row>
                        <v-col cols="8"> Welcome to Expense Manager</v-col>


                        <v-col cols="4" v-if="$store.getters['storeSession/isLogged']"> <span  class="cursor" @click="logout()"> Log out </span> </v-col>
                        <v-col cols="4" v-else> <span class="cursor" @click="login"> Log in </span> </v-col>


                    </v-row>
                </v-col>
            </v-app-bar>
        </div>
        <!-- style="margin-bottom:50px" -->
        <v-main>
            <v-app>

                <v-container>
                    <bread-crumbs></bread-crumbs>
                    <router-view/>
                </v-container>

             </v-app>
        </v-main>
        <!-- CHANGE PASSWORD -->
         <v-dialog
            v-model="change_password_dialog"
            width="500"
            >
            <v-card>
                <v-card-title class="headline grey lighten-2">
                    Change Password
                </v-card-title>

                <v-card-text>
                    <v-form ref="dataform">
                        <v-container>
                            <v-row dense>
                                    <v-col cols="4">
                                        <span class="float-right">Password</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="ChangePasswordProperties.password"
                                            dense
                                            :rules="rules.required"
                                            outlined
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="4">
                                        <span class="float-right">Confirm Password</span>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="ChangePasswordProperties.confirmpassword"
                                            dense
                                            :rules="rules.confirm(ChangePasswordProperties.password,ChangePasswordProperties.confirmpassword)"
                                            outlined
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="cancelDialog">
                        Cancel
                    </v-btn>
                    <v-btn @click="change_password">
                        Confirm
                    </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
  </v-app>
</template>

<script>
import BreadCrumbs from '@/js/components/Breadcrumbs';
import basepath from '@/js/basepath';
import ChangePassword from '@/js/class/ChangePassword';
import iziToastClass from '@/js/class/iziToastClass';
import { mapActions,mapState } from 'vuex';

    export default {

        components: {
            BreadCrumbs
        },
        props: {

        },

        data: () => ({
            csrf : '',
            basepath: basepath,
            drawer: null,
            iziToastClass: new iziToastClass,
            ChangePasswordProperties : new ChangePassword,
            change_password_dialog: false

        }),
        computed:{
            ...mapState('storeRules',['rules'])
        },
        methods:{

            ...mapActions('storeSession',['setClear','get_userinfo']),
            cancelDialog(){
                this.change_password_dialog = false
                this.resetform()
            },

            resetform(){
                if(this.$refs.dataform != undefined){
                    this.$refs.dataform.resetValidation()
                }

            },
            opendialog(){
                this.change_password_dialog = true
                this.resetform()
                this.ChangePasswordProperties.password = ''
                this.ChangePasswordProperties.confirmpassword = ''
            },
            change_password(){

                if(this.$refs.dataform.validate()){
                    this.ChangePasswordProperties.changepassword()
                    .then((res) => {

                        this.iziToastClass.show('success','System Message','Successfully Change Password')
                        this.change_password_dialog = false
                    }).catch((err) => {

                    });
                }

            },
            login(){
                window.location = basepath + '/login'
            },
            logout(){
                var self = this
                axios({
                    method:'post',
                    url: basepath + '/logout',
                    data: {
                        _token: this.csrf
                    }
                }).then(res=>{
                    if(res.status == 204){
                        self.setClear()
                        window.location = basepath + '/login'
                    }
                })

            }
        },

        beforeMount(){
            if(this.$store.getters['storeSession/getData'].name == ''){
                this.get_userinfo()
            }
        },
        mounted(){
            var self = this
        }
  }
</script>

<style scoped>
.profileimg {
  border-radius: 50%;
}

.v-application .primary--text {

    color: #fff !important;
    caret-color: #ffff !important;

}

.linkmargin {
    margin-left: 30px;
}
</style>
