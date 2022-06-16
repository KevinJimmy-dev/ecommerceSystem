<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiError;
use App\Api\ApiSuccess;
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
        $stores = $this->store
                       ->with('products')
                       ->paginate(10);

        return response()->json(ApiSuccess::successMessage('Lojas e seus respectivos produtos encontrados!', $stores), 200);
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

            return response()->json(ApiSuccess::successMessage('Loja cadastrada com sucesso!', $newStore), 201);
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
        $store = $this->store
                      ->with('products')
                      ->find($id)
                      ->paginate(10);

        if(!$store){
            return response()->json(ApiError::errorMessage('Nenhuma loja foi encontrada!', 404), 404);
        }

        return response()->json(ApiSuccess::successMessage('Loja encontrada!', $store), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $store = $this->store->find($id);

        if(!$store){
            return response()->json(ApiError::errorMessage('Nenhuma loja foi encontrada!', 404), 404);
        }

        try{
            $storeData = $request->all();

            $store = $this->store->find($id);

            $store->update($storeData);

            return response()->json(ApiSuccess::successMessage('Loja atualizada com sucesso!', $store), 201);
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $store = $this->store->find($id);

        if(!$store){
            return response()->json(ApiError::errorMessage('Nenhuma loja foi encontrada!', 404), 404);
        }

        try{
            $store->delete();

            return response()->json(ApiSuccess::successMessage('Loja excluida com sucesso!', $store), 200);
        } catch(Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao deletar a loja!', 1012), 500);
        }
    }
}
