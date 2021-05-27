<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <a class="navbar-brand" href="{{url('/')}}" style="color:#66EB9A">WeFashion</a>
                @if(isset($categories) && Request::is('admin/*') == false)
                    <li class="active"><a href="{{url('/soldes')}}">Soldes</a></li>
                    @forelse($categories as $id => $name)
                        <li ><a href="{{url('category', $id)}}">{{$name}}</a></li>
                    @empty 
                        <li>Aucune categorie pour l'instant</li>
                    @endforelse
                @else
                    <li class="active"><a href="{{url('/admin/products')}}">Produits</a></li>
                    <li><a href="{{url('/admin/categories')}}">Catégories</a></li>
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                Logout
                        </a>
                        <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                    <li class="active"><a href="{{route('products.index')}}">Dashboard</a></li>
                @else
                    <li class="active"><a href="{{route('login')}}">Login</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>