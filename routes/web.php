<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\RetailCrm\ApiClient;

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
    session()->forget('success');

    return view('order', ['success' => $success]);
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
            'manufacturer' => 'TRAXXAS', // 'Azalita'
            'name' => 'TRA2854X' // article 'AZ105W'
        ],
        'orderType' => 'eshop-individual', // 'fizik'
        'orderMethod' => 'app', // 'test',
        'customerName' => $request->input('name'),
        'customerComment' => 'https://github.com/farkhad',
        'site' => 'demo-magazin', //'test',
        'number' => rand(1000000, 9999999), // '2311985',
        'status' => 'new', //'trouble',
    ];

    $client = new ApiClient;
    $orderId = $client->placeOrder($orderData);

    if ($orderId) {
        session(['success' => true]);
    }

    return redirect('/');
});
