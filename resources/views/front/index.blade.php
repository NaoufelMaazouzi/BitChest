@extends('layouts.master')

<!-- Vue pour afficher les produits -->
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
                <h3 class="card-title">{{$product->name}}</h3>
                <p class="card-text">{{$product->description}}</p>
                <div class="divPrices">
                    @if($product->state == 'solde')
                        @php
                            $discountPrice = mt_rand ($product->price*10, $product->price+100*10) / 10
                        @endphp
                    <p class="oldPirce">{{$discountPrice}}</p>
                    @endif
                    <p class="{{$product->state == 'solde' ? 'card-price' : 'soloPrice'}}">{{$product->price}}€</p>
                </div>
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

