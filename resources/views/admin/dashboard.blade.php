@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div class="row">
    <div class="col-lg-6 col-12">

      <div class="small-box bg-info">
        <div class="inner">
          <h3 id="qtd"></h3>

          <p>Websites Cadastrados</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-12">
 
      <div class="small-box bg-success">
        <div class="inner">
          <h3 id="qtd_200"></h3>

          <p>Websites Status 200</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>
    
  </div>
@stop

@section('css')
@stop

@section('js')
    <script src="/js/axios.min.js"></script>
    <script>

        

        async function reload(){

            try{
                
                const res = await axios.get('/admin/home/reload');

                if(res.status == 200){
                    document.getElementById('qtd').innerHTML = res.data.qtd;
                    document.getElementById('qtd_200').innerHTML = res.data.qtd_200;
                }

            }catch(err){
                
                console.log(err);

                return false;

            }
        }

        setInterval(function(){
            reload();
        }, 60000)

        window.onload = function(){
            reload();
        }
    </script>
@stop