import iziToastClass from '@/js/class/iziToastClass'
export default class laravelsecurity {

    constructor(){

        this.crsf = document.querySelector('meta[name=csrf-token]').content;
        this.iziToastClass = new iziToastClass;
    }

    unauthorized(){
        this.iziToastClass.show('error','System Error','Unauthorized to perform this action.')
    }
}
