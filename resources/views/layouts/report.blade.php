<!doctype html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="https://newspace1.nyc3.digitaloceanspaces.com/themes/company/alldatalogistic/logo.ico">
	<title>{{ config('app.name', 'All Data Logistic, S.A.') }}</title>
	
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="{{ URL::asset('attached/css/wrap.css') }}">
	<script src="https://kit.fontawesome.com/72f3daada8.js" crossorigin="anonymous"></script>
    
</head>
<body>
	<div id="app">
		

		<main class="" >


			<nav id="side_panel" class="navbar" style="display: initial;">
				<div class="col-sm-12 side_panel_logo_container mt-1">
					<img src="{{ URL::asset('attached/image/logo_limpio_rect.png')}}" width="150px" alt="">
				</div>
				<nav class="nav flex-column pt-3">
					@yield('nav_item')
				</nav>
				<div class="dropdown-divider mt-5"></div>
				<ul class="navbar-nav ml-auto">	
					@guest
						<li class="nav-item">
								<a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
						</li>
					@else
						<li><a class="nav-item nav-link text-white" href="{{URL::asset('/report')}}">Reportes</a></li>
						<li><a class="nav-item nav-link text-white" href="{{ route('home') }} ">Combustible</a></li>
						@if ( auth()->user()->checkRole('admin'))
							<li class="nav-item">
									<a class="nav-link text-white" href="{{URL::asset('/register')}}">{{ __('Registrar') }}</a>
							</li>
						@endif
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item social" href='https://www.tiktok.com/@alldatalogistic_1?lang=es'><i class="fas fa-video"></i></a>
								<a class="dropdown-item social" href="https://twitter.com/alldatalogistic/"><i class="fab fa-twitter"></i></a>
								<a class="dropdown-item social" href="https://instagram.com/alldatalogistic/"><i class="fab fa-instagram"></i></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="{{ URL::asset('/user/edit/edit')}}"><i class="fas fa-wrench"> </i> {{ __(' Opciones') }}</a>
								<a class="dropdown-item" href="{{ route('logout') }}"
								onclick="event.preventDefault();
																			document.getElementById('logout-form').submit();"><i class="fas fa-door-open"> </i>
												{{ __(' Logout') }}
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>
							</div>
						</li>
					@endguest
				</ul>

			</nav>

				@yield('content')
		</main>
	</div>

    
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="{{ URL::asset('attached/js/validCampoFranz.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('attached/js/adl.js') }}"></script>
    @yield('optional_javascript')
</body>
</html>
