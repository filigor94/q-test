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
        $response = $this->clientService->fetchAuthors($page, $perPage, $request->search)->collect();

        $authors = new LengthAwarePaginator(
            collect($response['items']),
            $response['total_results'],
            $perPage,
            $page,
            ['path' => route('authors.index')]
        );

        if ($request->ajax()) {
            $result = [];
            foreach ($response['items'] as $author) {
                $result[] = [
                    'id' => $author['id'],
                    'text' => $author['first_name'].' '.$author['last_name'],
                ];
            }

            return [
                'results' => $result,
                'pagination' => [
                    'more' => $page != $authors->lastPage(),
                ]
            ];
        }

        return view('authors.index', [
            'authors' => $authors,
        ]);
    }

    public function show(int $author)
    {
        $author = $this->clientService->fetchAuthor($author)->json();

        return view('authors.show', [
            'author' => $author,
        ]);
    }

    public function destroy(int $author)
    {
        $author = $this->clientService->fetchAuthor($author)->json();
        if (count($author['books'])) {
            session()->flash('message', ['status' => 'danger', 'text' => 'The author has related books']);

            return back();
        }

        if ($this->clientService->deleteAuthor($author['id'])->successful()) {
            session()->flash('message', ['status' => 'success', 'text' => 'The author has been successfully deleted']);
        }

        return to_route('authors.index');
    }
}
