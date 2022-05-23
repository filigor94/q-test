<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AuthorController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $page = Paginator::resolveCurrentPage() ? : 1;
        $perPage = $request->perPage ? (int) $request->perPage : 12;
        $response = $this->clientService->fetchAuthors($page, $perPage);

        $authors = new LengthAwarePaginator(
            collect($response['items']),
            $response['total_results'],
            $perPage,
            $page,
            ['path' => route('authors.index')]
        );

        return view('authors.index', [
            'authors' => $authors,
        ]);
    }
}
