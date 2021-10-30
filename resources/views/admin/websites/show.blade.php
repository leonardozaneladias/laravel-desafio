@extends('adminlte::page')

@section('title', 'Websites')

@section('content_header')
    <h1>Detalhes do Website</h1>
@stop

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-12" style="margin-bottom: 5px;">
                <a href="{{route('admin.website')}}" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><b>Url:</b></div>
                    <div class="card-body">
                         {{$website->url}}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><b>Último Status:</b> </div>
                    <div class="card-body" id="lastHttpCode">
                        @php
                        if($website->lastedStatus->http_code == 200){
                            $btnStatus = "<span style=\"font-size: 15px\" class=\"badge badge-success\">{$website->lastedStatus->http_code}</span>";
                        }else if($website->lastedStatus->http_code == 0){
                            $btnStatus = "<span style=\"font-size: 15px\" class=\"badge badge-danger\">Fora do Ar</span>";
                        }else{
                            $btnStatus = "<span style=\"font-size: 15px\" class=\"badge badge-warning\">{$website->lastedStatus->http_code}</span>";
                        }
                        @endphp
                        {!!$btnStatus!!}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><b>Última Atualização:</b> </div>
                    <div class="card-body" id="lastCreatedAt">
                         {{ \Carbon\Carbon::create($website->lastedStatus->created_at)->format('d/m/Y H:i:s')}}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><b>Corpo da resposta:</b></div>
                    <div class="card-body" style="height: 300px; overflow-y: auto;" id="lastBody">
                         {{$website->lastedStatus->body}}
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">Histórico da última hora</div>
                    <div class="card-body">
                        <table class="table table-striped" id="tableStatus">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Data e Hora</th>
                                    <th>Corpo da resposta</th>
                                </tr>
                            </thead>
                            <tbody>
                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="openBody">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Corpo da resposta</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="openModalBody" style="height: 500px; overflow: auto;">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

@stop

@section('css')

@stop

@section('js')
    
    <script src="/js/moment.min.js"></script>

    <script>

        let table;

        $(function () {
            table = $('#tableStatus').DataTable({
                ajax: {
                    url: '/admin/website/{{$website->id}}/status',
                    dataSrc: ''
                },
                scrollX: true,
                proceeding: false,
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
                },
                initComplete: function(data){
                    if(data.json.length)
                        updateData(data.json[0]);
                },
                aaSorting: [[1, "desc"]],
                columns: [
                    { data: "http_code", class: "text-center", render: function(data){
                        if(data == undefined){
                            return `<span style="font-size: 15px" class="badge badge-info">não processado</span>`;
                        }
                        if(data == 200){
                            return `<span style="font-size: 15px" class="badge badge-success">${data}</span>`;
                        }
                        if(data == 0){
                            return `<span style="font-size: 15px" class="badge badge-danger">Fora do Ar</span>`;
                        }
                        return `<span style="font-size: 15px" class="badge badge-warning">${data}</span>`;
                    }},
                    { data: "created_at", class: "text-center", render: function(data){
                        if(data){
                            return moment(data).format('DD/MM/YYYY H:mm:ss');
                        }
                        return '-';
                    } },
                    { data: "id", class: "text-center", render: function(data, type, table, set){
               
                        let btn = `<button class="btn btn-info" title="Excluir" onclick="openBody(${set.row})"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>`;
                        
                        return btn;
                    }, orderable: false},
                ]
            });

            setInterval(() => {
                table.ajax.reload(function(json){
                    if(json.length)
                        updateData(json[0]);
                });
            }, 60000);    
        
        });

        function updateData(data){

            let btnStatus = 0;

            if(data.http_code == undefined){
                btnStatus = `<span style="font-size: 15px" class="badge badge-info">não processado</span>`;
            }else if(data.http_code == 200){
                btnStatus = `<span style="font-size: 15px" class="badge badge-success">${data.http_code}</span>`;
            }else if(data.http_code == 0){
                btnStatus = `<span style="font-size: 15px" class="badge badge-danger">Fora do Ar</span>`;
            }else{
                btnStatus = `<span style="font-size: 15px" class="badge badge-warning">${data.http_code}</span>`;
            }

            document.getElementById('lastHttpCode').innerHTML = btnStatus;
            document.getElementById('lastCreatedAt').innerText = moment(data.created_at).format('DD/MM/YYYY H:mm:ss');
            document.getElementById('lastBody').innerText = data.body;
        }

        function openBody(row) {

            const body = table.row(row).data().body;
            $('#openModalBody').text(body);
            $('#openBody').modal('show');

        }


    </script>
@stop