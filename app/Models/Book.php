<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
