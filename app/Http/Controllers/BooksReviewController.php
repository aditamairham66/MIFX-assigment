<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $bookReview = new BookReview();
        $bookReview->book_id = $bookId;
        $bookReview->user_id = Auth::id();
        $bookReview->review = $request->get('review');
        $bookReview->comment = $request->get('comment');
        $bookReview->save();

        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $findBook = Book::find($bookId);
        if(!$findBook) {
            return response()->json([
                "type" => "error",
                "message" => "Book is not found"
            ], 400);
        }

        BookReview::where([
            "book_id" => $bookId,
            "id" => $reviewId
        ])->delete();

        return response()->json([], 204);
    }
}
