@extends('adminlte::page')

@section('title', 'Websites')

@section('content_header')
    <h1>Websites Cadastrados</h1>
@stop

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-12 text-right">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addNewWebsite">Cadastrar novo Website</button>
                
                
                <button class="btn btn-primary" type="button" id="btnRefresh">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                    <span id="btnRefreshText">Processar agora</span>
                </button>
                <button type="button" class="btn btn-default" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="O processamento é realizado automaticamente a cada 1 minuto"><i class="fas fa-info-circle"></i></button>
                {{-- <button type="button" class="btn btn-success" id="btnRefresh" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Atualizando">Atualizar</button> --}}
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" width="100%" id="tableWebsites">
                            <thead>
                                <tr>
                                    <th>#Id</th>
                                    <th>Url</th>
                                    <th>Último Status</th>
                                    <th>Última Atualização</th>
                                    <th></th>
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

    <div class="modal" tabindex="-1" role="dialog" id="addNewWebsite">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cadastrar novo website</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label>URL </small></label>
                  <input type="text" class="form-control" name="formUrl" id="formUrl" placeholder="https://website.com.br">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="addNewWebsite()">Salvar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    
@stop

@section('css')
      
@stop

@section('js')
    <script src="/js/axios.min.js"></script>
    <script src="/js/moment.min.js"></script>
    
    <script>


        $(function () {

            table = $('#tableWebsites').DataTable({
                ajax: {
                    url: '/admin/websites',
                    dataSrc: ''
                },
                scrollX: true,
                proceeding: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
                },
                responsive: true,
                autoWidth: false,
                columns: [
                    { data: "id" },
                    { data: "url" },
                    { data: "lasted_status.http_code", class: "text-center", render: function(data){
                        if(data == undefined){
                            return `<span style="font-size: 15px;" class="badge badge-info">Aguardando processamento</span>`;
                        }
                        if(data == 200){
                            return `<span style="font-size: 15px;" class="badge badge-success">${data}</span>`;
                        }
                        if(data == 0){
                            return `<span style="font-size: 15px;" class="badge badge-danger">Fora do Ar</span>`;
                        }
                        return `<span style="font-size: 15px;" class="badge badge-warning">${data}</span>`;
                    }},
                    { data: "lasted_status.created_at", class: "text-center", render: function(data){
                        if(data){
                            return moment(data).format('DD/MM/YYYY H:mm:ss');
                        }
                        return '-';
                    } },
                    { data: "id", class: "text-center", render: function(data, type, row){
                    
                        const active = (row.lasted_status != null) ?  true : false;

                        let btn = `<button class="btn btn-danger" title="Excluir" onclick="removeWebsites(${data})"><i class="fa fa-trash" aria-hidden="true"></i></button>`;
                        if(active){
                            btn+= ` <a href="/admin/website/${data}" class="btn btn-info" title="Detalhes"><i class="fa fa-info-circle" aria-hidden="true"></i></a>`;
                        }else{
                            btn+= ` <button disabled class="btn btn-info" title="Detalhes"><i class="fa fa-info-circle" aria-hidden="true"></i></button>`;
                        }

                        return btn;

                    }, orderable: false},
                ]
            });

            setInterval(() => {
                table.ajax.reload();
            }, 60000);
            
            $("#btnRefresh").on("click", async function() {

                let $this = (this);
                $($this).attr('disabled', true);
                $($this).find('span.spinner-border').show();
                $($this).find('span#btnRefreshText').text('Processando');

                try{
                
                    const res = await axios.get('/admin/websites/process');

                    if(res.data.success){
                        table.ajax.reload(function ( json ) {
                            setTimeout(function (){
                                $($this).attr('disabled', false);
                                $($this).find('span.spinner-border').hide();
                                $($this).find('span#btnRefreshText').text('Processar agora');
                            }, 500);
                            
                        });
                        return true;
                    }
                    throw new Error();

                }catch(err){
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao tentar realizar o processamento'
                    });

                    $($this).attr('disabled', false);
                    $($this).find('span.spinner-border').hide();
                    $($this).find('span#btnRefreshText').text('Processar agora');
                }

                
            });

            $('[data-toggle="popover"]').popover(); 
            
        });

        async function addNewWebsite(){

            const formUrl = document.getElementById('formUrl');
            const jsonPost = {
                url: formUrl.value
            }

            try{
                
                const res = await axios.post('/admin/website', jsonPost, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if(res.data.success){

                    Swal.fire({
                        icon: 'success',
                        title: res.data.message
                    });

                    formUrl.value = '';
                    table.ajax.reload();
                    $("#addNewWebsite").modal('hide');

                }

            }catch(err){
                
                if(!err.response.success){

                    let arrErrors = '';
                    for(let error of Object.keys(err.response.data.errors)){
                        arrErrors+= err.response.data.errors[error] + '<br>';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: err.response.data.message,
                        html: arrErrors
                    })
                }

                return false;

            }
            
        }

        async function removeWebsites(id){

            Swal.fire({
                title: 'Você confirma que deseja excluir este Website?',
                showCancelButton: true,
                confirmButtonText: 'Confirmo',
                denyButtonText: `Cancelar`,
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    
                    try{

                        const res = await axios.delete(`/admin/website/${id}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if(res.status == 204){

                            Swal.fire({
                                icon: 'success',
                                title: 'Website removido com sucesso'
                            });

                            table.ajax.reload();
                            
                            return true;
                        }

                    }catch(err){

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao tentar excluir este website'
                        })

                    }

                 

                }
            })
        }


    </script>
@stop