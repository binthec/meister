@extends('layouts/master')
@section('title', 'メーカー一覧・追加')

@section('content')

<section class="content-header">
	<h1>メーカー管理</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">メーカー一覧</h3>
			<span class="pull-right"><a href="{{ url('/maker/create') }}" class="btn btn-primary">追加</a></span>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			@if($makers->count() > 0)
			<table id="users" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th>メーカー名</th>
						<th>ステータス</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>

					@foreach($makers as $maker)
					<tr>
						<td>{{ $maker->name }}</td>
						<td class="{{ ($maker->deketed_at !== null)? 'text-red': '' }}">{{ ($maker->deketed_at !== null) ? '削除済' : 'アクティブ' }}</td>
						<td>
							<a href="{{ action('MakerController@edit', $maker->id) }}" class="btn btn-primary">編集</a>
							&emsp;
							<a href="{{ action('MakerController@destroy', $maker->id) }}" class="btn btn-danger">削除</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			メーカーはありません
			@endif
		</div><!-- /.box-body -->

		@if($makers->count() > 0)
		<div class="box-footer text-center">
			{!! $makers->render() !!}
		</div>
		@endif
	</div><!-- /.box -->

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
</script>
@endsection