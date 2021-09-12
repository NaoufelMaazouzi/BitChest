<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class PurchaseController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // On check si la requête valide le schéma de données
        $this->validate($request, [
            'user_id' => 'required|numeric',
            'crypto' => 'required|string',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
            'totalValue' => 'required|numeric',
            'imageUrl' => 'required|string',
            'date' => 'required|date',
        ]);

        $purchase = Purchase::create($request->all()); // associer les fillables
       
        return 'Success';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        try {
            //On récupère les achats de l'utilisateur en question en base de données
            $purchaseInDB = Purchase::where('user_id', '=', $user_id)->get();
            if($purchaseInDB) {
                return $purchaseInDB;
            }
        } catch (Exception $e) {
            return $e.getMessage();
          }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // On récupère l'if de l'utilisateur
        $userId = Auth::user()->id;
        //On incrémente le solde de l'utilisateur avec la valeur récupérée dans la requête
        User::find($userId)->increment('solde', $request->currentValue);
        //On supprime l'achat en base de données
        Purchase::where('id', '=', $id)->delete();

        return 'Success';
    }
}
