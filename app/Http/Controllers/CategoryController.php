<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Pagination de 15 catégories
    protected $paginate = 15;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // On récupère les catégories et on les envoient vers le front
        $categories = Category::paginate($this->paginate);

        //On redirige l'utilisateur vers la vue catégories index avec les catégories récupérées
        return view('back.categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //On envoie l'utilisateur vers la vue création de catégorie
        return view('back.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // On check si les données de la requête valide le schéma de données
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $category = Category::create($request->all()); // On crée la catégorie avec les données de la requête

        // Quand la catégorie est crée on renvoie l'utilisateur vers la vue qui liste les catégories
        return redirect()->route('categories.index')->with('message', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //On cherche la catégorie que l'utilisateur veut modifier
        $category = Category::find($id);

        //On renvoie cette catégorie et l'utilisateur vers la vue de modification de catégories
        return view('back.categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // On check si les données de la requête valide le schéma de données
        $this->validate($request, [
            'name' => 'required'
        ]);

        //On cherche la catégorie que l'utilisateur veut modifier
        $category = Category::find($id);

        //On met a jour le nom de l'image avec le nouveau nom qu'a entré l'utlisateur
        if (isset($request->name)) {
                $category->update([
                    'name' => $request->name,
                ]);
            }

        // Quand la catégorie est modifiée on renvoie l'utilisateur vers la vue qui liste les catégories
        return redirect()->route('categories.index')->with('message', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //On cherche la catégorie que l'utilisateur veut modifier
        $category = Category::find($id);

        //On supprime la catégorie en question
        $category->delete();

        // Quand la catégorie est supprimée on renvoie l'utilisateur vers la vue qui liste les catégories
        return redirect()->route('categories.index')->with('message', 'success delete');
    }
}
