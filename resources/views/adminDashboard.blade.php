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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                        <div class="row justify-content-md-center">
                            <h4 class="text-center">Usuários Cadastrados</h4>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="modal-body">
                                <div style="position: relative; height: 300px;">
                                    <div id="user" style="display: inline-block; width: 500px; height: 300px;"></div>
                                    <div id="question" style="display: inline-block; width: 624px; height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>

<script>
    var auxObj = {
        userQuestions: [],

        getQuestionsByUserId: (array = [], user_id) => {
            if (!array) {
                return;
            }

            if (!array.length) {
                return array;
            }

            return array.filter( user => user.user_id == user_id )
        },
    }

    $(document).ready(function() {
        $('#user').w2grid({
            name: 'user',
            box: '#user',
            header: 'Usuários',
            multiselect: true,
            show: { header: true, toolbar: true, footer: true },
            columns: [
                { field: 'recid', text: 'Record ID', type: 'int', hidden: true },
                { field: 'id', text: 'User ID', type: 'int', hidden: true },
                { field: 'nome', text: 'Nome', type: 'text', size: '30%' },
                { field: 'email', text: 'Email', type: 'text', size: '30%' },
                { field: 'nota', text: 'Nota', type: 'text', size: '15%' },
                { field: 'maximo', text: 'Máximo', type: 'float', size: '15%', hidden: true },
                { field: 'respondidas', text: 'Respondidas', type: 'int', size: '15%' },
            ],
            toolbar: {
                items: [
                    { id: 'show-feedback', type: 'button', text: 'Mostrar Feedback', icon: 'w2ui-icon-info' },
                    { type: 'break' },
                    { id: 'reset', type: 'button', text: 'Apagar Respostas', icon: 'w2ui-icon-cross' },
                ],
                onClick(event) {
                    event.preventDefault();
                    if (event.target == 'reset') {
                        let user = w2ui.user.get(w2ui.user.getSelection()[0]);
                        if (user.respondidas == 0) {
                            return;
                        }
                        w2confirm('Isso irá remover todas as questões respondidas e o feedback')
                            .no( r => { return })
                            .yes( r => { return resetUser(user.user_id) });
                    }
                    if (event.target == 'show-feedback') {
                        let user = w2ui.user.get(w2ui.user.getSelection()[0]);
                        if (!user.terminado) {
                            return;
                        }
                        w2popup.open({
                            title: `${user.nome} - Feedback`,
                            body: user.feedback
                        });
                    }
                }
            },
            records: [],
            onClick(event) {
                let record = this.get(event.recid);
                w2ui.question.clear();
                let questions = auxObj.getQuestionsByUserId(auxObj.userQuestions, record.user_id);
                let formatted = [];
                questions.forEach( item => {
                    formatted.push({
                        recid: item.recid,
                        name: `Q.${item.question_id} - Resposta`,
                        value: item.resposta,
                        w2ui: {
                            style: `background-color: ${item.valor == 0 ? 'rgba(255, 0, 0, 0.2)' : 'rgba(0, 255, 0, 0.2)' }`,
                            children: [
                                { recid: item.recid + 1, name: 'Pergunta', value: item.pergunta, w2ui: {
                                    children: [
                                        { recid: item.recid + 2, name: 'Resposta', value: item.resposta_correta, w2ui: {
                                            style: 'background-color: rgba(0, 0, 0, 0.1);'
                                        }}
                                    ],
                                    style: 'background-color: rgba(0, 0, 0, 0.1);'
                                    },
                                },
                            ],
                        },
                    });
                });
                w2ui.question.add(formatted);
            }
        });

        $('#question').w2grid({
            name: 'question',
            box: '#question',
            header: 'Questões e Respostas',
            show: { header: true, columnHeaders: false, footer: true },
            name: 'question',
            columns: [
                { field: 'name', text: 'Name', size: '120px', style: 'background-color: #efefef; border-bottom: 1px solid white; padding-right: 5px;', attr: "align=right" },
                { field: 'value', text: 'Value', size: '100%', editable: { type: 'text' }},
            ]
        });

        function generateDynamicColumns() {
            let columns = [];

            @foreach($users as $user)
            column = {
                recid: `{!! $user->id !!}`,
                nome: `{!! $user->name !!}`,
                email: `{!! $user->email !!}`,
                nota: parseFloat(`{!! $user->nota !!}`),
                maximo: 0,
                feedback: `
                    <div class="text-center" style="margin: 10px 10px 10px 10px">
                        <p>{!! $user->feedback !!}</p>
                    </div>`,
                terminado: parseInt(`{!! $user->feedback == '' ? 0 : 1 !!}`),
                user_id: `{!! $user->id!!}`,
                nota: parseFloat(`{!! $user->nota!!}`),
                error_msg: '',
                questoesResp: 0,
            };

            @if($user->totalQuestionsAnswered)
            @foreach($user->questions as $question)
            auxObj.userQuestions.push({
                user_id: `{!! $question->user_id !!}`,
                question_id: `{!! $question->question_id !!}`,
                recid: `{!! $question->question_id !!}`,
                resposta: `{!! $question->resposta !!}`,
                pergunta: `{!! $question->question->pergunta !!}`,
                resposta_correta: `{!! $question->question->resposta !!}`,
                valor: parseFloat(`{!! $question->valor !!}`),
                valor_original: parseFloat(`{!! $question->question->valor!!}`),
                error_msg: `{!! $question->error_msg !!}`,
            });
            @endforeach
            @endif

            column.questoesResp = `{!! $user->totalQuestionsAnswered !!}`
            column.w2ui = {
                style: 'background-color: rgba(255, 255, 0, 0.2);'
            };

            column.maximo = auxObj.getQuestionsByUserId(auxObj.userQuestions, `{!! $user->id !!}`).reduce( (ac, el) => ac += el.valor_original, 0);
            column.nota = column.nota + ' / ' + column.maximo;
            media = auxObj.getQuestionsByUserId(auxObj.userQuestions, `{!! $user->id !!}`).reduce( (ac, el) => ac += el.valor_original, 0) * 0.6;
            if (media == NaN) {
                media = 0;
            }

            if (column.questoesResp == 10) {
                if (column.nota > media) {
                    column.w2ui = {
                        style: 'background-color: rgba(0, 255, 0, 0.2);'
                    };
                } else {
                    column.w2ui = {
                        style: 'background-color: rgba(255, 0, 0, 0.2);'
                    };
                }
            }

            column.respondidas = auxObj.getQuestionsByUserId(auxObj.userQuestions, `{!! $user->id !!}`).length;
            columns.push(column);
            @endforeach

            w2ui['user'].records = columns;
            w2ui['user'].refresh();
            createChart(w2ui.user.records);
        }
        generateDynamicColumns();

        function resetUser(user_id) {
            $.ajax({
                url: "{{ route('questions.reset') }}",
                type: 'POST',
                data: { user_id: user_id },
                async: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
                },
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
                        return w2popup.open({
                            title: 'ERROR ON RESET',
                            body: `<h3 class="text-center">${response.message}</h3>`
                        });
                    }
                    location.reload();
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }

        function createChart(records) {
            let pizzaChartCanvas = document.getElementById('myChart');
            let finalizados = records.reduce( (a, e) => a += e.terminado, 0 );
            let naoFinalizados = records.reduce( (a, e) => a += (e.terminado ? 0 : 1), 0 );
            let pizzaChartData = {
            labels: ['Finalizados', 'A Fazer'],
            datasets: [{
                data: [finalizados, naoFinalizados],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                ]
            }]
            };

            let pizzaChartOptions = {
            plugins: {
                legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 20,
                    fontStyle: 'bold'
                }
                }
            }
            };

            let pizzaChart = new Chart(pizzaChartCanvas, {
                type: 'pie',
                data: pizzaChartData,
                options: pizzaChartOptions
            });
        }
    });
</script>
