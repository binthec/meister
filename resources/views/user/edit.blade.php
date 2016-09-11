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
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">編集</h3>
		</div>
		<div class="box-body">
			{!! Form::open(['method' => 'post', 'url' => ['user/edit', $user->id], 'class' => 'form-horizontal']) !!}

			{{ csrf_field() }}
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">ID</label>
				<div class="col-md-8 form-control-static">{{ $user->id }}</div>
			</div>

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">名前</label>
				<div class="col-md-2">
					{!! Form::text('last_name',$user->last_name, ['class' => 'form-control', 'placeholder' => '名字']) !!}
				</div>
				<div class="col-md-2">
					{!! Form::text('first_name',$user->first_name, ['class' => 'form-control', 'placeholder' => '名前']) !!}
				</div>
				<div class="col-md-2">
					<span class="label label-danger">必須</span>
				</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">メールアドレス</label>
				<div class="col-md-6">
					{!! Form::email('email',$user->email, ['class' => 'form-control', 'placeholder' => 'メールアドレス']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">権限</label>
				<div class="col-md-2">
					{!! Form::select('role', App\User::$roleLabels, $user->role,['class' => 'form-control']) !!}
				</div>
				<span class="label label-danger">必須</span>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">パスワード</label>
				<div class="col-md-4">
					{!! Form::password('password', ['class' => 'form-control']) !!}
					※変更の場合のみ入力してください
				</div>
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
						{!! Form::date('date_of_entering', App\User::getJaDate($user->date_of_entering), ['class' => 'form-control pull-right use_datepicker', 'id' => 'date_of_entering']) !!}
					</div>
				</div>
				<span class="label label-danger">必須</span>
			</div>


			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">起算日</label>
				<div class="col-md-6">
					<p class="form-control-static font18" id="base_date_text">
						@if(!empty($user->base_date))
						{{ App\User::getJaDate($user->base_date) }}
						@else
						入社日を入力すると自動で算出されます。
						@endif
					</p>
				<!--<input type="date" class="form-control" name="base_date" id="base_date" value="{{ $user->base_date }}"></input>-->
				</div>
			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">備考</label>
				<div class="col-md-8">
					<textarea class="form-control" name="memo" id="memo" value="{{ $user->memo }}">{{ $user->memo }}</textarea>
				</div>
			</div>

			{!! Form::hidden('base_date', App\User::getJaDate($user->base_date),['class' => 'form-control', 'id' => 'base_date']) !!}
			{!! Form::hidden('user_id', $user->id,['class' => 'form-control']) !!}

		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
		</div>

	</div><!-- /.box -->
	{!! Form::close() !!}

</section>
@endsection


@section('js')
@include('elements.for_form')

{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection