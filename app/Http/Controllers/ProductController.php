<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Size;
use Illuminate\Http\Request;

use Storage;

class ProductController extends Controller
{
    // Pagination de 15 produits
    protected $paginate = 15;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // On récupère les produits
        $products = Product::paginate($this->paginate);

        // On redirige l'utilisateur vers la vue produits index avec les produits récupérés
        return view('back.product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // permet de récupérer une collection type array avec en clé id => name
        $categories = Category::pluck('name', 'id')->all();
        $sizes = Size::pluck('name', 'id')->all();

        // On redirige l'utilisateur vers la vue produits create avec les catégories et tailles récupérées
        return view('back.product.create', ['categories' => $categories, 'sizes' => $sizes]);
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
            'name' => 'required|string|min:5|max:100', 
            'description' => 'required|string',
            'price' => 'required|string|min:0|not_in:0',
            'reference' => 'required|string',
            'category_id' => 'required|integer',
            'state' => 'required|in:standard,solde',
            'sizes'   => 'required|array',
            'sizes.*' => 'integer', // pour vérifier un tableau d'entiers il faut mettre sizes.*
            'status' => 'required|in:published,unpublished',
            'title_image' => 'string|nullable', // pour le titre de l'image si il existe
            'picture' => 'required|image|max:3000',
        ]);

        $product = Product::create($request->all()); // associé les fillables

        // On utilise le modèle product et la relation sizes ManyToMany pour attacher des nouvelles tailles(s)
        // à un produit que l'on vient de créer en base de données.
        // Attention $request->sizes correspond aux donnes du formulaire alors $product->sizes() à la relation ManyToMany
        $product->sizes()->attach($request->sizes);

        // On récupère l'image de la requête
        $im = $request->file('picture');
        
        // si l'image existe
        if (!empty($im)) {
            
            // On récupère le lien de l'image
            $link = $request->file('picture')->store('images');

            // mettre à jour la table picture pour le lien vers l'image dans la base de données
            $product->picture()->create([
                'link' => $link,
                'title' => $request->title_image?? $request->title
            ]);
        }

        //On retourne l'utilisateur vers la vue products index
        return redirect()->route('products.index')->with('message', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        // On récupère le produit à modifier
        $product = Product::find($id);

        // permet de récupérer une collection type array avec en clé id => name
        $category = Category::pluck('name', 'id')->all();
        $sizes = Size::pluck('name', 'id')->all();

        //On retourne l'utilisateur vers la vue products edit avec le produit les catégories et les tailles récupérées
        return view('back.product.edit', compact('product', 'category', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // On check si la requête valide le schéma de données
        $this->validate($request, [
            'name' => 'required|string|min:5|max:100',
            'description' => 'required|string',
            'price' => 'required|string|min:0|not_in:0',
            'reference' => 'required|string',
            'category_id' => 'required|integer',
            'state' => 'required|in:standard,solde',
            'sizes'   => 'required|array',
            'sizes.*' => 'integer', // pour vérifier un tableau d'entiers il faut mettre sizes.*
            'status' => 'required|in:published,unpublished',
            'title_image' => 'string|nullable', // pour le titre de l'image si il existe
            'picture' => 'image|max:3000',
        ]);

        // On récupère le produit à modifier
        $product = Product::find($id);

        $product->update($request->all()); // associé les fillables
        
        // on utilisera la méthode sync pour mettre à jour les tailles dans la table de liaison
        $product->sizes()->sync($request->sizes);

        // on check si l'utilisateur entre un nom d'image et on utiliser la méthode update pour mettre à jour le titre de l'image dans la table de liaison
        if (isset($request->name_image)) {
            $product->picture()->update([
                'title' => $request->name_image,
            ]);
        }
        
        // On récupère l'image de la requête
        $im = $request->file('picture');
        
        // On vérifie si l'image existe
        if (!empty($im)) {

            // On récupère le lien de l'image
            $link = $request->file('picture')->store('images');
            // On retire Homme ou Femme du lien
            $newLink = str_replace(['Homme', 'Femme'], '', $link);
            // suppression de l'image si elle existe 
            if(!empty($product->picture)){
                $product->picture()->delete(); // supprimer l'information en base de données
            }

            // mettre à jour la table picture pour le lien vers l'image dans la base de données
            $product->picture()->create([
                'link' => $newLink,
                'title' => $request->new_name_image?? $request->new_name_image
            ]);
            
        }

        //On retourne l'utilisateur vers la vue products index
        return redirect()->route('products.index')->with('message', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // On récupère le produit à supprimer
        $product = Product::find($id);
        //On supprime le produit
        $product->delete();

        //On retourne l'utilisateur vers la vue products index
        return redirect()->route('products.index')->with('message', 'success delete');
    }
}
