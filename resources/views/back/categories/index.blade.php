@extends('layouts.master')

<!-- Vue pour afficher les catégorie -->
@section('content')
<p class="newBtn"><a href="{{route('categories.create')}}"><button type="button" class="btn btn-primary btn-lg">Nouveau</button></a></p>
{{-- On inclut le fichier des messages retournés par les actions du contrôleurs productController--}}
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nom</th>
        </tr>
    </thead>
    <tbody>
    @forelse($categories as $category)
        <tr>
            <td><a href="#">{{$category->name}}</a></td>
            <td><a href="{{route('categories.edit', $category->id)}}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
            <td>
                <form class="deleteCategory" method="POST" action="{{route('categories.destroy', $category->id)}}">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input class="btn btn-danger" type="submit" value="delete" >
                </form>
            </td>
        </tr>
    @empty
        aucune catégorie ...
    @endforelse
    </tbody>
</table>
{{$categories->links()}}
@endsection 

@section('scripts')
    @parent
    <script src="{{asset('js/confirm.js')}}"></script>
@endsection