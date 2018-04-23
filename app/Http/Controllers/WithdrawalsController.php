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
use App\KnownMosaic;
use App\WatchAddress;
use App\User;

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
        $latestAppAddress = WatchAddress::whereRaw("true")->orderBy("id", "desc")->first();
        $currentAppAddress = "N/A";
        if ($latestAppAddress !== null) {
            $currentAppAddress = $latestAppAddress->address;
        }

        return view("withdrawals.form", $params + compact("mode", "currentAppAddress"));
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
            'sender' => "required|min:40|max:45|exists:nem_watch_addresses,address",
            'recipient' => "required|min:40|max:45",
            'email'   => "required|exists:users,email",
            'mosaic_fqmn' => "required|exists:nem_known_mosaics,fqmn",
            'amount' => "required",
        ]);

        $user    = User::where("email", $request->email)->first();
        $address = WatchAddress::where("address", $request->sender)->first();
        $mosaic  = KnownMosaic::where("fqmn", $request->mosaic_fqmn)->first();
        $lastn   = UserWithdrawal::where("user_id", $user->id)
                              ->orderBy("nonce", "desc")
                              ->select("nonce")->first();

        $nonce = 1;
        if ($lastn !== null) {
            $nonce = ((int) $lastn->nonce) + 1;
        }

        $withdrawal = UserWithdrawal::create([
            'address_id' => $address->id,
            'user_id'    => $user->id,
            'recipient_address' => $request->recipient,
            'mosaic_fqmn' => $mosaic->fqmn,
            'amount' => (int) $request->amount,
            'nonce' => $nonce,
            'reference' => $request->reference ?: null,
        ]);
        return redirect('/withdrawals');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\UserWithdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function show(UserWithdrawal $withdrawal)
    {
        return view('withdrawals.show', compact('withdrawal'));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserWithdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function edit(UserWithdrawal $withdrawal)
    {
        // no update
        return redirect("withdrawals");
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserWithdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserWithdrawal $withdrawal)
    {
        // no update
        return redirect("withdrawals");
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserWithdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserWithdrawal $withdrawal)
    {
        // no delete
        return redirect("withdrawals");
    }
}
