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
        App\Category::create([
            'name' => 'Homme'
        ]);
        App\Category::create([
            'name' => 'Femme'
        ]);

        

        factory(App\Product::class, 80)->create()
        ->each(function($product){
            $category = App\Category::find(rand(1, 2));

            $pathHommes = public_path('images/Homme');
            $filesHommes = File::allFiles($pathHommes);
            $hommesPictures = array_map(function($file){ 
                return substr($file, strrpos($file, '\\') + 1); 
            },
            $filesHommes);

            $pathFemmes = public_path('images/Femme');
            $filesFemmes = File::allFiles($pathFemmes);
            $femmesPictures = array_map(function($file){ 
                return substr($file, strrpos($file, '\\') + 1); 
            },
            $filesFemmes);
            
            $link = array_rand($category->name == 'Homme' ? $hommesPictures : $femmesPictures, 1);
            $product->picture()->create([
                'title' => 'Default',
                'link' => $category->name == 'Homme' ? $hommesPictures[$link] : $femmesPictures[$link],
            ]);
            
            // Storage::disk('local')->put($link, $file);
            
            $product->category()->associate($category);
            $product->save();
        });
    }
}
