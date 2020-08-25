<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JavaScript;


class spaController extends Controller
{
    //

    protected $data = [];

    public function __construct(){

        // $this->data =  new \stdClass;
        // $this->data->name = Auth::user()->name;
        // $this->data->email = Auth::user()->email;
        // $this->data->token = Auth::user()->createToken('login')->accessToken;
        // $this->data->role = Auth::user()->myrole['id'];
        // $this->data->rolename = Auth::user()->myrole['name'];
        // $this->data->permissions = Auth::user()->getPermissionsViaRoles();
    }

    public function index(){

        $this->data =  new \stdClass;
        $this->data->name = Auth::user()->name;
        $this->data->email = Auth::user()->email;
        $this->data->token = Auth::user()->createToken('login')->accessToken;
        $this->data->role = Auth::user()->myrole['id'];
        $this->data->rolename = Auth::user()->myrole['name'];
        $this->data->permissions = Auth::user()->getPermissionsViaRoles();


        JavaScript::put([
            'user' => $this->data
        ]);

        return view('pages.app');

    }




}
