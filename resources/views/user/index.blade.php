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
			<h3 class="box-title">一覧</h3>
		</div>
		<div class="box-body">
			<table class="table table-bordered">
				<thead class="well">
				<th width="5%">№</th><th>ID</th><th>名前</th><th>入社日</th><th>起算日</th><th>有給残日数</th><th width="10%">操作</th>
				</thead>
				<tbody>

					<?php $i = 1 ?>
					@foreach($users as $user)
					<tr>
						<td class="middle">{{ $i }}</td>
						<td>{{ $user->id }}</td>
						<td>{{ $user->last_name }} {{ $user->first_name }}</td>
						<td>{{ App\User::getJaDate($user->date_of_entering) }}</td>
						<td>{{ App\User::getJaDate($user->base_date) }}</td>
						<td>{{ $user->getSumRemainingDays() }}</td>
						<td><button type="button" class="btn btn-primary btn-sm" name="edit" onclick="location.href='/user/edit/{{ $user->id }}'">編集</button></td>
					</tr>
					<?php $i += 1 ?>
					@endforeach

				</tbody>
			</table>
		</div>

		<div class="box-footer">
		</div>
	</div><!-- /.box -->

</section>

@endsection