<?php

namespace App\Http\Controllers;
use App\Models\Borrow;
use App\Models\Book;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $borrows=Borrow::all();
        return response()->json(['AllBorrows'=>$borrows], 200);

    }
    public function allUserBorrows(Request $request)
    {

        $user = $request->user;

        $userBorrows=Borrow::where('user_id',$user->id)->get();

        if($userBorrows->count()>=1)
        {
            return response()->json(['userBorrows'=>$userBorrows],200);
        }
        return response()->json(['message'=>'No Borrows Made yet'],200);
    }

    public function singleUserBorrow(Request $request, $borrowId)
    {
        $user = $request->user;

        $userBorrow = Borrow::where('user_id',$user->id)->get()
                        ->where('id', $borrowId)->first();
        if($userBorrow)
        {
            return response()->json(['borrow' => $userBorrow],200);
        }
        return response()->json(['borrow' => 'No Borrow Found'],404);
    }

    public  function  returnBook(Request $request, $borrowId)
    {
        $user = $request->user;
        $userBorrow = Borrow::where('user_id',$user->id)->get()
            ->where('id', $borrowId)->first();
        if($userBorrow)
        {
            if($userBorrow->book_returned)
            {
                return response()->json(["message" => "You have no pending Borrows"],400);
            }
            $book = Book::find($userBorrow->book_id);
            $book->update(array("stock" => $book->stock + 1));

            $userBorrow->update(array("book_returned" => true));
            return response()->json( ["message" => "book returned"] ,200);
        }
        return response()->json(['borrow' => 'No Borrow Found'],404);
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

        $user = $request->user;
        $userBorrows = Borrow::where('user_id',$user->id)
            ->where('book_returned', false)->get();

        if( count($userBorrows)>=1){
            return response()->json(['message' => "Hey You Still have A Book not returned yet"], 400);
        }

            $validator = Validator::make($request->all(),[
                "book_id"=>"required|numeric",

            ]);

            if($validator->fails())
            {
                return response()->json(['errors'=>$validator->errors()],400);
            }

            $book = Book::find($request->get("book_id"));

            if($book && $book->stock >= 1)
            {
                $newBorrow = Borrow::create([
                    "book_id" => $request->get("book_id"),
                    "user_id" => $request->user->id,
                    "book_returned" => false

                ]);
                $book->update(array("stock" => $book->stock - 1 ));
                return response()->json($newBorrow);

            }
            return response()->json(['message'=>"The book you ordered is not Available or Out of stock"], 400);

        }






    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        $borrow = Borrow::find($id);
        $user = $request->user;

        if(!$borrow)
        {
            return response()->json(['message'=>'Not Found'],404);
        }

        if($borrow->user_id === $user->id or $user->isAdmin)
        {
            return response()->json(['borrow' => $borrow],200);
        }
        return response()->json(['message' => 'You are not the owner'],400);
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

        $borrow = Borrow::find($id);
        $user = $request->user;

        if(!$borrow)
        {
            return response()->json(['message' => 'Not Found'],404);
        }
        return response()->json($user->isAdmin);

        if( $borrow->user_id === $user->id)
        {
            $borrow->update($request->all());
            return response()->json(['borrow'=>$borrow],200);
        }
        return response()->json(['message'=>'You are not the owner'],400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Resquest $request)
    {
        $borrow = Borrow::find($id);
        $user = $request->user;

        if(!$borrow)
        {
            return response()->json(['message'=>'Not Found'],404);
        }

        if($borrow->user_id === $user->id)
        {
            return response()->json(['borrow'=>$borrow],200);
        }
        return response()->json(['message'=>'You are not the owner'],400);

    }

}
