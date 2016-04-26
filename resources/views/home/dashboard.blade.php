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
		{{--
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
		--}}
	</div>
</div>


<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<legend>有給残日数</legend>
		@foreach(Auth::user()->getValidPaidVacation() as $remaining_day)
		<div class="col-md-4 inline">
			<div class="panel panel-default align-center">
				<div class="panel-heading">{{ $remaining_day->limit_date }}日期限</div>
				<div class="panel-body font13">
					{{ $remaining_day->remaining_days }}
				</div>
			</div>
		</div>
		@endforeach
		<div class="col-md-4">
			<div class="panel panel-default align-center">
				<div class="panel-heading">合計有給残日数</div>
				<div class="panel-body font13">
					{{ Auth::user()->addRemainingDays() }}
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<button class="btn btn-success col-md-12"type="button">再計算</button>
	</div>
</div>
@endsection<!-- /content -->