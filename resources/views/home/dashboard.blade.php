@extends('layouts/master')

@section('content')

<div class="col-md-10">

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<div class="panel-title">お知らせ</div>
					<!--
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
						<a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
					</div>
					-->
				</div>
				<div class="panel-body">
					<p>現在お知らせはありません。</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<div class="panel-title"><h3><i class="glyphicon glyphicon-paperclip"></i> {{ $user->family_name }} {{ $user->first_name }}さんのデータ</h3></div>
				</div>

				<div class="panel-body">
					<legend>入社日と起算日</legend>
					<table class="table table-bordered">
						<tr class="well"><td>入社日</td><td>起算日</td></tr>
						<tr><td>{{ $user->date_of_entering }}</td><td>{{ $user->base_date }}</td></tr>
					</table>
				</div>

				<div class="panel-body">
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

				<div class="panel-body">
					<div class="col-md-4 col-md-offset-4">
						<a href="/user/reset/{{ Auth::user()->id }}" class="btn btn-danger col-md-12"type="button">リセット</a><br><br>
						<a class="btn btn-success col-md-12"type="button">リセット＋再計算（時間ちょっとかかるかも）</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection<!-- /content -->
