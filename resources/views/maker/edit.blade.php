@extends('layouts/master')
@section('title', 'メーカー管理')

@section('content')
<section class="content-header">
	<h1>メーカー管理</h1>
</section>

<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">メーカー{{ $maker->id === null ? '新規登録': '編集' }}</h3>
		</div>
		<div class="box-body">

			@if($maker->id === null) <!-- 新規作成 -->
			{!! Form::open(['url' => ['/maker', $maker->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			@else <!-- 編集 -->
			{!! Form::open(['url' => ['/maker', $maker->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
			@endif
			{{ csrf_field() }}

			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<label for="name" class="col-md-2 control-label">メーカー名 <span class="text-danger">*</span></label>
				<div class="col-md-8">
					{!! Form::text('name', $maker->name, ['class' => 'form-control', 'placeholder' => 'Adobe とか Symantec とか']) !!}
					@if($errors->has('name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('name') }}</strong>
					</span>
					@endif
				</div>
			</div>

			@if($maker->id !== null)
			<div class="form-group">
				<label for="deleted" class="col-md-2 control-label">削除フラグ</label>
				<div class="col-md-8">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('deleted', 1, ($maker->deleted_at !== null)? true : false) !!} 削除済み
						</label>
					</div>
				</div>
			</div>
			@endif

			<div class="box-footer">
				<button type="submit" class="btn btn-primary col-md-offset-2">
					{{ ($maker->id === null)? '追加実行': '編集実行' }}
				</button>
			</div>

			{!! Form::close() !!}

		</div>
	</div>
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
            orientation: "top left"
        });
    });

    changeForComputer();
    $("#category").change(function () {
        changeForComputer();
    });

    //ディスプレイの時はOSとコア、メモリは必要無いので隠すためのメソッド
    function changeForComputer() {
        if ($("#category option:selected").val() == '{{ App\Device::DISPLAY }}') {
            $(".forComputer").hide();
        } else {
            $(".forComputer").show();
        }
    }
</script>
@endsection