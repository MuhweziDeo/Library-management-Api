<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = ['name', 'status', 'stock','category'];
    protected function findBookByName($name)
    {
        $book=Book::where('name', $name)->first();
        return $book;
    }
   
}
