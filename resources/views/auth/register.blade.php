@extends('layouts/master')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		ユーザ管理
		<small>Control panel</small>
	</h1>
</section>

<!-- Main content -->
<section class="content">

	{!! Form::open(['method' => 'post', 'url' => 'auth/register', 'class' => 'form-horizontal']) !!}
	{{ csrf_field() }}
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">新規追加</h3>
		</div>
		<div class="box-body">

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>！入力項目に不備があります！</strong> <br>
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">名前</label>
				<div class="col-md-2">
					{!! Form::text('last_name','', ['class' => 'form-control', 'placeholder' => '名字']) !!}
				</div>
				<div class="col-md-2">
					{!! Form::text('first_name','', ['class' => 'form-control', 'placeholder' => '名前']) !!}
				</div>
				<div class="col-md-2">
					<span class="label label-danger">必須</span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label">メールアドレス</label>
				<div class="col-md-6">
					{!! Form::email('email','', ['class' => 'form-control', 'placeholder' => 'メールアドレス']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">権限</label>
				<div class="col-md-2">
					{!! Form::select('role', App\User::$roleLabels, null,['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">パスワード</label>
				<div class="col-md-4">
					{!! Form::password('password', ['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label">パスワード再入力</label>
				<div class="col-md-4">
					{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<hr>

			<!-- Date -->
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">入社日</label>
				<div class="col-md-4">
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						{!! Form::date('date_of_entering','', ['class' => 'form-control pull-right use_datepicker', 'id' => 'date_of_entering']) !!}
					</div>
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">起算日</label>
				<div class="col-md-6">
					<p class="form-control-static font18" id="base_date_text">
						入社日を入力すると自動で算出されます。
					</p>
				</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">備考</label>
				<div class="col-md-8">
					{!! Form::textarea('memo','', ['class' => 'form-control', 'rows' => 5]) !!}
				</div>
			</div>

			{!! Form::hidden('base_date','', ['class' => 'form-control', 'id' => 'base_date']) !!}

		</div>

		<div class="box-footer">
			<div class="form-group">
				<div class="col-md-4 col-md-offset-2">
					<button type="submit" class="btn btn-primary">　登録　</button>
				</div>
			</div>

		</div>

	</div><!-- /.box -->
	{!! Form::close() !!}

</section>
@endsection<!-- /content -->

@section('js')
@include('elements.for_form')

{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection