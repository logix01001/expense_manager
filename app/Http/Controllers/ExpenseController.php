<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{




    public function __construct(){
        $this->middleware('can:expense.update', ['only' => ['update']]);
        $this->middleware('can:expense.create', ['only' => ['store']]);
        $this->middleware('can:expense.delete', ['only' => ['destroy']]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $expense = new Expense;
        $expense->category_id_fk = $request->input('category_id');
        $expense->amount = $request->input('amount');
        $expense->entry_date = \date('Y-m-d', strtotime($request->input('entry_date')));
        $expense->user_id_fk = Auth::user()->id;

        $expense->save();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $expense = Expense::find($id);
        $expense->category_id_fk = $request->input('category_id');
        $expense->amount = $request->input('amount');
        $expense->entry_date = \date('Y-m-d', strtotime($request->input('entry_date')));

        $expense->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Expense::find($id)->delete();
    }
}
