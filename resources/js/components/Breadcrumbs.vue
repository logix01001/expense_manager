<template>
    <div>
        <v-container>
            <v-row>
            <h3>{{title}}</h3>
            <v-spacer></v-spacer>
            <v-breadcrumbs :items="items">

                <template v-slot:divider>
                    <v-icon>mdi-chevron-right</v-icon>
                </template>
                <template v-slot:item="{ item }">
                <v-breadcrumbs-item>
                    {{ item }}
                </v-breadcrumbs-item>
                </template>

            </v-breadcrumbs>


            </v-row>
        </v-container>

    </div>
</template>

<script>
import {mapState} from 'vuex';
export default {
    data(){
        return {

        }
    },
    computed:{
        ...mapState('storeBreadCrumbs',['title','items'])
    }
}
</script>
