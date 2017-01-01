@extends('layouts/master')
@section('title', 'デバイス一覧・検索')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		デバイス管理
	</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">検索</h3>
		</div>

		{!! Form::open(['url' => '/device', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
		<div class="box-body">

			<div class="form-group">
				<label for="category" class="col-md-2 control-label">デバイスの分類</label>
				<div class="col-md-3">
					{!! Form::select('category', App\Device::$deviceCategories, Request::input('category'),['class' => 'form-control', 'placeholder' => '---']) !!}
				</div>

				<label for="os" class="col-md-2 control-label">OS</label>
				<div class="col-md-3">
					{!! Form::select('os', App\Device::$osLabels, Request::input('os'),['class' => 'form-control', 'placeholder' => '---']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="name" class="col-md-2 control-label">機器名</label>
				<div class="col-md-3">
					{!! Form::text('name', Request::input('name'), ['class' => 'form-control', 'placeholder' => '機器名の like 検索']) !!}
				</div>

				<label for="bought_at" class="col-md-2 control-label">購入日</label>
				<div class="col-md-3 form-inline">
					{!! Form::text('after', Request::has('after')? Request::input('after'): '', ['class' => 'form-control use_datepicker']) !!}
					&ensp;〜&ensp;
					{!! Form::text('before', Request::has('before')? Request::input('before'): '', ['class' => 'form-control use_datepicker']) !!}
				</div>
			</div>

			<div class="form-group">
				<label for="user_id" class="col-md-2 control-label">使用者</label>
				<div class="col-md-3">
					{!! Form::text('user_name', Request::input('user_name'), ['class' => 'form-control', 'placeholder' => '使用者名の like 検索']) !!}
				</div>

				<label for="disposal" class="col-md-2 control-label">廃棄</label>
				<div class="col-md-3">
					<div class="checkbox">
						<label>
							{!! Form::checkbox('searchInactive', 1, Request::input('searchInactive') == 1? true: false) !!} 廃棄済みも含める
						</label>
					</div>
				</div>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<a href="{{ url('/device') }}" class="btn btn-warning">リセット</a>
			<button class="btn btn-primary pull-right" type="submit" name="search"> 検&emsp;索 </button>
		</div>
		{!! Form::close() !!}
	</div>



	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">デバイス一覧</h3>
			<span class="pull-right"><span class="font18 text-blue">{{ ($devices->count() > 0)? $devices->total() : '0' }}</span> 件</span>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			@if($devices->count() > 0)
			<table id="users" class="table table-bordered table-striped">
				<thead>
					<tr class="bg-blue">
						<th>分類</th>
						<th>OS</th>
						<th>機器名</th>
						<th>購入日</th>
						<th>使用者</th>
						<th>メモリ</th>
						<th>サイズ</th>
						<th>廃棄済</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>

					@foreach($devices as $device)
					<tr>
						<td><i class="fa {{ App\Device::$deviceIcon[$device->category] }}"></i> {{ App\Device::$deviceCategories[$device->category] }}</td>
						<td>{{ ($device->os) ? App\Device::$osLabels[$device->os]: '-' }}</td>
						<td>{{ $device->name }}</td>
						<td>{{ App\User::getJaDate($device->bought_at) }}</td>
						<td>{{ ($device->user_id) ? App\User::getUserName($device->user_id) :'なし' }}</td>
						<td>{{ ($device->memory)? $device->memory . ' GB' : '-' }}</td>
						<td>{{ $device->size }} インチ</td>
						<td class="text-red">{{ ($device->status == 99) ? '廃棄済' : '' }}</td>
						<td>
							<a href="{{ action('DeviceController@edit', $device->id) }}" class="btn btn-primary">編集</a>
							&emsp;
							<a href="#" class="btn btn-default bg-purple" data-toggle="modal" data-target="#detailModal{{ $device->id }}">詳細</a>

							<!-- deleteModalWindow -->
							<div class="modal fade" id="detailModal{{ $device->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header bg-blue text-center">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<i class="fa fa-4x {{ App\Device::$deviceIcon[$device->category] }}"></i>
											<h5>【 {{ $device->name }} 】</h5>
											<p>{{ ($device->os)? App\Device::$osLabels[$device->os] . ' / ' : '' }}{{ App\Device::$deviceCategories[$device->category] }}</p>
										</div>
										<div class="modal-body">
											<ul class="nav nav-stacked" id="deviceDetail">
												<li>
													<a href="#">
														<span class="left">{{ $device->user_id ? App\User::getUserName($device->user_id)  :'なし' }} </span>
														<span class="pull-right text-blue">使用者</span>
													</a>
												</li>
												<li>
													<a href="#">
														<span class="left">{{ App\User::getJaDate($device->bought_at) }} </span>
														<span class="pull-right text-blue">購入日</span>
													</a>
												</li>

												@if($device->category != App\Device::DISPLAY)
												<li>
													<a href="#">
														<span class="left">{{ $device->core }}コア </span>
														<span class="pull-right text-blue">コア数</span>
													</a>
												</li>
												<li>
													<a href="#">
														<span class="left">{{ $device->memory }} GB </span>
														<span class="pull-right text-blue">メモリ</span>
													</a>
												</li>
												<li>
													<a href="#">
														<span class="left">{{ $device->capacity }} GB </span>
														<span class="pull-right text-blue">ストレージ</span>
													</a>
												</li>
												@endif

												<li>
													<a href="#">
														<span class="left">{{ $device->size }} インチ </span>
														<span class="pull-right text-blue">サイズ</span>
													</a>
												</li>
												@if($device->status == 99)
												<div class="text-center font16 bg-red">廃棄済</div>
												@endif
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- /deleteModalWindow -->

						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@else
			該当するデバイスはありません
			@endif
		</div><!-- /.box-body -->

		@if($devices->count() > 0)
		<div class="box-footer text-center">
			{!! $devices->appends(Request::all())->render() !!}
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