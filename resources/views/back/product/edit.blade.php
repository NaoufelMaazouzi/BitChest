@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Modifier le produit :  </h1>
                <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="form">
                        <div class="form-group">
                            <label for="name">Nom :</label>
                            <input type="text" minlength="5" maxlength="100" name="name" value="{{$product->name}}" class="form-control" id="name"
                                   placeholder="Nom du produit">
                            @if($errors->has('name')) <span class="error bg-warning text-warning">{{$errors->first('name')}}</span>@endif
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <textarea type="text" name="description"class="form-control">{{$product->description}}</textarea>
                            @if($errors->has('description')) <span class="error bg-warning text-warning">{{$errors->first('description')}}</span> @endif
                        </div>
                        <div class="form-group">
                            <label for="price">Prix :</label>
                            <input type="number" min="0" step="0.01" name="price" value="{{$product->price}}" class="form-control" id="price"
                                   placeholder="Prix du prix">
                            @if($errors->has('price')) <span class="error bg-warning text-warning">{{$errors->first('price')}}</span> @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Référence :</label>
                            <input type="text" name="reference" value="{{$product->reference}}" class="form-control" id="reference"
                                   placeholder="Reference du produit">
                            @if($errors->has('reference')) <span class="error bg-warning text-warning">{{$errors->first('reference')}}</span>@endif
                        </div>
                        <div class="input-radio">
                            <h3>Etat du produit :</h3>
                            <input type="radio" @if($product->state == 'standard') checked @endif name="state" value="standard"> standard<br>
                            <input type="radio" @if($product->state == 'solde') checked @endif name="state" value="solde"> solde<br>
                            @if($errors->has('state')) <span class="error bg-warning text-warning">{{$errors->first('state')}}</span> @endif
                        </div>
                    </div>
                    <div class="input-radio">
                        <h3>Catégorie :</h3>
                            @foreach($category as $id => $name)
                                <input type="radio" @if($product->category !== null && $product->category->id == $id) checked @endif name="category_id" value="{{$id}}"> {{$name}}<br>
                            @endforeach
                        @if($errors->has('category_id')) <span class="error bg-warning text-warning">{{$errors->first('category_id')}}</span>@endif
                    </div>

                    <h1>Tailes disponibles en vente</h1>
                    <div class="form-inline">
                        <div class="form-group">
                    @forelse($sizes as $id => $name)
                        <label class="control-label" for="size{{$id}}}">{{$name}}</label>
                        <input
                        name="sizes[]"
                        value="{{$id}}"
                        type="checkbox"
                        class="form-control"
                        @if( is_null($product->sizes) == false and  in_array($id, $product->sizes()->pluck('id')->all()))
                            checked
                        @endif
                        id="size{{$id}}">
                    @empty
                    @endforelse
                    @if($errors->has('sizes')) <span class="error bg-warning text-warning">{{$errors->first('sizes')}}</span>@endif
                        </div>
                    </div>
                    
            </div><!-- #end col md 6 -->
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Valider</button>
                <div class="input-radio">
                    <h2>Status</h2>
                    <input type="radio" @if($product->status == 'published') checked @endif name="status" value="published" checked> publié<br>
                    <input type="radio" @if($product->status == 'unpublished') checked @endif name="status" value="unpublished" > dépublié<br>
                </div>
                <div class="input-file">
                    <h2>Ajouter une nouvelle image :</h2>
                    <label for="genre">Titre de  l'image :</label>
                    <input type="text" name="new_name_image" value="{{old('new_name_image')}}">
                    <input class="file" type="file" name="picture" >
                    @if($errors->has('picture')) <span class="error bg-warning text-warning">{{$errors->first('picture')}}</span> @endif
                </div>
                @if($product->picture)
                <div class="form-group">
                    <h2>Image associée :</h2>
                    <label for="genre">Titre de  l'image :</label>
                    <input type="text" name="name_image" value="{{$product->picture->title}}">
                </div>
                <div class="form-group">
                    <img width="300" src="{{url('images/', $product->picture->link)}}" alt="">
                </div>
                @endif
            </div>
            <!-- #end col md 6 -->
            </form>
        </div>
@endsection