@extends('layouts/master')
@section('title', 'プロフィール編集')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		ユーザ管理
	</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">パスワード変更</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['method' => 'put', 'action' => ['UserController@passwordUpdate', $user->id], 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}

			@if($errors->has('permission'))
				<span class="help-block">
						<strong class="text-danger">{{ $errors->first('permission') }}</strong>
                </span>
			@endif

			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<label for="ID" class="col-md-2 control-label">パスワード <span class="text-danger">*</span></label>
				<div class="col-md-4">
					{!! Form::password('password', ['class' => 'form-control', 'placeholder' => '8文字以上の半角英数字']) !!}
					@if($errors->has('password'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('password') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
				<label for="password_confirmation" class="col-md-2 control-label">パスワード再入力 <span class="text-danger">*</span></label>
				<div class="col-md-4">
					{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'パスワードをもう一度入力してください']) !!}
					@if($errors->has('password_confirmation'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
					</span>
					@endif
				</div>
			</div>

			{!! Form::hidden('user_id', $user->id) !!}

		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
		</div>

	</div><!-- /.box -->
	{!! Form::close() !!}

</section>
@endsection
