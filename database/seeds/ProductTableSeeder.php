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
        App\Size::create([
            'name' => 'XS'
        ]);
        App\Size::create([
            'name' => 'S'
        ]);
        App\Size::create([
            'name' => 'M'
        ]);
        App\Size::create([
            'name' => 'L'
        ]);
        App\Size::create([
            'name' => 'XL'
        ]);
        

        factory(App\Product::class, 80)->create()
        ->each(function($product){
            global $category;
            $category = App\Category::find(rand(1, 2));

            // $pathHommes = public_path('images/Homme');
            // $filesHommes = File::allFiles($pathHommes);
            // $hommesPictures = array_map(function($file){ 
            //     return substr($file, strrpos($file, '\\') + 1); 
            // },
            // $filesHommes);

            // $pathFemmes = public_path('images/Femme');
            // $filesFemmes = File::allFiles($pathFemmes);
            // $femmesPictures = array_map(function($file){ 
            //     return substr($file, strrpos($file, '\\') + 1); 
            // },
            // $filesFemmes);

            $path = public_path('images');
            $files = File::allFiles($path);
            $filteredArray = array_filter($files, function($elem){
                global $category;
                return str_contains($elem, $category->name);
            });
            $arrayPictures = array_map(function($file){
                return substr($file, strrpos($file, '\\') + 1); 
            },
            $filteredArray);

            // $link = array_rand($category->name == 'Homme' ? $hommesPictures : $femmesPictures, 1);
            // $categoryName = $category->name;
            $index = array_rand($arrayPictures, 1);

            $product->picture()->create([
                'title' => 'Default',
                'link' => $arrayPictures[$index],
            ]);
            
            // Storage::disk('local')->put($link, $file);
            $sizes = App\Size::pluck('id')->shuffle()->slice(0, rand(1, 3))->all();
            $product->sizes()->attach($sizes);
            $product->category()->associate($category);
            $product->save();
        });
    }
}
