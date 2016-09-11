@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		有給消化申請・一覧
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">申請</h3>
		</div>
		<div class="box-body">


			{!! Form::open(['url' => '/use_request', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			{{ csrf_field() }}

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">ID</label>
				<div class="col-md-8 form-control-static">{{ Auth::user()->id }}</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">名前</label>
				<div class="col-md-8 form-control-static">
					<p>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }} さん</p>
				</div>
			</div>

			<div class="form-group request_remaining_day">
				<label for="ID" class="col-md-2 control-label">有給残日数</label>



				<div class="col-md-2">
					<div class="panel panel-default align-center">
						<div class="panel-heading text-center">
							@if ($validPaidVacations->count() > 0)
							{{ App\User::getJaDate($validPaidVacations->first()->limit_date) }} 日期限
							@else
							未確定
							@endif
						</div>
						<div class="panel-body font18 text-center">
							@if ($validPaidVacations->count() > 0)
							{{ $validPaidVacations->first()->remaining_days }} 日
							@else
							0
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-1 text-center">
					<p class="font18">＋</p>
				</div>

				<div class="col-md-2">
					<div class="panel panel-default align-center">
						<div class="panel-heading text-center">
							@if ($validPaidVacations->count() == 2)
							{{ App\User::getJaDate($validPaidVacations->last()->limit_date) }} 日期限
							@else
							未確定
							@endif
						</div>
						<div class="panel-body font18 text-center">
							@if ($validPaidVacations->count() == 2)
							{{ $validPaidVacations->last()->remaining_days }} 日
							@else
							0
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-1 text-center">
					<p class="font18">＝</p>
				</div>

				<div class="col-md-2">
					<div class="panel panel-default align-center">
						<div class="panel-heading text-center">合計有給残日数</div>
						<div class="panel-body font18 text-center">
							{{ Auth::user()->getSumRemainingDays() }} 日
						</div>
					</div>
				</div>

			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">有給申請期間</label>
				<div class="col-md-6">
					{!! Form::date('daterange', '', ['class' => 'form-control use_daterange font18', 'placeholder' => '日付を選択してください']) !!}
				</div>
				<div class="col-md-4 form-control-static">
					<span class="font18" id="sum_box">合計： <span id="sum"></span> 日間</span>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-10 col-md-offset-2">

					<!-- １日以下しか休まない場合 -->
					<div id="single" class="cant-use">
						<div class="col-md-3 well">
							<label>半休の選択</label>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('from_am', '', false, ['class' => 'half']) !!} 午前半休
								</label>
							</div>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('from_pm', '', false, ['class' => 'half']) !!} 午後半休
								</label>
							</div>
						</div>
					</div>

					<!-- 複数日休む場合 -->
					<div id="plural" class="cant-use">
						<div class="col-md-3 well">
							<label>開始日の半休選択</label>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('from_pm', '', false, ['class' => 'half']) !!} 午後半休
								</label>
							</div>
						</div>

						<div class="col-md-3 well" style="margin-left: 20px;">
							<label>終了日の半休選択</label>
							<div class="checkbox">
								<label>
									{!! Form::checkbox('until_pm', '', false, ['class' => 'half']) !!} 午前半休
								</label>
							</div>
						</div>
					</div>

				</div>
			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">連絡事項</label>
				<div class="col-md-8">
					{!! Form::textarea('memo', '', ['class' => 'form-control', 'rows' => 5]) !!}
				</div>
			</div>

			{!! Form::hidden('from', '',['id' => 'from']) !!}
			{!! Form::hidden('until', '',['id' => 'until']) !!}
			{!! Form::hidden('used_days', '',['id' => 'used_days']) !!}
			{!! Form::hidden('user_id', Auth::user()->id) !!}

			<div class="box-footer">
				<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
			</div>
			{!! Form::close() !!}

		</div>
	</div>




	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">申請済一覧</h3>
		</div>
		<div class="box-body">


			@if($used_days->count())
			<table class="table table-bordered">
				<thead class="well">
				<th width="5%">№</th><th>開始日</th><th>終了日</th><th>日数</th><th width="15%">操作</th>
				</thead>
				<tbody>

					<?php $i = ($used_days->currentPage() - 1) * 5 + 1 ?>
					{{-- dd(Auth::user()->usedDays) --}}
					@foreach ($used_days as $used_day)
					<tr>
						<td class="middle">{{ $i }}</td>
						<td>{{ App\User::getJaDate($used_day->from) }}</td>
						<td>{{ App\User::getJaDate($used_day->until) }}</td>
						<td>{{ $used_day->used_days }}</td>
						<td>
							<a type="button" class="btn btn-primary btn-sm" name="edit" href="{{ url('use_request/edit', $used_day->id) }}">編集</a>
							<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $used_day->id }}">削除</button>

							<!-- deleteModalWindow -->
							<div class="modal fade" id="deleteModal{{ $used_day->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="ModalLabel">申請済有給の削除</h4>
										</div>
										<div class="modal-body">
											<p class="alert-danger align-center">この処理は取り消せません</p>
											<p>選択した申請済有給を削除します。よろしいですか？</p>
											<p>
												削除する有給：{{ $used_day->from }} 〜 {{ $used_day->until }}<br>
												有給日数：{{ $used_day->used_days }} 日分
											</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
											<a type="button" class="btn btn-danger btn-sm" name="delete" href="/use_request/delete/{{ $used_day->id }}">削除実行</a>
										</div>
									</div>
								</div>
							</div>
							<!-- /deleteModalWindow -->
						</td>
						<?php $i += 1 ?>
					</tr>
					@endforeach

				</tbody>
			</table>

			@else
			<p>現在、登録済み有給はありません。</p>
			@endif

			{!! $used_days->render() !!}
		</div>
	</div>
</section>

@endsection

@section('js')
@include('elements.for_form')
@endsection