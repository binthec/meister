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
			<h3 class="box-title">ユーザ情報編集</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['method' => 'put', 'action' => ['UserController@update', $user->id], 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}

			@if($errors->has('permission'))
				<span class="help-block">
						<strong class="text-danger">{{ $errors->first('permission') }}</strong>
                </span>
			@endif

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">名前 <span class="text-danger">*</span></label>
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
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">メールアドレス <span class="text-danger">*</span></label>
				<div class="col-md-6">
					{!! Form::email('email',$user->email, ['class' => 'form-control', 'placeholder' => 'メールアドレス']) !!}
					@if($errors->has('email'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('email') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group">
				<label for="status" class="col-md-2 control-label">区分</label>
				<div class="col-md-4">
					{!! Form::select('type_of_employment', App\User::$typeOfEmployments, $user->type_of_employment, ['class' => 'form-control']) !!}
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

			@if(Auth::user()->role === 1)
			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">権限 <span class="text-danger">*</span></label>
				<div class="col-md-2">
					{!! Form::select('role', App\User::$roleLabels, $user->role,['class' => 'form-control']) !!}
				</div>
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
