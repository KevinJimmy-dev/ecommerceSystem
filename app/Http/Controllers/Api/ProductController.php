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
    public function index(){
        $products = $this->product
                       ->with('store')
                       ->paginate(10);

        return response()->json(ApiSuccess::successMessage('Produtos e suas respectivas lojas encontradas!', $products), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try{
            $productData = $request->all();

            $newProduct = $this->product->create($productData);

            return response()->json(ApiSuccess::successMessage('Produto cadastrado com sucesso!', $newProduct), 201);
        } catch(Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 2010), 500);
            } 

            return response()->json(ApiError::errorMessage('Erro ao cadastrar um novo produto!', 500), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $product = $this->product
                      ->with('store')
                      ->find($id);

        if(!$product){
            return response()->json(ApiError::errorMessage('Nenhum produto foi encontrado!', 404), 404);
        }

        return response()->json(ApiSuccess::successMessage('Produto encontrado!', $product), 200);
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
        //
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
