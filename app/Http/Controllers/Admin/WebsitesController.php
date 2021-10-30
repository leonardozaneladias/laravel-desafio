<?php

namespace App\Http\Controllers\Admin;

use App\Models\Websites;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WebsiteStatusService;
use Illuminate\Support\Facades\Validator;

class WebsitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.websites.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        $errors = $validator->errors()->getMessages();

        if(count($errors)){

            return response([
                'success' => false,
                'message' => 'Erro nas validações',
                'errors' => $errors
            ], 422);

        }

        try{

            $dataCreate = $request->all();

            Websites::create($dataCreate);

            return response([
                'success' => true,
                'message' => 'Website cadastrado com sucesso!',
                'errors' => []
            ], 201);

        }catch(\Exception $e){

            return response([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 400);

        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return Websites::with('lastedStatus')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Websites  $websites
     * @return \Illuminate\Http\Response
     */
    public function show(Websites $website)
    {
        return view('admin.websites.show', compact('website'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Websites  $websites
     * @return \Illuminate\Http\Response
     */
    public function destroy(Websites $website)
    {
        $website->delete();
        return response('', 204);
    }

    public function processStatus()
    {
        try{

            $webs = Websites::all();
            foreach ($webs as $w){
                $response = WebsiteStatusService::getStatus($w->url);
                $w->status()->create([
                    'http_code' => $response['httpCode'], 
                    'body' => mb_convert_encoding($response['body'], 'UTF-8', 'ISO-8859-1'), 
                ]);
            }

            return response([
                'success' => true,
                'message' => 'Processamento realizado com sucesso',
                'errors' => []
            ], 200);

        }catch(\Exception $e){

            return response([
                'success' => false,
                'message' => 'Erro ao tentar processar status dos websites',
                'errors' => [$e->getMessage()]
            ], 409);

        }
        

    }
}
