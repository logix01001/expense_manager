
export default class breadcrumbs {
    constructor(path){


        this.path = path
        this.breadcrumbspath = []
        path.forEach(pth =>{
            pth = pth.split('_');
            var BreadCrumbs = '';
            pth.forEach(pa=>{
               BreadCrumbs +=  pa.charAt(0).toUpperCase() + pa.slice(1) + " ";
            })
            this.breadcrumbspath.push( BreadCrumbs )
        })


    }
}
