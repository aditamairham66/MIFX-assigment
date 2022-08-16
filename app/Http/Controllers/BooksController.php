<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        // @TODO implement
        
        $sortColumn = $request->get('sortColumn')?:"id"; ### one of `title`, `avg_review` or `published_year`
        $sortDirection = $request->get('sortDirection')?:"ASC"; ### one of `ASC` or `DESC`
        $title = $request->get('title');
        $authors = $request->get('authors');

        $data = Book::whereHas('authors', function($q) use ($authors) {
            if($authors) {
                $q->whereIn('id', explode(',', $authors));
            }
        })->where(function ($q) use ($title) {
            if($title) {
                $q->where('books.title', 'LIKE', "%$title%");
            }
        })->orderBy($sortColumn, $sortDirection)->paginate();
        return BookResource::collection($data);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $book = new Book();
        $book->isbn = $request->get('isbn');
        $book->title = $request->get('title');
        $book->description = $request->get('description');
        $book->published_year = $request->get('published_year');
        $book->save();

        if (!empty($request->get('authors'))) {
            $find = Book::find($book->id);
            $find->authors()->attach($request->get('authors'));
            $find->save();
        }

        return new BookResource($book);
    }
}
