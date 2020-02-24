<!doctype html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <link rel="shortcut icon" href="https://newspace1.nyc3.digitaloceanspaces.com/themes/company/alldatalogistic/logo.ico">
    <title>{{ config('app.name', 'All Data Logistic, S.A.') }} - All Data Logistic, S.A.</title>

		<link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Jquery UI CSS  -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
    <!-- Wrap CSS  -->
    <link rel="stylesheet" href="{{ URL::asset('attached/css/wrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('attached/css/index.css') }}">
    <script src="https://kit.fontawesome.com/72f3daada8.js" crossorigin="anonymous"></script>
    
</head>
<body>
	<div id="app" class="h_100p">
		<nav class="navbar navbar-expand-lg navbar-dark bg_adl">
			<div class="container">
					<a class="navbar-brand" href="{{ url('/') }}">
						<img src="{{ URL::asset('attached/image/rect_long_logo.png') }}" alt="" height="40px">
						{{-- {{ config('app.name', 'All Data Logistic') }} --}}
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<!-- Left Side Of Navbar -->
						<ul class="navbar-nav mr-auto"></ul>
						<!-- Right Side Of Navbar -->
						<ul class="navbar-nav ml-auto">
							<!-- Authentication Links -->
							@guest
								<a target="_blank" class="nav-item nav-link text-white" href="https://alldatalogistic.com/"><i class="fas fa-link"></i></a>
								<a target="_blank" class="nav-item nav-link text-white" href='https://www.tiktok.com/@alldatalogistic_1?lang=es'><i class="fas fa-video"></i></a>
								<a target="_blank" class="nav-item nav-link text-white" href="https://twitter.com/alldatalogistic/"><i class="fab fa-twitter"></i></a>
								<a target="_blank" class="nav-item nav-link text-white" href="https://instagram.com/alldatalogistic/"><i class="fab fa-instagram"></i></a>
							@endguest
						</ul>
					</div>
			</div>
		</nav>
		<main class="py-4 main_bg_statistic h_100p" >
			@yield('content')
		</main>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="{{ URL::asset('attached/js/validCampoFranz.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('attached/js/adl.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('attached/js/user.js') }}"></script>
	@yield('optional_javascript')
</body>
</html>
