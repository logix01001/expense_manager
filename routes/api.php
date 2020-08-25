<?php

use App\User;
use App\Expense;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/roles', function(Request $request) {
        return Role::all();
    });

    Route::get('/users', function(Request $request) {
        return User::all();
    });

    Route::get('/expense_categories', function(Request $request) {
        return Category::all();
    });

    Route::get('/expense', function(Request $request) {

        return Expense::where('user_id_fk',$request->user()->id)->get();
    });

    Route::get('/myexpense', function(Request $request) {

        return Expense::distinct()
        ->select('name','category_id_fk',DB::raw('SUM(amount) as total'))
        ->join('categories','categories.id','category_id_fk')
        ->where('expenses.user_id_fk',$request->user()->id)
        ->groupBy('category_id_fk')
        ->get();
    });

});

