@extends('layouts/master')
@section('title', 'Meister | デバイス管理')

@section('content')
    <section class="content-header">
        <h1>デバイス管理</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">デバイス{{ $device->id === null ? '新規登録': '編集' }}</h3>
            </div>
            <div class="box-body">

            @if($device->id === null) <!-- 新規作成 -->
                {!! Form::open(['url' => ['/device', $device->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            @else <!-- 編集 -->
                {!! Form::open(['url' => ['/device', $device->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
            @endif
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                    <label for="category" class="col-md-2 control-label">分類</label>
                    <div class="col-md-3">
                        {!! Form::select('category', App\Device::$deviceCategories, $device->category,['class' => 'form-control', 'id' => 'category']) !!}
                        @if($errors->has('category'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('category') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group forComputer{{ $errors->has('category') ? ' has-error' : '' }}">
                    <label for="os" class="col-md-2 control-label">OS</label>
                    <div class="col-md-3">
                        {!! Form::select('os', App\Device::$osLabels, $device->os,['class' => 'form-control']) !!}
                        @if($errors->has('os'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('os') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-2 control-label">機器名 <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        {!! Form::text('name', $device->name, ['class' => 'form-control', 'placeholder' => '任意の機器名。なんでもいい。']) !!}
                        @if($errors->has('name'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('name') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('serial_id') ? ' has-error' : '' }}">
                    <label for="serial_id" class="col-md-2 control-label">シリアルID <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        {!! Form::text('serial_id', $device->serial_id, ['class' => 'form-control', 'placeholder' => 'ユニークな値']) !!}
                        @if($errors->has('serial_id'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('serial_id') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('bought_at') ? ' has-error' : '' }}">
                    <label for="bought_at" class="col-md-2 control-label">購入日</label>
                    <div class="col-md-8">
                        {!! Form::text('bought_at', App\User::getJaDate($device->bought_at), ['class' => 'form-control use_datepicker']) !!}
                        @if($errors->has('bought_at'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('bought_at') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label for="price" class="col-md-2 control-label">購入金額</label>
                    <div class="col-md-8">
                        {!! Form::text('price', $device->price, ['class' => 'form-control']) !!}
                        @if($errors->has('price'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('price') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                    <label for="user_id" class="col-md-2 control-label">使用者</label>
                    <div class="col-md-8">
                        {!! Form::select('user_id', App\User::getFullNames(), ($device->user_id)? $device->user_id: '', ['class' => 'form-control', 'placeholder' => 'なし', 'id' => 'user_id']) !!}
                        @if($errors->has('user_id'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('user_id') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('rented_at') ? ' has-error' : '' }}">
                    <label for="rented_at" class="col-md-2 control-label">貸出日</label>
                    <div class="col-md-8">
                        {!! Form::text('rented_at', App\User::getJaDate($device->rented_at), ['class' => 'form-control use_datepicker']) !!}
                        @if($errors->has('rented_at'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('rented_at') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group forComputer{{ $errors->has('condition') ? ' has-error' : '' }}">
                    <label for="condition" class="col-md-2 control-label">状態</label>
                    <div class="col-md-3">
                        {!! Form::select('condition', App\Device::$conditionLabels, $device->condition,['class' => 'form-control', 'placeholder' => '---']) !!}
                        @if($errors->has('condition'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('condition') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('rental_number') ? ' has-error' : '' }}">
                    <label for="rental_number" class="col-md-2 control-label">モア貸出番号</label>
                    <div class="col-md-8">
                        {!! Form::text('rental_number', $device->rental_number, ['class' => 'form-control']) !!}
                        @if($errors->has('rental_number'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('rental_number') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                @if($device->id !== null)
                    <div class="form-group">
                        <label for="disposal" class="col-md-2 control-label">廃棄フラグ</label>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('disposal', 1, ($device->status == 99)? true : false) !!} 廃棄済み
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
                    <label for="memo" class="col-md-2 control-label">備考</label>
                    <div class="col-md-8">
                        {!! Form::textarea('memo', $device->memo, ['class' => 'form-control', 'rows' => 5]) !!}
                        @if($errors->has('memo'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('memo') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <hr>
                <h4><i class="fa fa-wrench"></i> スペック</h4>

                <div class="form-group forComputer{{ $errors->has('core') ? ' has-error' : '' }}">
                    <label for="core" class="col-md-2 control-label">コア数</label>
                    <div class="col-md-2">
                        {!! Form::text('core', $device->core, ['class' => 'form-control']) !!}
                        @if($errors->has('core'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('core') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group forComputer{{ $errors->has('memory') ? ' has-error' : '' }}">
                    <label for="memory" class="col-md-2 control-label">メモリ</label>
                    <div class="col-md-8 form-inline">
                        {!! Form::text('memory', $device->memory, ['class' => 'form-control']) !!}
                        <span class="form-control-static"> GB</span>
                        @if($errors->has('memory'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('memory') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group forComputer{{ $errors->has('capacity') ? ' has-error' : '' }}">
                    <label for="capacity" class="col-md-2 control-label">ストレージ容量</label>
                    <div class="col-md-8 form-inline">
                        {!! Form::text('capacity', $device->capacity, ['class' => 'form-control']) !!}
                        <span class="form-control-static"> GB</span>
                        @if($errors->has('capacity'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('capacity') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('size') ? ' has-error' : '' }}">
                    <label for="size" class="col-md-2 control-label">サイズ</label>
                    <div class="col-md-8  form-inline">
                        {!! Form::text('size', $device->size, ['class' => 'form-control']) !!}
                        <span class="form-control-static"> インチ</span>
                        @if($errors->has('size'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('size') }}</strong>
					</span>
                        @endif
                    </div>
                </div>


                <div class="box-footer">
                    <button type="submit" class="btn btn-primary col-md-offset-2">決定</button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </section>

@endsection

@section('js')
    <link href="/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <script src="/plugins/select2/js/select2.min.js"></script>

    @include('elements.for_form')
    <script>
        $(function () {
            $(".use_datepicker").datepicker({
                language: "ja",
                format: "yyyy年m月d日",
                autoclose: true,
                orientation: "top left"
            });

            //select2を使ってセレクトボックスを検索可能に
            var data = [];
            $("#user_id").select2({
               data: data
            });
        });

        changeForComputer();
        $("#category").change(function () {
            changeForComputer();
        });

        //ディスプレイの時はOSとコア、メモリは必要無いので隠すためのメソッド
        function changeForComputer() {
            if ($("#category option:selected").val() == '{{ App\Device::DISPLAY }}' || $("#category option:selected").val() == '{{ App\Device::OTHER }}') {
                $(".forComputer").hide();
            } else {
                $(".forComputer").show();
            }
        }
    </script>
@endsection
