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
use App\KnownMosaic;
use Artisan;

class MosaicsController extends Controller
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
        return view("mosaics.form", $params + compact("mode"));
    }

    /**
     * Show a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mosaics = KnownMosaic::paginate(20);
        return view('mosaics.index', compact('mosaics'));
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
            'namespace' => 'required|min:3',
            'mosaic' => 'required|min:1',
        ]);

        Artisan::call("mosaics:new", [
            "--namespace" => $request->namespace,
            "--mosaic"  => $request->mosaic,
        ]);

        return redirect('/mosaics');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\KnownMosaic  $mosaic
     * @return \Illuminate\Http\Response
     */
    public function show(KnownMosaic $mosaic)
    {
        return view('mosaics.show', compact('mosaic'));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KnownMosaic  $mosaic
     * @return \Illuminate\Http\Response
     */
    public function edit(KnownMosaic $mosaic)
    {
        // no update
        return redirect("mosaics");
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KnownMosaic  $mosaic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KnownMosaic $mosaic)
    {
        // no update
        return redirect("mosaics");
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KnownMosaic  $mosaic
     * @return \Illuminate\Http\Response
     */
    public function destroy(KnownMosaic $mosaic)
    {
        $mosaic->delete();
        $request->session()->flash('message', 'Successfully deleted the Mosaic!');
        return redirect('mosaics');
    }
}
