@extends('layouts/master')
@section('title', '営業日外出勤日 / 振替休日　一覧')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		振替休日　一覧
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">振替休日一覧</h3>
		</div>
		<div class="box-body">

			@if($substituteHolidays->count())
			<table class="table table-bordered">
				<thead class="bg-primary">
				<th width="5%">№</th><th>概要</th><th>営業日外出勤日</th><th>振替休日</th><th width="15%">操作</th>
				</thead>
				<tbody>

					<?php $i = ($substituteHolidays->currentPage() - 1) * \App\SubstituteHoliday::PAGE_NUM + 1 ?>
					{{-- dd(Auth::user()->usedDays) --}}
					@foreach ($substituteHolidays as $substituteHoliday)
					<tr>
						<td>{{ $i }}</td>
						<td>{{ $substituteHoliday->memo }}</td>
						<td>{{ App\User::getJaDate($substituteHoliday->workday) }}</td>
						<td>{{ App\User::getJaDate($substituteHoliday->holiday) }}</td>
						<td>
							<a type="button" class="btn btn-primary btn-sm" name="edit" href="{{ url('vacation/edit', $substituteHoliday->id) }}">編集</a>
							&ensp;
							<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $substituteHoliday->id }}">削除</button>

							<!-- deleteModalWindow -->
							<div class="modal fade" id="deleteModal{{ $substituteHoliday->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-red">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="ModalLabel">登録済有給の削除</h4>
										</div>
										<div class="modal-body">
											<p class="alert-danger align-center">&ensp;<i class="fa fa-warning"></i> この処理は取り消せません</p>
											<p>選択した登録済有給を削除します。よろしいですか？</p>
											<p>
												削除する有給：{{ App\User::getJaDate($substituteHoliday->from) }} 〜 {{ App\User::getJaDate($substituteHoliday->until) }}<br>
												有給日数：{{ $substituteHoliday->used_days }} 日分
											</p>
										</div>
										<div class="modal-footer">
											{!! Form::open(['method' => 'delete', 'url' => '/substitute_holiday']) !!}
											<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
											{!! Form::submit('削除実行', ['class' => 'btn btn-danger btn-sm']) !!}
											{!! Form::close() !!}
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

			{!! $substituteHolidays->render() !!}
		</div>
	</div>
</section>

@endsection
