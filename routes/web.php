<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\RetailCrm\ApiClient;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Interfaces\ClientExceptionInterface;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $success = session('success');
    $reason = session('reason');

    session()->forget(['success', 'reason']);

    return view('order', ['success' => $success, 'reason' => $reason]);
});

Route::post('/', function(Request $request) {
    $request->validate([
        'name' => 'required',
        'comment' => 'required',
        'article' => 'required',
        'manufacturer' => 'required'
    ]);

    /**
     * All fields are validated
     * Place Order
     */
    $orderData = [
        'productFilter' => [
            'active' => 1,
            'manufacturer' => 'Azalita',
            'name' => 'AZ105W', // article
        ],
        'orderType' => 'fizik',
        'orderMethod' => 'test',
        'customerName' => $request->input('name'),
        'customerComment' => 'https://github.com/farkhad/test-laravel-retailcrm',
        'site' => 'test',
        'number' => '2311985',
        'status' => 'trouble',
    ];

    $client = new ApiClient;
    try {
        $client->placeOrder($orderData);
        session(['success' => true]);
    } catch (ApiExceptionInterface | ClientExceptionInterface $exception) {
        session(['success' => false, 'reason' => $exception->getMessage()]);
    }

    return redirect('/');
});
