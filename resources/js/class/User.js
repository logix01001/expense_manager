import laravelsecurity from "./laravelsecurity"
import basepath from '@/js/basepath'
export default class User extends laravelsecurity{


    constructor(){
        super()
        this.id = ''
        this.name = ''
        this.email = ''
        this.role_id = ''
    }


    get_data(token){
        return axios({
            method:'get',
            url: basepath + '/api/users',
            headers: {
                'Authorization': `Bearer ${token}`
            },
        })
    }


    save(){
        return axios({
            method:'post',
            url: basepath + '/user',
            data: {
                name: this.name,
                email: this.email,
                role_id: this.role_id,
                _token: this.crsf,
                _method: 'POST'
            }
        })

    }

    update(){
        return axios({
            method:'post',
            url: basepath + '/user/' + this.id,
            data: {
                name: this.name,
                email: this.email,
                role_id: this.role_id,
                _token: this.crsf,
                _method: 'PUT'
            }
        })

    }

    destroy(){

        return axios({
            method:'post',
            url: basepath + '/user/' + this.id,
            data: {
                _token: this.crsf,
                _method: 'DELETE'
            }
        })
    }
}
