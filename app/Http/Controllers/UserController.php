<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('can:user.update', ['only' => ['update']]);
        $this->middleware('can:user.create', ['only' => ['store']]);
        $this->middleware('can:user.delete', ['only' => ['destroy']]);
    }

    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => ['required', ],
            'email' => ['required','email','unique:users'],
            'role_id' => ['required'],
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id_fk = $request->input('role_id');
        $user->password = Hash::make('purplebug');


        try {
            DB::beginTransaction();

            $user->save();
            $role = Role::find( $user->role_id_fk);
            $user->assignRole( $role->name);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e);

        }


    }

    public function update(Request $request, $id)
    {
        //

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id_fk = $request->input('role_id');

        try {
            DB::beginTransaction();

            $user->save();
            $role = Role::find( $user->role_id_fk);
            $user->assignRole( $role->name);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e);

        }



    }

    public function destroy($id)
    {
        //

        User::find($id)->delete();
    }
}
