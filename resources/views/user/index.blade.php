@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h1>ユーザ一覧</h1>

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
					<td>{{ $user->family_name }} {{ $user->first_name }}</td>
					<td>{{ $user->date_of_entering }}</td>
					<td>{{ $user->base_date }}</td>
					<td>{{ $user->getRemainingDays() }}</td>
					<td><button type="button" class="btn btn-success btn-sm" name="edit" onclick="location.href='/user/edit/{{ $user->id }}'">編集</button></td>
				</tr>
				<?php $i += 1 ?>
				@endforeach

			</tbody>
		</table>

	</div>
</div>
@endsection