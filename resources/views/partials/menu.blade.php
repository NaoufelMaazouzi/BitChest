<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"><a href="{{url('/soldes')}}">Soldes</a></span>
                @if(isset($categories))
                    @forelse($categories as $id => $name)
                        <span class="icon-bar"><a href="{{url('category', $id)}}">{{$name}}</a></span>
                    @empty 
                        <li>Aucune categorie pour l'instant</li>
                    @endforelse
                @endif
            </button>
            <a class="navbar-brand" href="{{url('/')}}" style="color:#66EB9A">WeFashion</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="{{url('/soldes')}}">Soldes</a></li>
                @if(Route::is('product.*') == false)
                    @forelse($categories as $id => $name)
                        <li ><a href="{{url('category', $id)}}">{{$name}}</a></li>
                    @empty 
                        <li>Aucune categorie pour l'instant</li>
                    @endforelse
                @endif
            </ul>
        </div>
    </div>
</nav>