<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'year',
        'publisher',
        'city',
        'cover',
        'bookshelf_id'
    ];

    public function bookshelf():BelongsTo{
        return $this->belongsTo(Bookshelf::class);
    }
    public static function getDataBooks(){
        $books=Book::all();
        $books_filter = [];
        foreach($books as $key => $book){
            $books_filter[$key]['no'] = $key+1;
            $books_filter[$key]['title'] = $book['title'];
            $books_filter[$key]['author'] = $book['author'];
            $books_filter[$key]['year'] = $book['year'];    
            $books_filter[$key]['publisher'] = $book['publisher'];
        }
        return $books_filter;
    }
}
