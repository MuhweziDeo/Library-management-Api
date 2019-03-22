<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
class BookController extends Controller

{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $books=Book::all();
        return response()->json(['books'=>$books], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create book
        $validator=Validator::make($request->all(),[
            "name"=>"required|string",
            "stock"=>"required|numeric",
            "category"=>"required|string",

        ]);


        if($validator->fails()){

            return response()->json($validator->errors(),400);
        }

        $book=Book::findBookByName($request->get('name'));

        if ($book) {

            return response()->json(['message'=>'Book with name already exists'],400);
        }

        $newBook=Book::create([
            'name'=>$request->get('name'),
            'stock'=>$request->get('stock'),
            'category'=>$request->get('category'),
        ]);

        return response()->json(['book'=>$newBook],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get single book
        $book=Book::find($id);

        if ($book)
        {
            return response()->json(['book'=>$book]);
        }

        return response()->json(['message'=>'Book Not Found'],404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
        $book=Book::find($id);

        if ($book) {

            $book->update($request->all());

            return response()->json($book, 201);

        }

        return response()->json(['message'=>'Book Not Found'],404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete book
        $book=Book::find($id);

        if ($book) {

            $book->delete();

            return response()->json(['message'=>'Book Deleted'],200);

        }

        return response()->json(['message'=>'Book Not Found'],404);
    }
}
