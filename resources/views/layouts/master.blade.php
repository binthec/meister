<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>U9 | Dashboard</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="/assets/dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
			 folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="/assets/dist/css/skins/_all-skins.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="/assets/plugins/iCheck/flat/blue.css">
		<!-- custom css -->
		<link rel="stylesheet" href="/css/custom.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">

			<header class="main-header">
				<!-- Logo -->
				<a href="/" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"><b>U9</b></span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><b>U9</b></span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>

					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<span class="hidden-xs">{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}</span>
								</a>
							</li>
							<li>
								<a href="/logout">ログアウト</a>
							</li>
						</ul>
					</div>
				</nav>
			</header>

			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">

						<li class="header">メインメニュー</li>

						<li class="treeview {{ isActiveUrl('dashboard') }}">
							<a href="/dashboard">
								<i class="fa fa-dashboard"></i> <span>Dashboard</span>
							</a>
						</li>

						<li class="treeview {{ isActiveUrl('use_request*') }}">
							<a href="{{ url('use_request/add') }}">
								<i class="fa fa-calendar-check-o"></i> <span>有給消化登録</span>
							</a>
						</li>

						@if(Auth::user()->role === 1)
						<li class="header">管理メニュー</li>

						<li class="treeview {{ isActiveUrl('user*') }}">
							<a href="#">
								<i class="fa fa-users"></i> <span>ユーザ管理</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="{{ isActiveUrl('user') }}"><a href="/user"><i class="fa fa-circle-o"></i> ユーザ一覧</a></li>
								<li class="{{ isActiveUrl('auth/register') }}"><a href="/auth/register"><i class="fa fa-circle-o"></i> ユーザ新規登録</a></li>
							</ul>
						</li>

						<li class="treeview">
							<a href="#">
								<i class="fa fa-laptop"></i> <span>PC管理</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li><a href="#"><i class="fa fa-circle-o"></i> PC一覧</a></li>
								<li><a href="#"><i class="fa fa-circle-o"></i> PC新規登録</a></li>
							</ul>
						</li>
						@endif

					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>


			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">

				{{-- フラッシュメッセージの表示 --}}
				@if (Session::has('flashMessage'))
				<div class="alert alert-primary flashMessage">{{ Session::get('flashMessage') }}</div>
				@endif

				@yield('content')
			</div>
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 1.0.0
				</div>
				<strong>Copyright &copy; since 2016 ishi</strong> All rights
				reserved.
			</footer>

		</div>
		<!-- ./wrapper -->

		<!-- jQuery 2.2.3 -->
		<script src="/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<!-- AdminLTE App -->
		<script src="/assets/dist/js/app.min.js"></script>
		<!-- moment.js -->
		<script src="/plugins/momentjs/moment.js"></script>
		<script src="/plugins/momentjs/ja.js"></script>

		@yield('css')
		@yield('js')

		<!-- custom js -->
		<script src="/js/custom.js"></script>

	</body>
</html>
