@extends('layouts.master')

@section('content')
<p class="newBtn"><a href="#"><button type="button" class="btn btn-primary btn-lg">Nouveau</button></a></p>
{{-- On inclut le fichier des messages retournés par les actions du contrôleurs productController--}}
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix</th>
            <th>Etat</th>
        </tr>
    </thead>
    <tbody>
    @forelse($products as $product)
        <tr>
            <td><a href="#">{{$product->name}}</a></td>
            <td>{{$product->category->name}}</td>
            <td>{{$product->price}}</td>
            <td>{{$product->state}}</td>
            <td><a href="{{route('products.edit', $product->id)}}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
            <td>
                <form class="delete" method="POST" action="{{route('products.destroy', $product->id)}}">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input class="btn btn-danger" type="submit" value="delete" >
                </form>
            </td>
        </tr>
    @empty
        aucun titre ...
    @endforelse
    </tbody>
</table>
{{$products->links()}}
@endsection 

@section('scripts')
    @parent
    <script src="{{asset('js/confirm.js')}}"></script>
@endsection