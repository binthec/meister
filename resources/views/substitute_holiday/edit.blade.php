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

                {!! Form::open(['url' => '/substitute_holiday', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
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
                        {!! Form::text('workday', '', ['class' => 'form-control datepicker font18', 'placeholder' => '日付を選択してください']) !!}
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
                        {!! Form::text('holiday', '', ['class' => 'form-control datepicker font18', 'placeholder' => '日付を選択してください']) !!}
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
                        {!! Form::textarea('memo', '', ['class' => 'form-control', 'rows' => 5]) !!}
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

        <div class="box box-primary">
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
                                    <a type="button" class="btn btn-primary btn-sm" name="edit" href="{{ url('substitute_holiday/edit', $substituteHoliday->id) }}">編集</a>
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
                                                    <p>選択した振替休日を削除します。よろしいですか？</p>
                                                    <p>
                                                        営業外日出勤日：{{ App\User::getJaDate($substituteHoliday->workday) }}<br>
                                                        振替休日：{{ App\User::getJaDate($substituteHoliday->holiday) }}<br>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    {!! Form::open(['method' => 'delete', 'action' => ['SubstituteHolidayController@destroy', $substituteHoliday->id]]) !!}
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
                    <p>現在、振替休日はありません。</p>
                @endif

                {!! $substituteHolidays->render() !!}
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