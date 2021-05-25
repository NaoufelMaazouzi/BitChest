@extends('layouts.master')

@section('content')

<h1>{{$products->total()}} résultats</h1>
 <div class="row">
    @forelse($products as $product)
                <div class="col-xs-4" style="max-height:400px,min-height:400px">
                    <!-- <a href="#" class="thumbnail">
                        <img width="171" src="{{asset('images/'.$product->picture->link)}}" alt="{{$product->picture->title}}">
                    </a> -->
                    <figure class="thumbnail">
                        <a href="{{url('product', $product->id)}}">
                            <img width="171" src="{{asset('images/'.$product->category->name.'/'.$product->picture->link)}}" class="figure-img img-fluid rounded" alt="product->name">
                            <figcaption class="figure-caption">{{$product->name}}</figcaption>
                        </a>
                    </figure>
                </div>
    @empty
        <li>Désolé pour l'instant aucun produit n'est publié sur le site</li>
    @endforelse
</div>
{{$products->links()}}

@endsection

