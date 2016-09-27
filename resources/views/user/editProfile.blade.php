@extends('layouts/master')

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
			<h3 class="box-title">ユーザ情報編集</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['method' => 'post', 'url' => ['user/editProfile', $user->id], 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">名前 </label>
				<div class="col-md-2">
					{!! Form::text('last_name',$user->last_name, ['class' => 'form-control', 'placeholder' => '名字']) !!}
					@if($errors->has('last_name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('last_name') }}</strong>
					</span>
					@endif
				</div>
				<div class="col-md-2">
					{!! Form::text('first_name',$user->first_name, ['class' => 'form-control', 'placeholder' => '名前']) !!}
					@if($errors->has('first_name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('first_name') }}</strong>
					</span>
					@endif
				</div>
				<div class="col-md-2">
					<span class="label label-danger">必須</span>
				</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">メールアドレス</label>
				<div class="col-md-6">
					{!! Form::email('email',$user->email, ['class' => 'form-control', 'placeholder' => 'メールアドレス']) !!}
					@if($errors->has('email'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('email') }}</strong>
					</span>
					@endif
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="status" class="col-md-2 control-label">区分</label>
				<div class="col-md-4">
					{!! Form::select('status', App\User::$memberStatus, $user->status, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="department" class="col-md-2 control-label">部署</label>
				<div class="col-md-4">
					{!! Form::select('department', App\User::$departments, $user->department, ['class' => 'form-control', 'placeholder' => 'なし']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">備考</label>
				<div class="col-md-8">
					{!! Form::textarea('memo', $user->memo, ['class' => 'form-control', 'placeholder' => 'なし', 'rows' => 5]) !!}
				</div>
			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">パスワード</label>
				<div class="col-md-4">
					{!! Form::password('password', ['class' => 'form-control']) !!}
					※変更の場合のみ入力してください
					@if($errors->has('password'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('password') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group">
				<label for="password_confirmation" class="col-md-2 control-label">パスワード再入力</label>
				<div class="col-md-4">
					{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
					@if($errors->has('password_confirmation'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
					</span>
					@endif
				</div>
			</div>

			@if(Auth::user()->role === 1)
			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">権限</label>
				<div class="col-md-2">
					{!! Form::select('role', App\User::$roleLabels, $user->role,['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="status" class="col-md-2 control-label">退社済みフラグ</label>
				<div class="col-md-2">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('retire_flg', 1,($user->retire_flg == 1)? true : false) !!} 退社済み
						</label>
					</div>
				</div>
			</div>
			@endif

			{!! Form::hidden('user_id', $user->id) !!}

		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
		</div>

	</div><!-- /.box -->
	{!! Form::close() !!}

</section>
@endsection
