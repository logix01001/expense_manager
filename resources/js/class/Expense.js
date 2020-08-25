import laravelsecurity from "./laravelsecurity"
import basepath from '@/js/basepath'
export default class Expense extends laravelsecurity{


    constructor(){
        super()
        this.id = ''
        this.category_id = ''
        this.amount = ''
        this.entry_date = ''
    }

    get_data(token){
       return axios({
            method:'get',
            url: basepath + '/api/expense',
            headers: {
                'Authorization': `Bearer ${token}`
            },
        })
    }

    save(){
        return axios({
            method:'post',
            url: basepath + '/expense',
            data: {
                category_id: this.category_id,
                amount: this.amount,
                entry_date: this.entry_date,
                _token: this.crsf,
                _method: 'POST'
            }
        })

    }

    update(){
        return axios({
            method:'post',
            url: basepath + '/expense/' + this.id,
            data: {
                 category_id: this.category_id,
                amount: this.amount,
                entry_date: this.entry_date,
                _token: this.crsf,
                _method: 'PUT'
            }
        })
    }

    destroy(){

        return axios({
            method:'post',
            url: basepath + '/expense/' + this.id,
            data: {
                _token: this.crsf,
                _method: 'DELETE'
            }
        })
    }
}
