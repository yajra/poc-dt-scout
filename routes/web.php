<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\EloquentDataTable;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (request()->ajax()) {
        return (new EloquentDataTable(User::query()))
            // Enable scout search for eloquent model
            ->enableScoutSearch(User::class)
            // Add filters to scout search
            ->scoutFilter(function (string $keyword) {
                // return 'region IN ["Germany", "France"]'; // Meilisearch
                // return 'region:Germany OR region:France'; // Algolia
            })
            // Add filters to default search
            ->filter(function (Builder $query, bool $scoutSearched) {
                if (! $scoutSearched) {
                    // Filter already added for scout search
                    // $query->whereIn('region', ['Germany', 'France']);
                }

                // filter all email that start with a when search is not empty
                if (request('search.value')) {
                    $query->where('email', 'like', 'a%');
                }
            }, true)
            ->setRowId('id')
            ->toJson();
    }

    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
