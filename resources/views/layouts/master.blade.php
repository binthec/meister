<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Meister | @yield('title')</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="/assets/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="/assets/ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="/assets/dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
			 folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="/assets/dist/css/skins/skin-blue.min.css">
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
				<a href="{{ url('/') }}" class="logo">
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
								<a href="{{ action('UserController@show', Auth::user()->id) }}">
									<span class="hidden-xs">{{ Auth::user()->last_name }} {{ Auth::user()->first_name }}</span>
								</a>
							</li>
							<li>
								<a href="{{ url('logout') }}">ログアウト</a>
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
							<a href="{{ url('/dashboard') }}">
								<i class="fa fa-dashboard"></i> <span>Dashboard</span>
							</a>
						</li>

						<li class="treeview {{ isActiveUrl('vacation*') }}">
							<a href="{{ url('vacation/create') }}">
								<i class="fa fa-calendar-check-o"></i> <span>有給消化登録</span>
							</a>
						</li>

						<li class="treeview {{ isActiveUrl('device*') }}">
							<a href="#">
								<i class="fa fa-laptop"></i> <span>デバイス管理</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="{{ isActiveUrl('device') }}"><a href="{{ url('device') }}"><i class="fa fa-circle-o"></i> デバイス一覧・検索</a></li>
								<li class="{{ isActiveUrl('device/create') }}"><a href="{{ url('device/create') }}"><i class="fa fa-circle-o"></i> デバイス新規登録</a></li>
							</ul>
						</li>

						<li class="treeview {{ isActiveUrl('license*') }}">
							<a href="{{ url('license') }}">
								<i class="fa fa-user"></i> ライセンス管理
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="{{ isActiveUrl('license') }}">
									<a href="{{ url('license') }}"><i class="fa fa-circle-o"></i> ライセンス一覧・検索</a>
								</li>
								<li class="{{ isActiveUrl('license/*') }}">
									<a href="{{ url('license') }}"><i class="fa fa-circle-o"></i> ラインセンス本体操作
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li class="{{ isActiveUrl('license/maker') }}">
											<a href="{{ url('license/maker') }}"><i class="fa fa-circle-o"></i> メーカー一覧・追加</a>
										</li>
										<li class="{{ isActiveUrl('license/create') }}">
											<a href="{{ url('license/create') }}"><i class="fa fa-circle-o"></i> ライセンス新規登録</a>
										</li>
									</ul>
								</li>
								<li class="{{ isActiveUrl('license/use/*') }}">
									<a href="{{ url('license/use/create') }}"><i class="fa fa-circle-o"></i> ユーザ利用登録
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li class="{{ isActiveUrl('license/use/create') }}">
											<a href="{{ url('license/use/create') }}"><i class="fa fa-circle-o"></i> ライセンス使用登録</a>
										</li>
									</ul>
								</li>
							</ul>
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
								<li class="{{ isActiveUrl('user') }}"><a href="/user"><i class="fa fa-circle-o"></i> ライセンス一覧・検索</a></li>
								<li class="{{ isActiveUrl('user/register') }}"><a href="{{ url('/user/register') }}"><i class="fa fa-circle-o"></i> ライセンス新規登録</a></li>
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
				@if(session('flashMsg'))
				<div class="alert alert-primary flashMsg">{{ session('flashMsg') }}</div>
				@elseif(session('flashErrMsg'))
				<div class="alert alert-danger flashMsg">{{ session('flashErrMsg') }}</div>
				@endif

				@yield('content')
			</div>
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 1.1.0
				</div>
				<strong>Copyright &copy; since 2017 ishi</strong> All rights reserved.
			</footer>

		</div>
		<!-- ./wrapper -->

		<!-- jQuery 2.2.3 -->
		<script src="/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="/assets/plugins/jQuery/jquery-ui-1.11.4.min.js"></script>
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
