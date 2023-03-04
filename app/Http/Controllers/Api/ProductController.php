<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiError;
use App\Api\ApiSuccess;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller{

    private $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product->with('store')
            ->paginate(10)
        ;

        return response()->json(
            ApiSuccess::successMessage('Produtos e suas respectivas lojas encontradas!', $products), 
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required',   
            'description' => 'nullable',
            'price' => 'required',
            'stock' => 'required',
        ]);

        try{
            $newProduct = $this->product->create($request->all());

            return response()->json(
                ApiSuccess::successMessage('Produto cadastrado com sucesso!', $newProduct),
                 201
            );
        } catch(Exception $e){
            if(config('app.debug')){
                return response()->json(
                    ApiError::errorMessage($e->getMessage(), 2010),
                     500
                );
            } 

            return response()->json(
                ApiError::errorMessage('Erro ao cadastrar um novo produto!', 500),
                 500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->with('store')
            ->find($id)
        ;

        if (!$product) {
            return response()->json(
                ApiError::errorMessage('Nenhum produto foi encontrado!', 404),
                 404
            );
        }

        return response()->json(
            ApiSuccess::successMessage('Produto encontrado!', $product),
             200
        );
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
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required',   
            'description' => 'nullable',
            'price' => 'required',
            'stock' => 'required',
        ]);

        $product = $this->product->find($id);

        if (!$product) {
            return response()->json(
                ApiError::errorMessage('Nenhum produto foi encontrado!', 404),
                 404
            );
        }

        try{
            $product->update($request->all());

            return response()->json(
                ApiSuccess::successMessage('Produto atualizado com sucesso!', $product),
                 201
            );
        } catch(Exception $e){
            if (config('app.debug')) {
                return response()->json(
                    ApiError::errorMessage($e->getMessage(), 1011),
                     500
                );
            }

            return response()->json(
                ApiError::errorMessage('Erro ao atualizar o produto!', 2011),
                 500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $product = $this->product->find($id);

        if(!$product){
            return response()->json(ApiError::errorMessage('Nenhum produto foi encontrado!', 404), 404);
        }

        try{
            $product->delete();

            return response()->json(ApiSuccess::successMessage('Produto excluido com sucesso!', $product), 200);
        } catch(Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 2012), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao deletar o produto!', 2012), 500);
        }
    }
}
