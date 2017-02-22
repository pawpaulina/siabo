<!doctype html>
<html>
    <head>
		<title>Timeline Absensi</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href='{{url("css/bootstrap.min.css")}}' rel='stylesheet' />
        @yield('css')
        <style>
            table form { margin-bottom: 0; }
            form ul { margin-left: 0; list-style: none; }
            .error { color: red; font-style: italic; }
            body { padding-top: 20px; }
			
			ul.pagination {
				display: inline-block;
				padding: 0;
				margin: 0;
			}

			ul.pagination li {
				display: inline;
			}

			ul.pagination li a {
				color: black;
				float: left;
				padding: 8px 16px;
				text-decoration: none;
				border-radius: 5px;
				align:center;
			}
			ul.pagination li.active {
				color: black;
				float: left;
				padding: 8px 16px;
				text-decoration: none;
				border-radius: 5px;
				align:center;
			}
			ul.pagination li.disabled {
				color: black;
				float: left;
				padding: 8px 16px;
				text-decoration: none;
				border-radius: 5px;
				align:center;
			}
			ul.pagination li a {
				border: 1px solid #ddd; /* Gray */
			}
			ul.pagination li a:hover:not(.active) {
				background-color: #ddd;
			}
			ul.pagination li a {
				border-radius: 5px;
			}
			ul.pagination li a {
				transition: background-color .3s;
			}
			div.center {
				text-align: center;
			}
			.pagination li:first-child a {
				border-top-left-radius: 5px;
				border-bottom-left-radius: 5px;
			}

			.pagination li:last-child a {
				border-top-right-radius: 5px;
				border-bottom-right-radius: 5px;
			}
        </style>
    </head>

    <body>
		<!-- Header -->
			<header id="header">
				<div class="inner">
					<nav id="nav">
					</nav>
					<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
				</div>
			</header>

		

		<!-- One -->
			<section id="one" class="wrapper">
				<div class="inner">
					<div class="flex flex-3">
						<div class="container">
							@if (Session::has('message'))
								<div class="flash alert">
									<p>{{ Session::get('message') }}</p>
								</div>
							@endif

							@yield('main')
						</div>
					</div>
				</div>
			</section>
<!--jQuery-->
	<script src='{{url("js/jquery-1.9.1.min.js")}}'></script>
	<script src='{{url("js/bootstrap.js")}}'></script>
	<script src='{{url("js/jquery-ui-1.10.2.custom.min.js")}}'></script>
			@yield('script')
	</body>
</html>