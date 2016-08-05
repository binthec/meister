@extends('layouts/master')

@section('content')
<div class="col-md-10">

	<div class="row">
		<div class="col-md-12">
			<div class="content-box-large">
				<div class="panel-heading">
					<legend>ユーザ情報編集</legend>
				</div>
				<div class="panel-body">
					<form action="" class="form-horizontal">
						{{-- CSRF対策--}}
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">ID</label>
							<div class="col-md-8 form-control-static">{{ $user->id }}</div>
						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">名前</label>
							<div class="col-md-8 form-inline">
								<p class="form_txt" id="base_date_text">{{ $user->family_name }} {{ $user->first_name }}</p>
							</div>
						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">E-Mailアドレス</label>
							<div class="col-md-6">
								<p class="form_txt" id="base_date_text">{{ $user->email }}</p>
							</div>
						</div>

						<hr>

						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">入社日</label>
							<div class="col-md-4">
								<p class="form_txt" id="base_date_text">{{ $user->date_of_entering }}</p>
							</div>
						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">起算日</label>
							<div class="col-md-6">
								<p class="form_txt" id="base_date_text">
									@if(!empty($user->base_date))
									{{ $user->base_date }}
									@else
									入社日を入力すると自動で算出されます。
									@endif
								</p>
							<!--<input type="date" class="form-control" name="base_date" id="base_date" value="{{ $user->base_date }}"></input>-->
							</div>
						</div>
						<div class="form-group">
							<label for="ID" class="col-md-2 control-label">備考</label>
							<div class="col-md-8">
								<p class="form_txt" id="base_date_text">{{ $user->memo }}</p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('js')
{{-- DatePickerと起算日計算のJS--}}
<script src="/js/calc_base_date.js"></script>
@endsection