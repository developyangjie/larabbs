<?php
namespace App\Transformers;

use App\Models\Book;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract{
    public function transform(Book $book){
        return [
            "id" => $book->id,
            "name" => $book->name,
            "price" => $book->price,
            "description" => $book->description,
            "image" => $book->image,
            "created_at" => $book->created_at->toDateString(),
            "updated_at" => $book->updated_at->toDateString()
        ];
    }
}