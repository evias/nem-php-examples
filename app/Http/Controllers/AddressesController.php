<?php
/**
 * Part of the evias/nem-php-examples package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    evias/nem-php-examples
 * @version    1.0.0
 * @author     Grégory Saive <greg@evias.be>
 * @license    MIT License
 * @copyright  (c) 2017-2018, Grégory Saive <greg@evias.be>
 * @link       http://github.com/evias/nem-php-examples
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WatchAddress;

class AddressesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the edition/creation form
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function showForm($mode, $params = [])
    {
        return view("addresses.form", $params + compact("mode"));
    }

    /**
     * Show a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = WatchAddress::all();
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->showForm("create");
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate
        $request->validate([
            'bip44_path' => 'required',
            'public_key' => 'required',
            'address' => 'required',
        ]);

        $address = WatchAddress::create([
            'bip44_path' => $request->bip44_path, 
            'public_key' => $request->public_key,
            'address' => $request->address,
        ]);
        return redirect('/addresses');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\WatchAddress  $address
     * @return \Illuminate\Http\Response
     */
    public function show(WatchAddress $address)
    {
        return view('addresses.show', compact('address'));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WatchAddress  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(WatchAddress $address)
    {
        return redirect("addresses");
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WatchAddress  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WatchAddress $address)
    {
        return redirect("addresses");
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WatchAddress  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(WatchAddress $address)
    {
        return redirect("addresses");
    }
}
