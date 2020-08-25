import axios from 'axios'

const obj = {
    instances : {},
}

obj.instance = axios

if(window.localStorage.getItem('vuex') != null){
    let vuexdata = JSON.parse(window.localStorage.getItem('vuex'))

  //  if(vuexdata.hasOwnProperty('storeSession')){



        obj.instance = axios.create({
        // .. where we make our configurations
            baseURL:  `${window.location.protocol}//${window.location.host}/cts_spa`

        });

        // Where you would set stuff like your 'Authorization' header, etc ...
        obj.instance.defaults.headers.common['Authorization'] = 'Bearer ' + vuexdata.storeSession.mysession.token;

        // Also add/ configure interceptors && all the other cool stuff
  //  }




}else{

    obj.instance = axios

}

export default obj.instance;

