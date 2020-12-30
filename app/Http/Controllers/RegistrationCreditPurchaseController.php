<?php

namespace App\Http\Controllers;

use App\RegistrationCreditPurchase;
use Illuminate\Http\Request;

class RegistrationCreditPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('membership.buy_register_credits');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(RegistrationCreditPurchase $registrationCreditPurchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(RegistrationCreditPurchase $registrationCreditPurchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegistrationCreditPurchase $registrationCreditPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegistrationCreditPurchase $registrationCreditPurchase)
    {
        //
    }
}
