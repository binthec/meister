<!DOCTYPE html>
<html>
	<head>
		<title>U9システム</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- jQuery UI -->
		<link href="https://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" media="screen">
		<!-- styles -->
		<link href="/css/styles.css" rel="stylesheet">
		<link href="/css/custom.css" rel="stylesheet">

		<link href="/vendors/form-helpers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
		<link href="/vendors/select/bootstrap-select.min.css" rel="stylesheet">
		<link href="/css/forms.css" rel="stylesheet">

		<link href="/vendors/bootstrap-datetimepicker/datetimepicker.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

	</head>
	<body>
		<div class="header">
			<div class="container">
				<div class="row">
					<div class="col-md-5">
						<!-- Logo -->
						<div class="logo">
							<h1><a href="/admin">U9</a></h1>
						</div>
					</div>
					<!--					
					<div class="col-md-5">
						<div class="row">
							<div class="col-lg-12">
								<div class="input-group form">
									<input type="text" class="form-control" placeholder="Search...">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="button">Search</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					-->
					@if (!Auth::guest())
					<div class="col-md-2 col-md-offset-5">
						<div class="navbar navbar-inverse" role="banner">
							<nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
								<ul class="nav navbar-nav">
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> アカウント <b class="caret"></b></a>
										<ul class="dropdown-menu animated fadeInUp">
											<li><a href="/user/{{ Auth::user()->id }}">ユーザ情報</a></li>
											<li><a href="/logout">ログアウト</a></li>
										</ul>
									</li>
								</ul>
							</nav>
						</div>
					</div>
					@endif

				</div>
			</div>
		</div>

		<div class="page-content">
			<div class="row">
				<div class="col-md-2">
					<div class="sidebar content-box" style="display: block;">
						<ul class="nav">
							<!-- Main menu -->
							<li class="current"><a href="/dashboard"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
							<li><a href="/use_request"><i class="glyphicon glyphicon-bookmark"></i> 有給消化登録</a></li>
							@if (!Auth::guest())
							<!-- adminユーザのみユーザ一覧を利用可 -->
							@if(Auth::user()->role == 'admin')
							<li class="submenu">
								<a href="#">
									<i class="glyphicon glyphicon-pencil"></i> ユーザ管理
									<span class="caret pull-right"></span>
								</a>
								<!-- Sub menu -->
								<ul>
									<li><a href="/user">ユーザ一覧</a></li>
									<li><a href="/auth/register">ユーザ追加</a></li>
								</ul>
							</li>
							@endif
							@endif

						</ul>
					</div>
				</div>

				{{-- フラッシュメッセージの表示 --}}
				@if (Session::has('flashMessage'))
				<div class="alert alert-success flash-message">{{ Session::get('flashMessage') }}</div>
				@endif

				@yield('content')

			</div>
		</div>

		<footer>
			<div class="container">
				<div class="copy text-center">
					Copyright 2016 <a href='#'>Laravel5.2 Training</a>
				</div>
			</div>
		</footer>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://code.jquery.com/jquery.js"></script>
		<!-- jQuery UI -->
		<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="/bootstrap/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="/js/moment.js"></script><!-- moment.js -->

		@yield('css')
		@yield('js')

		<script src="/js/custom.js"></script>
	</body>
</html>