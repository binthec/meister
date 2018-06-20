@extends('layouts/master')
@section('title', '営業日外出勤日 / 振替休日 登録')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>振替休日 登録</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">新規登録</h3>
            </div>
            <div class="box-body">
                {!! Form::open(['method' => 'PUT', 'action' => ['SubstituteHolidayController@update', $substituteHoliday->id], 'class' => 'form-horizontal']) !!}
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">名前</label>
                    <div class="col-md-8 form-control-static">
                        <p>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }} さん</p>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('workday') ? ' has-error' : '' }}">
                    <label for="workday" class="col-md-2 control-label">営業日外出勤</label>
                    <div class="col-md-3">
                        {!! Form::text('workday', App\User::getJaDate($substituteHoliday->workday), ['class' => 'form-control datepicker font18', 'placeholder' => '日付を選択してください']) !!}
                        @if($errors->has('workday'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('workday') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('holiday') ? ' has-error' : '' }}">
                    <label for="holiday" class="col-md-2 control-label">振替休日</label>
                    <div class="col-md-3">
                        {!! Form::text('holiday', App\User::getJaDate($substituteHoliday->holiday), ['class' => 'form-control datepicker font18', 'placeholder' => '日付を選択してください']) !!}
                        @if($errors->has('holiday'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('holiday') }}</strong>
					</span>
                        @endif
                    </div>
                </div>


                <div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
                    <label for="memo" class="col-md-2 control-label">出勤理由</label>
                    <div class="col-md-8">
                        {!! Form::textarea('memo', $substituteHoliday->memo, ['class' => 'form-control', 'rows' => 5]) !!}
                        @if($errors->has('memo'))
                            <span class="help-block">
						<strong class="text-danger">{{ $errors->first('memo') }}</strong>
					</span>
                        @endif
                    </div>
                </div>

                {!! Form::hidden('user_id', Auth::user()->id) !!}

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
        //Date picker
        $(function () {
            $(".datepicker").datepicker({
                format: "yyyy年m月d日",
                autoclose: true,
            });
        });
    </script>
@endsection