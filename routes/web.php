<?php


use App\User;
use App\Expense;
use App\Policies\RolePolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   return redirect('expense_manager/dashboard');
});

Auth::routes(['register'=>false]);


Route::resource('user', 'UserController')->only(["store","update","destroy"]);
Route::resource('role', 'RolesController')->only(["store","update","destroy"]);
Route::resource('expense_categories', 'ExpenseCategoryController')->only(["store","update","destroy"]);
Route::resource('expense', 'ExpenseController')->only(["store","update","destroy"]);

Route::group(['prefix' => 'expense_manager','middleware' => 'auth'], function($router){

    $router->get('/{opt?}', 'spaController@index')->where('opt', '.*')->fallback();
});

//MySession
Route::get('mysession',function(){

        $data = new stdClass;
        $data->name = Auth::user()->name;
        $data->email = Auth::user()->email;
        $data->token = Auth::user()->createToken('login')->accessToken;
        $data->role = Auth::user()->myrole['id'];
        $data->rolename = Auth::user()->myrole['name'];
        $data->permissions = Auth::user()->getPermissionsViaRoles();

        return response()->json($data);
});

//Change password
Route::post('change_password',function(Request $request){

    $user = User::find(Auth::user()->id);
    $user->password = Hash::make($request->input('password'));
    $user->update();
});

