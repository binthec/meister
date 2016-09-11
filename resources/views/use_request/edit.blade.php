@extends('layouts/master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		有給消化申請
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">申請内容の編集</h3>
		</div>
		<div class="box-body">

			{!! Form::open(['method' => 'post', 'url' => 'user/request_edit', 'class' => 'form-horizontal']) !!}
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


				<div class="col-md-3">
					<div class="panel panel-default align-center">
						<div class="panel-heading">合計有給残日数</div>
						<div class="panel-body font13">
							{{ Auth::user()->getSumRemainingDays() }}
						</div>
					</div>
				</div>
			</div>

			<hr>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">有給申請期間</label>
				<div class="col-md-3">
					<input type="date" class="form-control" placeholder="開始日" name="from" id="from" value=""></input>
					<div class="bfh-datepicker" data-format="y-m-d" data-date="today"></div>
				</div>
				<div class="col-md-1 form-control-static text-center">
					〜
				</div>
				<div class="col-md-3">
					<input type="date" class="form-control" placeholder="終了日" name="until" id="until" value=""></input>
				</div>
				<div class="col-md-3 form-control-static">
					<span id="sum_box">合計： <span id="sum"></span> 日間</span>
				</div>


			</div>
			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">連絡事項</label>
				<div class="col-md-8">
					<textarea class="form-control" name="memo" id="memo" value="">{{ $requested->memo }}</textarea>
				</div>
			</div>


			<input type="hidden" name="used_days" id="used_days" value=""></input>
			<input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}"></input>
			<input type="hidden" name="id" id="used_days" value="{{ $requested->id }}"></input>

		</div>

		<div class="box-footer">
			<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
		</div>

		{!! Form::close() !!}
	</div>




	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">申請済有給の一覧</h3>
		</div>
		<div class="box-body">

			<table class="table table-bordered">
				<thead class="well">
				<th width="5%">№</th><th>開始日</th><th>終了日</th><th>日数</th>
				</thead>
				<tbody>

					{{-- 通し番号を付ける --}}
					<?php $i = ($usedDays->currentPage() - 1) * 5 + 1 ?>
					{{-- dd(Auth::user()->usedDays) --}}
					@foreach ($usedDays as $usedDay)
					<tr>
						<td class="middle">{{ $i }}</td>
						<td>{{ $usedDay->from }}</td>
						<td>{{ $usedDay->until }}</td>
						<td>{{ $usedDay->used_days }}</td>
						<?php $i += 1 ?>
					</tr>
					@endforeach
				</tbody>
			</table>
			{!! $usedDays->render() !!}
		</div>
	</div>
</section>


@endsection




@section('js')
@include('elements.for_form')
@endsection