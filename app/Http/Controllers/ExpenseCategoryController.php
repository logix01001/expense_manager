<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{

    public function __construct(){
        $this->middleware('can:expensecategory.update', ['only' => ['update']]);
        $this->middleware('can:expensecategory.create', ['only' => ['store']]);
        $this->middleware('can:expensecategory.delete', ['only' => ['destroy']]);
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



        $category = new Category;
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        $category->save();


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

        $category = Category::find($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->update();
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

        Category::find($id)->delete();
    }
}
