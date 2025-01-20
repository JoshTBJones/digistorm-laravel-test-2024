<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSearchRequest;
use App\Models\Contact;
use Illuminate\Contracts\View\View;

class SearchController extends Controller
{
    /**
     * Display the search page and search results if a query is provided.
     * 
     * @param ContactSearchRequest $request The validated search request
     * @return View Returns the search page view with optional results
     */
    public function index(ContactSearchRequest $request): View
    {
        $query = $request->validated('query');

        // If query is empty or only contains whitespace, return the search page
        if (trim($query) === '') {
            return view('pages.search.index');
        }

        // Split the query into terms and sanitize inputs
        $terms = array_filter(preg_split('/\s+/', trim($query)), 'strlen');
        if (empty($terms)) {
            return view('pages.search.index', ['results' => collect(), 'query' => $query]);
        }

        // Build the query securely using Eloquent's query builder
        $contacts = Contact::query();

        // Add conditional ordering logic using CASE
        $contacts->where(function ($query) use ($terms) {
            foreach ($terms as $term) {
                $safeTerm = '%' . addcslashes($term, '%_') . '%';
                $query->orWhere('first_name', 'LIKE', $safeTerm)
                      ->orWhere('last_name', 'LIKE', $safeTerm)
                      ->orWhere('company_name', 'LIKE', $safeTerm);
            }
        });

        // Use CASE to prioritise matches in first_name > last_name > company_name
        $contacts->orderByRaw("
            CASE 
                WHEN first_name LIKE ? THEN 1
                WHEN last_name LIKE ? THEN 2
                WHEN company_name LIKE ? THEN 3
                ELSE 4
            END
        ", array_fill(0, 3, '%' . addcslashes($query, '%_') . '%'));

        // Add secondary ordering for tie-breaking
        $contacts->orderBy('first_name')
                 ->orderBy('last_name')
                 ->orderBy('company_name');

        // Fetch paginated results
        $results = $contacts->paginate(10);

        return view('pages.search.index', compact('results', 'query'));
    }
}
