@extends('layouts.master')

@section('content')
<article>
    <div class="col-md-12">
        <h1>{{$product->title}}</h1>
            <div class="col-xs-6 col-md-3">
                <a href="#" class="thumbnail">
                <img src="{{url('images/', $product->picture->link)}}" alt="{{$product->picture->title}}">
                </a>
            </div>
            <h2>Nom du produit :</h2>
            <p>{{$product->name}}</p>
            <div class="select">
                <label for="size">Taille :</label>
                <select class="form-select" aria-label="Default select example">
                    <option selected>Choisissez une taille</option>
                    @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <option value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-light">Acheter</button>
    </div>
    <div class="col-md-12">
        <h2>Description: </h2>
        <p>{{$product->description}}</p>
    </div>
</article>
@endsection 
