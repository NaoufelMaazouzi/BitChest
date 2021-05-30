@extends('layouts.master')

<!-- Vue pour créer un produit -->
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Créer un produit:  </h1>
                <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form">
                        <div class="form-group">
                            <label for="name">Nom :</label>
                            <input type="text" minlength="5" maxlength="100" name="name" value="{{old('name')}}" class="form-control" id="name"
                                   placeholder="Nom du produit">
                            @if($errors->has('name')) <span class="error bg-warning text-warning">{{$errors->first('name')}}</span>@endif
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <textarea type="text" name="description"class="form-control" placeholder="Description du produit">{{old('description')}}</textarea>
                            @if($errors->has('description')) <span class="error bg-warning text-warning">{{$errors->first('description')}}</span> @endif
                        </div>
                        <div class="form-group">
                            <label for="price">Prix :</label>
                            <input type="number" min="0" step="0.01" name="price" value="{{old('price')}}" class="form-control" id="price"
                                   placeholder="Prix du produit">
                            @if($errors->has('price')) <span class="error bg-warning text-warning">{{$errors->first('price')}}</span> @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Référence :</label>
                            <input type="text" name="reference" value="{{old('reference')}}" class="form-control" id="reference"
                                   placeholder="Reference du produit">
                            @if($errors->has('reference')) <span class="error bg-warning text-warning">{{$errors->first('reference')}}</span>@endif
                        </div>
                        <div class="input-radio">
                            <h3>Etat du produit :</h3>
                            <input type="radio" @if(old('state') == 'standard') checked @endif name="state" value="standard" checked> standard<br>
                            <input type="radio" @if(old('state') == 'solde') checked @endif name="state" value="solde"> solde<br>
                            @if($errors->has('state')) <span class="error bg-warning text-warning">{{$errors->first('state')}}</span> @endif
                        </div>
                    </div>
                    <div class="form-select">
                    <label for="category">categories :</label>
                    <select id="category" name="category_id">
                        @foreach($categories as $id => $name)
                            <option {{ old('category_id')==$id? 'selected' : '' }} value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('category_id')) <span class="error bg-warning text-warning">{{$errors->first('category_id')}}</span>@endif
                    </div>
                    <h1>Choisissez une/des taille(s)</h1>
                    <div class="form-inline">
                        <div class="form-group">
                    @forelse($sizes as $id => $name)
                        <label class="control-label" for="size{{$id}}">{{$name}}</label>
                        <input name="sizes[]" value="{{$id}}"
                                {{ ( !empty(old('sizes')) and in_array($id, old('sizes')) )? 'checked' : ''  }}
                                type="checkbox" class="form-control"
                                id="size{{$id}}">
                    @empty
                    @endforelse
                    @if($errors->has('sizes')) <span class="error bg-warning text-warning">{{$errors->first('sizes')}}</span>@endif
                        </div>
                    </div>
            </div><!-- #end col md 6 -->
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Ajouter un produit</button>
                <div class="input-radio">
            <h2>Status</h2>
            <input type="radio" @if(old('status')=='published') checked @endif name="status" value="published" checked> publié<br>
            <input type="radio" @if(old('status')=='unpublished') checked @endif name="status" value="unpublished" > dépublié<br>
            </div>
            <div class="input-file">
                <h2>File :</h2>
                <label for="genre">Titre de l'image :</label>
                <input type="text" name="name_image" value="{{old('name_image')}}">
                <input class="file" type="file" name="picture" >
                @if($errors->has('picture')) <span class="error bg-warning text-warning">{{$errors->first('picture')}}</span> @endif
            </div>
            </div><!-- #end col md 6 -->
            </form>
        </div>
@endsection