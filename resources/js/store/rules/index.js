import axios from 'axios'

import basepath from '@/js/basepath'
import Gates from '@/js/class/gates'
import _ from 'lodash'
export default {

    state:{
        rules:{
            required: [
                v => !!v || 'Field is required'
            ],
            email:[
                    v => !!v || 'Field is required',
                    v => !v || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
            ],
            amount: [
                v => !!v || 'Field is required',
                v => (!isNaN(parseFloat(v)) ) || 'Invalid Amount'
            ],
            confirm(pw,cpw){
                return [
                    v => !!v || 'Field is required',
                    v => (pw === cpw) || 'Password not match'
                ]
            }
        }
    }
}



