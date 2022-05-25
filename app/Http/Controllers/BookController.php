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
        $response = $this->clientService->createBook(
            (int) $request->author_id,
            $request->title,
            $request->isbn,
            $request->description,
            $request->release_date ? Carbon::parse($request->release_date)->format('Y-m-d H:i:s') : null,
            $request->input('format'),
            $request->number_of_pages ? (int) $request->number_of_pages : null,
        );

        if ($response->ok()) {
            session()->flash('message', ['status' => 'success', 'text' => 'New book has been successfully created']);
        }

        return to_route('books.create');
    }

    public function destroy(int $book)
    {
        if ($this->clientService->deleteBook($book)->successful()) {
            session()->flash('message', ['status' => 'success', 'text' => 'The book has been successfully deleted']);
        }

        return back();
    }
}
