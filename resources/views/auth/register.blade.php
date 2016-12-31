@extends('layouts/master')
@section('title', 'ユーザ新規登録')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		ユーザ管理
	</h1>
</section>

<!-- Main content -->
<section class="content">

	{!! Form::open(['method' => 'post', 'action' => 'Auth\AuthController@postRegister', 'class' => 'form-horizontal']) !!}
	{{ csrf_field() }}
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">新規登録</h3>
		</div>
		<div class="box-body">

			<div class="form-group{{ ($errors->has('last_name') || $errors->has('first_name'))? ' has-error': '' }}">
				<label for="name" class="col-md-2 control-label">名前 <span class="text-danger">*</span></label>
				<div class="col-md-2">
					{!! Form::text('last_name','', ['class' => 'form-control', 'placeholder' => '名字']) !!}
					@if($errors->has('last_name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('last_name') }}</strong>
					</span>
					@endif
				</div>
				<div class="col-md-2">
					{!! Form::text('first_name','', ['class' => 'form-control', 'placeholder' => '名前']) !!}
					@if($errors->has('first_name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('first_name') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('email') ? ' has-error': '' }}">
				<label for="email" class="col-md-2 control-label">メールアドレス <span class="text-danger">*</span></label>
				<div class="col-md-6">
					{!! Form::email('email','', ['class' => 'form-control', 'placeholder' => 'メールアドレス']) !!}
					@if($errors->has('email'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('email') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('status') ? ' has-error': '' }}">
				<label for="status" class="col-md-2 control-label">区分</label>
				<div class="col-md-4">
					{!! Form::select('status', App\User::$memberStatus, '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group{{ $errors->has('department') ? ' has-error': '' }}">
				<label for="department" class="col-md-2 control-label">部署</label>
				<div class="col-md-4">
					{!! Form::select('department', App\User::$departments, '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group{{ $errors->has('password') ? ' has-error': '' }}">
				<label for="password" class="col-md-2 control-label">パスワード <span class="text-danger">*</span></label>
				<div class="col-md-4">
					{!! Form::password('password', ['class' => 'form-control', 'placeholder' => '8文字以上の半角英数字']) !!}
					@if($errors->has('password'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('password') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error': '' }}">
				<label for="password_confirmation" class="col-md-2 control-label">パスワード再入力 <span class="text-danger">*</span></label>
				<div class="col-md-4">
					{!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'もう一度入力してください']) !!}
					@if($errors->has('password_confirmation'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<hr>

			<!-- Date -->
			<div class="form-group{{ $errors->has('date_of_entering') ? ' has-error': '' }}">
				<label for="date_of_entering" class="col-md-2 control-label">入社日 <span class="text-danger">*</span></label>
				<div class="col-md-4">
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						{!! Form::date('date_of_entering','', ['class' => 'form-control pull-right use_datepicker', 'id' => 'date_of_entering']) !!}

					</div>
					@if($errors->has('date_of_entering'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('date_of_entering') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('base_date') ? ' has-error': '' }}">
				<label for="base_date" class="col-md-2 control-label">起算日</label>
				<div class="col-md-6">
					<p class="form-control-static font18" id="base_date_text">
						入社日を入力すると自動で算出されます。
					</p>
				</div>
			</div>
			{!! Form::hidden('base_date','', ['class' => 'form-control', 'id' => 'base_date']) !!}

			<hr>

			<div class="form-group{{ $errors->has('role') ? ' has-error': '' }}">
				<label for="role" class="col-md-2 control-label">U9サイト内権限</label>
				<div class="col-md-2">
					{!! Form::select('role', App\User::$roleLabels, null,['class' => 'form-control']) !!}
				</div>
			</div>

			<hr>

			<div class="form-group{{ $errors->has('memo') ? ' has-error': '' }}">
				<label for="memo" class="col-md-2 control-label">備考</label>
				<div class="col-md-8">
					{!! Form::textarea('memo','', ['class' => 'form-control', 'rows' => 3]) !!}
				</div>
			</div>

		</div>

		<div class="box-footer">
			<div class="form-group">
				<div class="col-md-4 col-md-offset-2">
					{!! Form::submit('登 録', ['class' => 'btn btn-primary']) !!}
				</div>
			</div>

		</div>

	</div><!-- /.box -->
	{!! Form::close() !!}

</section>
@endsection<!-- /content -->

@section('js')
@include('elements.for_form')

<script>
    $(function () {
        $(".use_datepicker").datepicker({
            language: "ja",
            format: "yyyy年m月d日",
            autoclose: true,
            orientation: "bottom left"
        });
    });
</script>

{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection