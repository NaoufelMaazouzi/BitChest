<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // On récupère tous les utilisateurs
        $users = User::all();

        // On retourne tous les utilisateurs
        return ['users' => $users];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userInDB = User::find($id);
        return $userInDB;
    }

    public function update(Request $request, $id)
    {
        // On check si la requête valide le schéma de données
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string',
            'solde' => 'required|numeric',
            'password' => 'string',
            'role' => 'required|string',
        ]);

        // On récupère l'utilisateur à modifier
        $user = User::find($id);
        if (isset($request->password)) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // On update l'utilisateur avec les paramètres de la requête sauf le mot de passe
        $user->update($request->except('password'));
       
        return $user;
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
            'name' => 'required|string',
            'email' => 'required|string',
            'solde' => 'required|numeric',
            'password' => 'string',
            'role' => 'required|string',
        ]);

        //On crypte le mot de passe
        $request->merge([
            'password' => Hash::make($request->password)
        ]);

        $user = User::create($request->all()); // associer les fillables
       
        return 'Success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // On récupère l'utilisateur à supprimer
        $user = User::find($id);
        //On supprime l'utilisateur
        $user->delete();

        return 'Success';
    }
}
