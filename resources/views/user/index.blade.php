@extends('layouts/master')
@section('title', 'ユーザ一覧')

@section('content')

<section class="content-header">
	<h1>ユーザ管理</h1>
</section>

<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">ユーザ一覧</h3>
		</div>

		@if($users->count() > 0)

		<div class="box-body">
			<table id="users" class="table table-bordered table-striped">
                <thead class="bg-primary">
					<tr>
						<th>名前</th>
						<th>メールアドレス</th>
						<th>U9サイト内権限</th>
						<th>入社日</th>
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
						<td class="font18">{{ $user->getSumRemainingDays() }}</td>
						<td><a href="{{ action('UserController@show', $user->id) }}" class="btn btn-primary">詳細表示</a></td>
					</tr>
					@endforeach
                </tbody>
			</table>
		</div><!-- /.box-body -->
		<div class="box-footer text-center">
			{!! $users->render() !!}
		</div>

		@else
		<div class="box-body">
			<p>ユーザが存在しません。</p>
		</div>
		@endif
	</div><!-- /.box -->

</section>
@endsection