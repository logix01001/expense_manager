import laravelsecurity from "./laravelsecurity"
import basepath from '@/js/basepath'
export default class Category extends laravelsecurity{


    constructor(){
        super()
        this.id = ''
        this.name = ''
        this.description = ''
    }

    get_data(token){
       return axios({
            method:'get',
            url: basepath + '/api/expense_categories',
            headers: {
                'Authorization': `Bearer ${token}`
            },
        })
    }

    save(){
        return axios({
            method:'post',
            url: basepath + '/expense_categories',
            data: {
                name: this.name,
                description: this.description,
                _token: this.crsf,
                _method: 'POST'
            }
        })

    }

    update(){
        return axios({
            method:'post',
            url: basepath + '/expense_categories/' + this.id,
            data: {
                name: this.name,
                description: this.description,
                _token: this.crsf,
                _method: 'PUT'
            }
        })

    }

    destroy(){

        return axios({
            method:'post',
            url: basepath + '/expense_categories/' + this.id,
            data: {
                _token: this.crsf,
                _method: 'DELETE'
            }
        })
    }
}
