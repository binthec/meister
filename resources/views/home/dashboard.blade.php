@extends('layouts/master')

@section('content')

<div class="row">
	<div class="col-md-offset-2 col-md-8 well">
		<h2>お知らせ</h2>
		<p>現在お知らせはありません。</p>
		<p></p>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-offset-3 col-md-6">
		<h3>{{ $user->family_name }} {{ $user->first_name }}さんのデータ</h3>
		<p></p>
		<table class="table table-bordered">
			<tr class="well"><td>入社日</td><td>起算日</td></tr>
			<tr><td>{{ $user->date_of_entering }}</td><td>{{ $user->base_date }}</td></tr>
		</table>
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td class="well col-md-5">{{ $today->year }}年{{ $today->month }}月{{ $today->day }}日時点の有給残日数</td>
					<td>◯◯日</td>
				</tr>
				<tr>
					<td class="well">{{ $today->year + 2 }}年{{ $today->month }}月{{ $today->day -1 }}日まで有効の有給日数</td>
					<td>◯◯日</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@endsection<!-- /content -->