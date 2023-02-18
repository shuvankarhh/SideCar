<ul class="navbar-nav me-auto">
    @foreach ($menu as $item )
        <li class="nav-item">
            <a class="nav-link" href="{{ $item['link'] }}">{{  $item['name'] }}</a>
        </li>    
    @endforeach
</ul>
