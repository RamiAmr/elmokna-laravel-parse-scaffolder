<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Parse\ParseClient;
use Parse\ParseSessionStorage;
use Illuminate\Support\Facades\Input;
/*USE-HERE*/

class TemplateController extends Controller
{

    //TODO move initParse to a baseController
    protected function initParse()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        try {

            /*REGISTER-HERE*/

            ParseClient::initialize(env("DB_USERNAME"), "", env("DB_PASSWORD"));
            ParseClient::setServerURL(env("DB_HOST") . ":" . env("DB_PORT"), 'parse');
            ParseClient::setStorage(new ParseSessionStorage());
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }

    public function __construct()
    {
        $this->initParse();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*INDEX-METHOD-HERE*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*CREATE-METHOD-HERE*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*STORE-METHOD-HERE*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*SHOW-METHOD-HERE*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*EDIT-METHOD-HERE*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*UPDATE-METHOD-HERE*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*DESTROY-METHOD-HERE*/
    }
}
