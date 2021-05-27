@extends('layouts.master')

@section('content')

<h1 class="resultTitle">{{$products->total()}} résultats</h1>

<div class="row">
    @forelse($products as $product)
    <div class="col-sm-4">
        <div class="card" style="text-align: center">
            <a href="{{url('product', $product->id)}}">
                <img src="{{url('images/', $product->picture->link)}}" width="400" height="550" class="card-img-top" alt="$product->name">
            </a>
            <div class="card-body">
                <h5 class="card-title">{{$product->name}}</h5>
                <p class="card-text">{{$product->description}}</p>
            </div>
        </div>
    </div>
    @empty
        <h2>Désolé pour l'instant aucun produit n'est publié sur le site</h2>
    @endforelse
</div>
<div class="pagination">
    {{$products->links()}}
</div>
@endsection

