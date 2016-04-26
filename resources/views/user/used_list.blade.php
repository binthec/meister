@extends('layouts/master')

@section('content')
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h1>登録済み有給一覧</h1>

		<table class="table table-bordered">
			<thead class="well">
			<th width="5%">№</th><th>開始日</th><th>終了日</th><th>日数</th><th width="10%">操作</th>
			</thead>
			<tbody>

				<?php $i = 1 ?>
				{{dd($used_days)}}
				{{-- dd(Auth::user()->usedDays) --}}
				@foreach (Auth::user()->usedDays->paginate(5) as $used_day)
				<tr>
					<td class="middle">{{ $i }}</td>
					<td>{{ $used_day->from }}</td>
					<td>{{ $used_day->until }}</td>
					<td>{{ $used_day->used_days }}</td>
					<td>
						<button type="button" class="btn btn-success btn-sm" name="edit" onclick="location.href='/user/use_request_edit/{{ Auth::user()->id }}'">編集</button>
						<button type="button" class="btn btn-success btn-sm" name="delete" onclick="location.href='/user/use_request_delete/{{ Auth::user()->id }}'">削除</button>
					</td>
					<?php $i += 1 ?>
				</tr>

				<?php $i += 1 ?>
				@endforeach

			</tbody>
		</table>

	</div>
</div>
@endsection