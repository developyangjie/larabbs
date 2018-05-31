<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Transformers\BookTransformer;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index(){
        $books = Book::orderBy("updated_at","desc")->paginate(20);
        return $this->response->paginator($books,new BookTransformer());
    }
}
