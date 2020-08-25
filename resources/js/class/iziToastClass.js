
export default class iziToastClass {


    show(type,title,message){

        iziToast[type]({
            title: title,
            message: message,
        });

    }
}
