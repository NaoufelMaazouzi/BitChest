<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product; // importer l'alias de la classe Product
use App\Category; // importer l'alias de la classe Category

class FrontController extends Controller
{
    public function __construct(){

        // méthode pour injecter des données à une vue partielle 
        view()->composer('partials.menu', function($view){
            $categories = Category::pluck('name', 'id')->all(); // on récupère un tableau associatif ['id' => 1]
            $view->with('categories', $categories ); // on passe les données à la vue
        });
    }

    public function index(){
        $products = Product::published()->paginate(6); // pagination 

        return view('front.index', ['products' => $products]);
    }

    // public function show(int $id){

    //     $book = Book::find($id);
        
    //     return view('front.show', ['book' => $book]);
    // }

    // public function showBookByAuthor(int $id){

    //     $author= Author::find($id);
    //     $books = Author::find($id)->books()->paginate(5);
        
    //     return view('front.author', ['books' => $books, 'author' => $author]);
    // }

    // public function showBookByGenre(int $id){
    //     // on récupère le modèle genre.id 
    //     $genre = Genre::find($id);
    //     $books = $genre->books()->paginate(5);

    //     return view('front.genre', ['books' => $books, 'genre' => $genre]);
    // }
    
}
