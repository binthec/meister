@extends('layouts/master')
@section('title', 'ライセンス管理')

@section('content')
<section class="content-header">
	<h1>ライセンス管理</h1>
</section>

<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">デバイス{{ $license->id === null ? '新規登録': '編集' }}</h3>
		</div>
		<div class="box-body">

			@if($license->id === null) <!-- 新規作成 -->
			{!! Form::open(['url' => ['/license', $license->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			@else <!-- 編集 -->
			{!! Form::open(['url' => ['/license', $license->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
			@endif
			{{ csrf_field() }}

			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<label for="name" class="col-md-2 control-label">ライセンス名 <span class="text-danger">*</span></label>
				<div class="col-md-8">
					{!! Form::text('name', $license->name, ['class' => 'form-control', 'placeholder' => 'ライセンスを識別出来る名前']) !!}
					@if($errors->has('name'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('name') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group forComputer{{ $errors->has('maker_id') ? ' has-error' : '' }}">
				<label for="maker_id" class="col-md-2 control-label">メーカー名 <span class="text-danger">*</span></label>
				<div class="col-md-6">
					{!! Form::select('maker_id', App\Maker::getNames(), $license->maker_id,['class' => 'form-control']) !!}
					@if($errors->has('maker_id'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('maker_id') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('product_key') ? ' has-error' : '' }}">
				<label for="product_key" class="col-md-2 control-label">プロダクトキー</label>
				<div class="col-md-8">
					{!! Form::text('product_key', $license->product_key, ['class' => 'form-control', 'placeholder' => 'プロダクトキー']) !!}
					@if($errors->has('product_key'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('product_key') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
				<label for="number" class="col-md-2 control-label">ライセンス数 <span class="text-danger">*</span></label>
				<div class="col-md-2">
					{!! Form::text('number', $license->number, ['class' => 'form-control', 'placeholder' => '1']) !!}
					@if($errors->has('number'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('number') }}</strong>
					</span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('expired_on') ? ' has-error' : '' }}">
				<label for="expired_on" class="col-md-2 control-label">有効期限</label>
				<div class="col-md-4">
					{!! Form::text('expired_on', App\User::getJaDate($license->expired_on), ['class' => 'form-control use_datepicker']) !!}
					@if($errors->has('expired_on'))
					<span class="help-block">
						<strong class="text-danger">{{ $errors->first('expired_on') }}</strong>
					</span>
					@endif
				</div>
			</div>

			@if($license->id !== null)
			<div class="form-group">
				<label for="deleted" class="col-md-2 control-label">削除フラグ</label>
				<div class="col-md-8">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('deleted', 1, ($license->deleted_at !== null)? true : false) !!} 削除済み
						</label>
					</div>
				</div>
			</div>
			@endif

			<div class="box-footer">
				<button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
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