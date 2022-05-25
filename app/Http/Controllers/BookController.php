<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Services\ClientService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function store(StoreBookRequest $request)
    {
        $this->clientService->createBook(
            $request->author_id,
            $request->title,
            $request->isbn,
            $request->release_date ? Carbon::parse($request->release_date)->format('Y-m-d H:i:s') : null,
            $request->description,
            $request->input('format'),
            $request->number_of_pages,
        );

        return to_route('books.create');
    }

    public function destroy(int $book)
    {
        $this->clientService->deleteBook($book);

        return back();
    }
}
