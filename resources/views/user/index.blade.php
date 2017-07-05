@extends('layouts/master')
@section('title', 'ユーザ一覧')

@section('content')

<section class="content-header">
	<h1>ユーザ管理</h1>
</section>

<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">検索</h3>
		</div>

		{!! Form::open(['url' => '/user', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
		<div class="box-body">
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">名前</label>
				<div class="col-md-3">
					{!! Form::text('name', Request::input('name'), ['class' => 'form-control', 'placeholder' => '氏名の like 検索']) !!}
				</div>

				<label for="bought_at" class="col-md-2 control-label">入社日</label>
				<div class="col-md-3 form-inline">
					{!! Form::text('after', Request::has('after')? Request::input('after'): '', ['class' => 'form-control use_datepicker']) !!}
					&ensp;〜&ensp;
					{!! Form::text('before', Request::has('before')? Request::input('before'): '', ['class' => 'form-control use_datepicker']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="disposal" class="col-md-2 control-label">退職済</label>
				<div class="col-md-3">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('searchInactive', 1, Request::input('searchInactive') == 1? true: false) !!} 退職済みも含める
						</label>
					</div>
				</div>
			</div>
		</div><!-- /.box-body -->

		<div class="box-footer">
			<a href="{{ url('/user') }}" class="btn btn-warning">リセット</a>
			<button class="btn btn-primary pull-right" type="submit" name="search"> 検&emsp;索 </button>
		</div>
		{!! Form::close() !!}
	</div>

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">ユーザ一覧</h3>
			<span class="pull-right"><span class="font18 text-blue">{{ ($users->count() > 0)? $users->total() : '0' }}</span> 件</span>
		</div>

		@if($users->count() > 0)

		<div class="box-body">
			<table id="users" class="table table-bordered table-striped">
				<thead class="bg-primary">
					<tr>
						<th>名前</th>
						<th>メールアドレス</th>
						<th>サイト内権限</th>
						<th>入社日</th>
						<th>ステータス</th>
						<th>有給残日数</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
					<tr>
						<td>{{ $user->last_name }} {{ $user->first_name }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ App\User::$roleLabels[$user->role] }}</td>
						<td>{{ App\User::getJaDate($user->date_of_entering) }}</td>
						<td class="{{ ($user->status == App\User::RETIRED)? 'text-red': '' }}">{{ App\User::$status[$user->status] }}</td>
						<td class="font18">{{ ($user->status == App\User::RETIRED)? '-': $user->getSumRemainingDays() }}</td>
						<td><a href="{{ action('UserController@show', $user->id) }}" class="btn btn-primary">詳細表示</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div><!-- /.box-body -->
		<div class="box-footer text-center">
			{!! $users->appends(Request::all())->render() !!}
		</div>

		@else
		<div class="box-body">
			<p>ユーザが存在しません。</p>
		</div>
		@endif
	</div><!-- /.box -->

</section>
@endsection

@section('js')
@include('elements.for_form')
<script>
    $(function () {
        $(".use_datepicker").datepicker({
            language: "ja",
            format: "yyyy年m月d日",
            autoclose: true,
            orientation: "top left"
        });
    });
</script>
@endsection