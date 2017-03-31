@extends('layouts/master')
@section('title', '入社日変更')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		ユーザ管理
		<small>Control panel</small>
	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">入社日編集</h3>
		</div>
		<div class="box-body">
			{!! Form::open(['method' => 'put', 'action' => ['UserController@dateUpdate', $user->id], 'class' => 'form-horizontal']) !!}

			{{ csrf_field() }}

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">入社日 <span class="text-danger font16">*</span></label>
				<div class="col-md-4">
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						{!! Form::text('date_of_entering', App\User::getJaDate($user->date_of_entering), ['class' => 'form-control pull-right use_datepicker', 'id' => 'date_of_entering']) !!}
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="ID" class="col-md-2 control-label">起算日</label>
				<div class="col-md-6">
					<p class="form-control-static font18" id="base_date_text">
						@if(!empty($user->base_date))
						{{ App\User::getJaDate($user->base_date) }}
						@else
						入社日を入力すると自動で算出されます。
						@endif
					</p>
				</div>
			</div>

			{!! Form::hidden('base_date', App\User::getJaDate($user->base_date),['class' => 'form-control', 'id' => 'base_date']) !!}
			{!! Form::hidden('user_id', $user->id,['class' => 'form-control']) !!}

		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
		</div>

	</div><!-- /.box -->
	{!! Form::close() !!}

</section>
@endsection


@section('js')
@include('elements.for_form')

<script>
    $(function () {
        $(".use_datepicker").datepicker({
            language: "ja",
            format: "yyyy年m月d日",
            autoclose: true,
            orientation: "top right"
        });
    });
</script>

{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection