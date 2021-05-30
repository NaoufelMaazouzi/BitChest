<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On crée les catégories Homme et Femme
        App\Category::create([
            'name' => 'Homme'
        ]);
        App\Category::create([
            'name' => 'Femme'
        ]);

        // On crée les tailles
        $sizeArray = array('XS', 'S', 'M', 'L', 'XL');
        foreach($sizeArray as $key => $value) {
            App\Size::create([
                'name' => $value
            ]);
        };
        
        // On crée 80 prodduits
        factory(App\Product::class, 80)->create()
        ->each(function($product){

            //On récupère une categorie globalement
            global $category;
            $category = App\Category::find(rand(1, 2));

            //On récupère toutes les images dans le dossier images
            $path = public_path('images');
            $files = File::allFiles($path);
            // On récupère seulement les images de la catégorie glabal
            $filteredArray = array_filter($files, function($elem){
                global $category;
                return str_contains($elem, $category->name);
            });
            // On supprime tout ce qu'il y a avant le dernier anti-slash du lien
            $arrayPictures = array_map(function($file){
                return substr($file, strrpos($file, '\\') + 1); 
            },
            $filteredArray);

            $index = array_rand($arrayPictures, 1);

            //On crée l'image du proudit avec le lien qu'on a récupéré
            $product->picture()->create([
                'title' => 'Default',
                'link' => $arrayPictures[$index],
            ]);
            
            // On associe les tailles et catégorie au produit
            $sizes = App\Size::pluck('id')->shuffle()->slice(0, rand(1, 3))->all();
            $product->sizes()->attach($sizes);
            $product->category()->associate($category);
            $product->save();
        });
    }
}
