<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $paginate = 15;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate($this->paginate);

        return view('back.categories.index', ['categories' => $categories]);
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
        //
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
        $category = Category::find($id);

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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $category = Category::find($id); // associé les fillables
        // $category->update($request->all());
        if (isset($request->name)) {
                $category->update([
                    'name' => $request->name,
                ]);
            }
        
        // // on utilisera la méthode sync pour mettre à jour les tailles dans la table de liaison
        // $category->sizes()->sync($request->sizes);

        // // on check si l'utilisateur entre un nom d'image et on utiliser la méthode update pour mettre à jour le titre de l'image dans la table de liaison
        // if (isset($request->name_image)) {
        //     $category->picture()->update([
        //         'title' => $request->name_image,
        //     ]);
        // }
        
        // // image
        // $im = $request->file('picture');
        
        // // si on associe une image à un produit 
        // if (!empty($im)) {

        //     $link = $request->file('picture')->store('images');
        //     $newLink = str_replace(['Homme', 'Femme'], '', $link);
        //     // suppression de l'image si elle existe 
        //     if(!empty($category->picture)){
        //         Storage::disk('local')->delete($category->picture->link); // supprimer physiquement l'image
        //         $category->picture()->delete(); // supprimer l'information en base de données
        //     }

        //     // mettre à jour la table picture pour le lien vers l'image dans la base de données
        //     $category->picture()->create([
        //         'link' => $newLink,
        //         'title' => $request->new_name_image?? $request->new_name_image
        //     ]);
            
        // }

        return redirect()->route('categories.index')->with('message', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
