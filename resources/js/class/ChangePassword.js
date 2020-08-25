import laravelsecurity from "./laravelsecurity"
import basepath from '@/js/basepath'
export default class ChangePassword extends laravelsecurity{


    constructor(){
        super()
        this.password = ''
        this.confirmpassword = ''
    }

   changepassword(){
       return axios({
            method:'post',
            url: basepath + '/change_password',
            data: {
                password: this.password,
                _token: this.crsf,
                _method: 'POST'
            }
        })
    }
}
