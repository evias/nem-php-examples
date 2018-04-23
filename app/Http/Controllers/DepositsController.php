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
use App\UserDeposit;
use App\KnownMosaic;
use App\WatchAddress;
use App\User;

class DepositsController extends Controller
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
        return view("deposits.form", $params + compact("mode"));
    }

    /**
     * Show a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deposits = UserDeposit::all();
        return view('deposits.index', compact('deposits'));
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
            'email'   => "required|exists:users,email",
            'mosaic_fqmn' => "required|exists:nem_known_mosaics,fqmn",
            'awaited_amount' => "required",
            'reference' => 'required',
        ]);

        $user    = User::where("email", $request->email)->first();
        $address = WatchAddress::where("address", $request->address)->first();
        $mosaic  = KnownMosaic::where("fqmn", $request->mosaic_fqmn)->first();
        $nonce   = UserDeposit::where("user_id", $user->id)
                              ->orderBy("nonce", "desc")
                              ->select("nonce")->first();

        if (null === $nonce) {
            $nonce = 1;
        }
        else {
            $nonce = ((int) $nonce) + 1;
        }

        //dd("store: " . $nonce);
        $deposit = UserDeposit::create([
            'address_id' => $address->id,
            'user_id'    => $user->id,
            'mosaic_fqmn' => $mosaic->fqmn,
            'awaited_amount' => (int) $request->awaited_amount,
            'nonce' => $nonce + 1,
            'reference' => $request->reference,
        ]);
        return redirect('/deposits');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\UserDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show(UserDeposit $deposit)
    {
        return view('deposits.show', compact('deposit'));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDeposit $deposit)
    {
        return redirect("deposits");
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDeposit $deposit)
    {
        return redirect("deposits");
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserDeposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDeposit $deposit)
    {
        return redirect("deposits");
    }
}
