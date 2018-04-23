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
use App\UserWithdrawal;

class WithdrawalsController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function showForm($mode, $params = [])
    {
        return view("withdrawals.form", $params + compact("mode"));
    }

    /**
     * Show a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdrawals = UserWithdrawal::all();
        return view('withdrawals.index', compact('withdrawals'));
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
            'address' => "required|min:40|max:45",
            'email'   => "required|exists,users.email",
            'mosaic_fqmn' => "required|exists,nem_known_mosaics.fqmn",
            'awaited_amount' => "required",
        ]);

        $user    = User::where("email", $request->email)->first();
        $address = WatchAddress::where("address", $request->address)->first();
        $mosaic  = KnownMosaic::where("fqmn", $request->mosaic_fqmn)->first();

        $deposit = UserWithdrawal::create([
            'address_id' => $address->id,
            'user_id'    => $user->id,
            'mosaic_fqmn' => $mosaic->fqmn,
            'awaited_amount' => (int) $request->awaited_amount,
        ]);
        return redirect('/withdrawals');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\UserWithdrawal  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show(UserWithdrawal $deposit)
    {
        return view('withdrawals.show', compact('deposit'));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserWithdrawal  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit(UserWithdrawal $deposit)
    {
        return redirect("withdrawals");
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserWithdrawal  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserWithdrawal $deposit)
    {
        return redirect("withdrawals");
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserWithdrawal  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserWithdrawal $deposit)
    {
        return redirect("withdrawals");
    }
}
