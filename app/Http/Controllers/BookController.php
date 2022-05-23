<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function store(Request $request)
    {

    }

    public function destroy(int $book)
    {
        $this->clientService->deleteBook($book);

        return back();
    }
}
