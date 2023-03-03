<x-app-layout>
    <style>
        .code-font {
            font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
            font-size: 15px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Atividades') }}
        </h2>
    </x-slot>

    @foreach ($questions as $question)
    <div class="py-12" id="mainDiv{{$question->id}}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg question" id="question_id_{{$question->id}}">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-3 text-left">
                                <p><span class="badge bg-secondary" style="text-align:left; margin-left: -80px;">Q{{ $question->id }}</span></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <p>{{ 'Ponto' . ($question->valor > 1 ? 's' : '') . ': ' . $question->valor }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="container images-container">
                        <div class="row justify-content-sm-center">
                            <h6 class="text-center">Analise o diagrama abaixo e resolva a questão</h6>
                            @foreach ($question->images->array() as $image)
                            <img class="img-fluid img-container" style="width:100px;height:100px;margin:0 -12px 0 -12px" src="{{ asset('question_images/' . $image) }}" alt="{{$image}}">
                            @endforeach
                        </div>
                    </div>
                    <hr />
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-12 text-center text-uppercase">
                                <h6 style="font-weight: 800">{{ $question->pergunta }}</h6>
                            </div>
                            <div class="col-md-12 text-center text-uppercase">
                                <h6 style="font-weight: 500">colunas que devem ser enviadas:
                                    <span class="text-lowercase" style="font-style: italic">
                                        {{ $question->columns }}
                                    </span>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-12 text-center">
                                <div class="input-group">
                                    <span class="input-group-text" style="background-color: lightgray;"><code style="color: crimson; font-weight: bold">SQL Query</code></span>
                                    <textarea class="form-control query_input code-font" aria-label="SQL Query" id="query_input_id_{{$question->id}}" maxlength="15000"></textarea>
                                    <span class="input-group-text" id="showspan_{{$question->id}}"><button class="btn-result btn btn-dark" data-id="{{$question->id}}" data-toggle="modal" data-target="#myModal">Resultado</button></span>
                                    <span class="input-group-text"><button class="btn-execute btn btn-primary" data-id="{{$question->id}}">Executar</button></span>
                                    <span class="input-group-text"><button class="btn-send btn btn-success" data-id="{{$question->id}}">Enviar</button></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger" style="margin: 5px 25px -20px 25px" id="alert_{{$question->id}}" hidden></div>
                </div>
            </div>
        </div>
    </div>
        @endforeach
</x-app-layout>

<script>
    $(document).ready(function() {
        const popup = (title, body) => {
            w2popup.open({
                title: title,
                body: `<h4 class="text-center">${body}</h4>`
            });
        }

        const SQL_TYPES = {
            'VAR_STRING': 'string',
            'LONGLONG': 'integer',
            'TIMESTAMP': 'date',
            'NEWDECIMAL': 'float',
            'TIME': 'string',
            'DATE': 'date'
        }

        $('img').mouseenter(function() {
            $(this).animate({
                width: "250px",
                height: "220px"
            }, 0);
        });

        $('img').mouseleave(function() {
            $(this).animate({
                width: "100px",
                height: "100px"
            }, 0);
        });

        $('.query_input').on('change keyup keydown paste cut', function(e) {
            $(this).height(0).height(this.scrollHeight);
            e.stopPropagation();
        });

        $('#feedback').on('change keyup keydown paste cut', function(e) {
            $(this).height(150).height(this.scrollHeight);
            e.stopPropagation();
        });

        $('.btn-result').on('click', function(e) {
            e.stopPropagation();
            $('#modal').modal('toggle');
            setTimeout(f => w2ui.grid.refresh(), 500);
        });

        $('#sendFeedback').on('click', function(e) {
            e.stopPropagation();
            $('#modalFb').modal('toggle');
            $.ajax({
                type: "POST",
                url: "{{route('users.saveFeedback')}}",
                data: {
                    feedback: $('#feedback').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
                },
                beforeSend: (xhr) => {},
                success: (response) => {
                    if (!response.success) {
                        return;
                    }

                    popup('Feedback enviado', response.msg);
                    // alert(response.msg);

                    location.reload();
                    // renderNotaFinal(response.nota, response.media);
                },
                error: (xhr, status, error) => {
                    console.log(xhr, status, error);
                },
                complete: (xhr, status) => {},
                dataType: 'json',
                contentType: 'application/x-www-form-urlencoded',
                async: true
            });
        });

        $('.btn-execute').on('click', function(e) {
            e.stopPropagation();

            let btn = $(this);
            let id = btn.attr('data-id');

            buttonExecuteOrSend(id, "{{route('questions.runQuery')}}");
        });

        $('.btn-send').on('click', function(e) {
            e.stopPropagation();

            let btn = $(this);
            let id = btn.attr('data-id');

            buttonExecuteOrSend(id, "{{route('questions.saveQuery')}}", true);
        });

        const buttonExecuteOrSend = (id, url, send = false) => {
            let query = $(`#query_input_id_${id}`).val();

            if (!query.length) {
                return popup('Error', 'Sua query está vazia!');
                // return alert('Sua query está vazia!');
            }

            let data = {
                _token: $('meta[name="csrf-token" ]').attr('content'),
                question_id: id,
                query: query
            }

            doPostAjax(url, data, send);
        }

        const buttonResultAnimation = (id) => {
            $(`#showspan_${id}`)
                .css('background-color', 'rgb(0, 255, 0)')
                .animate({
                    opacity: 0.5
                }, 500)
                .animate({
                    opacity: 1
                }, 500);

            setTimeout(f => {
                $(`#showspan_${id}`).css('background-color', '#e9ecef')
            }, 500);
        }

        const alertMessageDisplay = (id, msg) => {
            if ($('.p-log').length) {
                $(`#alert_${id}`).empty();
            }

            $(`#alert_${id}`).prepend(`<p class="p-log">${msg}</p>`)
                .attr('hidden', false);

            setTimeout(() => {
                $(`#alert_${id}`).empty().attr('hidden', true);
            }, 5000);
        }

        function renderNotaFinal(nota, media) {
            //! fix this later
            const CONTENT = `
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-12 text-center text-uppercase">
                            <h3 style="font-weight: 800">SUA NOTA FOI: <span style="font-weight: 400; color: ${nota >= media ? 'green' : 'red'}">${nota}</span></h3>
                        </div>
                        <div class="col-md-12 text-center text-uppercase">
                            <h5 style="font-weight: 500">
                                A média é ${media} pontos.
                            </h5>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-12 text-center text-uppercase" id="gridContainer">
                            <div id="user" style="display: inline-block; width: 100%; height: 300px;">
                                <h4 class="text-uppercase text-center" style="margin-top: 50px; color: rgba(0, 44, 255, 0.5)">carregando respostas</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    `;

            setTimeout(f => {
                $('main').append(CONTENT);
            }, 500);
        }

        function getNotaAjax() {
            $.ajax({
                type: "POST",
                url: "{{route('users.getNota')}}",
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
                },
                beforeSend: (xhr) => {},
                success: (response) => {
                    if (!response.success) {
                        return;
                    }
                    renderNotaFinal(response.nota, response.media);
                },
                error: (xhr, status, error) => {
                    console.log(xhr, status, error);
                },
                complete: (xhr, status) => {},
                dataType: 'json',
                contentType: 'application/x-www-form-urlencoded',
                async: true
            });
        }

        function doPostAjax(url, data, send) {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
                },
                beforeSend: (xhr) => {},
                success: (response) => {
                    if (!response.success) {
                        return popup('Error', response.message);
                        // return alert(response.message);
                    }

                    let id = data.question_id;

                    if (!send) {
                        buttonResultAnimation(id);
                        generateDynamicColumns(response.data);
                        return;
                    }

                    $(`#question_id_${id}`).animate({
                        opacity: 0.1
                    }, 800, complete => {
                        // $(`#question_id_${id}`).remove();
                        $(`#mainDiv${id}`).remove();
                    });

                    setTimeout(f => {
                        if (!$('.question').length) {
                            renderModalFeedback();
                        }
                    }, 2000);
                },
                error: (xhr, status, error) => {
                    let id = data.question_id;

                    alertMessageDisplay(id, xhr.responseJSON.msg);
                },
                complete: (xhr, status) => {},
                dataType: 'json',
                contentType: 'application/x-www-form-urlencoded',
                async: true
            });
        }

        $('#grid').w2grid({
            name: 'grid',
            columns: [],
            records: []
        });

        function renderModalFeedback() {
            $('#modalFb').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        function generateDynamicColumns(data, excludeTimestamp = true) {
            let columns = [];
            data.columns.forEach((e, i) => {
                if (['created_at', 'updated_at'].includes(e.name) && excludeTimestamp) {
                    return;
                }
                columns.push({
                    field: e.name,
                    text: e.name,
                    type: SQL_TYPES[e.type],
                    recid: i,
                    id: i
                });
            });
            w2ui['grid'].columns = columns;
            w2ui['grid'].records = data.result;
            w2ui['grid'].refresh();
        }

        @if(!$gaveFeedback)
        if (!$('.question').length) {
            renderModalFeedback();
        }
        @elseif($gaveFeedback)
        getNotaAjax();

        let records = [];

        @foreach (Auth::user()->questions as $userQuestion)
            records.push({
                recid: '{{ $userQuestion->id }}',
                pergunta: `{!! $userQuestion->question->pergunta !!}`,
                resposta: `{!! $userQuestion->resposta !!}`,
                nota: `{!! $userQuestion->valor . ' / ' . $userQuestion->question->valor !!}`,
                resposta_correta: `{!! $userQuestion->question->resposta !!}`,
                error_msg: `{!! $userQuestion->error_msg !!}`,
                w2ui: {
                    style: "background-color: {{ $userQuestion->question->valor != $userQuestion->valor ? 'rgba(255, 0, 0, 0.2);' : 'rgba(0, 255, 0, 0.2);' }}",
                }
            });
        @endforeach

        setTimeout(f => {
            $('#user').w2grid({
                name: 'user',
                box: '#user',
                header: 'Questões e Respostas',
                show: { header: true, columnHeaders: true, footer: true, toolbar: true },
                columns: [
                    { field: 'recid', text: 'ID', size: '120px', hidden: true },
                    { field: 'pergunta', text: 'Pergunta', size: '45%' },
                    { field: 'resposta', text: 'Resposta', size: '45%' },
                    { field: 'nota', text: 'Nota', size: '10%', editable: { type: 'text' } },
                ],
                toolbar: {
                    items: [
                        { id: 'show-error-message', type: 'button', text: 'Mensagem de Erro', icon: 'w2ui-icon-cross', disabled: true },
                        { type: 'break' },
                        { id: 'show-correct-answer', type: 'button', text: 'Mostrar Resposta Correta', icon: 'w2ui-icon-check', disabled: true },
                        { type: 'break' },
                        { id: 'show-answer', type: 'button', text: 'Mostrar Resposta', icon: 'w2ui-icon-info', disabled: true },
                    ],
                    onClick: (target, data) => {
                        if (target == 'show-error-message') {
                            let id = w2ui.user.getSelection()[0];
                            let item = w2ui.user.get(id);
                            if (!item.error_msg.length) {
                                return;
                            }
                            return w2popup.open({
                                title: 'Mensagem de Erro ao Executar Query',
                                body: `
                                <div class="row justify-content-center text-center">
                                    <div class="col-12" style="margin-top:10px">
                                        ${item.error_msg}
                                    </div>
                                </div>`
                            });
                        }

                        if (target == 'show-correct-answer') {
                            let id = w2ui.user.getSelection()[0];
                            let item = w2ui.user.get(id);
                            return w2popup.open({
                                title: 'Resposta (Query) Correta',
                                body: `
                                <div class="row justify-content-center text-center">
                                    <div class="col-12" style="margin-top:10px">
                                        ${item.resposta_correta}
                                    </div>
                                </div>`
                            });
                        }

                        if (target = 'show-answer') {
                            let id = w2ui.user.getSelection()[0];
                            let item = w2ui.user.get(id);
                            return w2popup.open({
                                title: 'Sua Resposta (Query)',
                                body: `
                                <div class="row justify-content-center text-center">
                                    <div class="col-12" style="margin-top:10px">
                                        ${item.resposta}
                                    </div>
                                </div>`
                            });
                        }
                    },
                },
                records: records,
                onSelect: event => {
                    let item = w2ui.user.get(event.recid);

                    w2ui.user.toolbar.items.forEach( e => {
                        if (e.id == 'show-correct-answer') {
                            e.disabled = false;
                        }

                        if (e.id == 'show-error-message') {
                            if (item.error_msg.length > 0) {
                                e.disabled = false;
                            } else {
                                e.disabled = true;
                            }
                        }

                        if (e.id == 'show-answer') {
                            e.disabled = false;
                        }
                    });
                    w2ui.user.refresh();
                },
                onUnselect: event => {
                    w2ui.user.toolbar.items.forEach( e => {
                        if (e.id =='show-correct-answer') {
                            e.disabled = true;
                        }
                        if (e.id =='show-error-message') {
                            e.disabled = true;
                        }
                        if (e.id == 'show-answer') {
                            e.disabled = true;
                        }
                    });
                    w2ui.user.refresh();
                }
            });
        }, 2000);
        @endif
    });
</script>


<!-- Modals -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center justify-content-md-center">
                <h4 class="modal-title" id="myModalLabel">Resultado</h4>
            </div>
            <div class="modal-body">
                <div id="grid" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFb" tabindex="-1" role="dialog" aria-labelledby="myModalLabelFb">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center justify-content-md-center">
                <h4 class="modal-title" id="myModalLabelFb">Deixe seu Feedback</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-12 text-center">
                            <div class="input-group">
                                <textarea class="form-control code-font" style="min-height:150px" id="feedback" maxlength="5000"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <button class="btn btn-primary" id="sendFeedback">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
