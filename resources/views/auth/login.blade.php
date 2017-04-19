<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>U9 | ログイン</title>
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
		<!-- iCheck -->
		<link rel="stylesheet" href="/assets/plugins/iCheck/square/blue.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition login-page">

		<div class="login-box">
			<div class="login-logo">
				<b>U9</b> Admin
			</div>

			@if (count($errors) > 0)
			<div class="alert alert-danger text-center">
				@foreach ($errors->all() as $error)
				<i class="fa fa-exclamation-triangle"></i> {{ $error }}<br>
				@endforeach
			</div>
			@endif

			<!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">ログイン</p>


				{!! Form::open(['method' => 'post','url' => '/login']) !!}
				{!! csrf_field() !!}

				<div class="form-group has-feedback">
					{!! Form::email('email', '',['class'=>'form-control', 'placeholder' => 'メールアドレス']) !!}
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					{!! Form::password('password', ['class'=>'form-control', 'placeholder' => 'パスワード']) !!}
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="checkbox icheck">
							<label>
								<input type="checkbox"> パスワードを覚える
							</label>
						</div>
					</div>
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat">ログイン</button>
					</div><!-- /.col -->
				</div>
				{!! Form::close() !!}

			</div>
			<!-- /.login-box-body -->
		</div>
		<!-- /.login-box -->

		<!-- jQuery 2.2.3 -->
		<script src="/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<!-- iCheck -->
		<script src="/assets/plugins/iCheck/icheck.min.js"></script>
		<script>
$(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
		</script>
	</body>
</html>