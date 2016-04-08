<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title></title>

		<!-- stylesheet -->
		<link rel="stylesheet" href="/css/bootstrap.css">
		<link rel="stylesheet" href="/css/style.css">

		<!-- datepicker用テーマ -->
		<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" rel="stylesheet" />

		<!-- JS -->
		<script src="/js/jquery-1.12.1.js"></script>
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<script src="/js/bootstrap.js"></script>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script><!-- datepicker用JS -->
		<script type="text/javascript" src="/js/exdate.js"></script><!-- exdate用JS -->

	</head>
	<body>
		@include('layouts/navbar')

		<div class="container-fluid">
			{{-- フラッシュメッセージの表示 --}}
			@if (Session::has('flash_message'))
			<div class="alert alert-success flash_message">{{ Session::get('flash_message') }}</div>
			@endif

			{{-- 本体 --}}
			@yield('content')
		</div>

		{{-- フラッシュメッセージ用のjs --}}
		<script src="/js/flash_message.js"></script>

	</body>
</html>