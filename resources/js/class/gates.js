export default class gates {


    constructor(user){
        this.user = user
        this._ = require('lodash');
    }


    haspermission(permission){

        let obj =   this._.filter(  this.user.permissions,{ 'name': permission })
        if(obj.length > 0){
            return true;
        }
        else{
            return false;
        }

    }


     hasAnyPermission(permission){

        if(this.user != undefined){

            //CHECK IF USER HAS ANY OF THIS PERMISSION
            var access = ['create','update','delete']
            for(var i = 0; i < 3; i++){
                var checkaccess =  this.haspermission(`${permission}-${access[i]}`)
                if(checkaccess == true){
                    return true
                }
            }
            return false
        }

    }


    myuser(){
        return this.user
    }

}
