<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiError;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;

class StoreController extends Controller{

    private $store;

    public function __construct(Store $store){
        $this->store = $store;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $data = [
            'data' => $this->store->paginate(5)
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try{
            $storeData = $request->all();

            $newStore = $this->store->create($storeData);

            $data = [
                'data' => [
                    'message' => 'Loja cadastrada com sucesso!',
                    'store' => $newStore
                ]
            ];

            return response()->json($data, 201);
        } catch(Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            } 

            return response()->json(ApiError::errorMessage('Erro ao cadastrar uma nova loja!', 500), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $store = $this->store->find($id);

        if(!$store){
            return response()->json(ApiError::errorMessage('Nenhuma loja foi encontrada!', 404), 404);
        }

        $data = [
            'data' => $store
        ];

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $storeData = $request->all();

            $store = $this->store->find($id);

            $store->update($storeData);

            $data = ['data' => 
                [
                    'msg' => 'Loja atualizada com sucesso!',
                    'product' => $store
                ]
            ];

            return response()->json($data, 201);
        } catch(Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao atualizar a loja!', 1011), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
