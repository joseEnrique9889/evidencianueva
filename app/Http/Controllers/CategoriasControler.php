<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class CategoriasControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datos['categorias']=Categoria::paginate(5);

      return view('supervisor.categoria.index',$datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supervisor.categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // $datosCategorias=request()->all();
        $datosCategorias=request()->except('_token');
        //validamos que el campo imagen tenga algo

        if ($request->hasFile('imagen')) {
            $datosCategorias['imagen']=$request->file('imagen')->store('uploads/categoria','public');
        }
       Categoria::insert($datosCategorias);
        //return response()->json($datosCategorias);
        //return view('supervisor.categoria.index');
       return redirect('categoria');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria= Categoria::findOrFail($id);

       return view('supervisor.categoria.show',compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //devuelve todo el valor del id.
        $categoria= Categoria::findOrFail($id);

        return view('supervisor.categoria.edit',compact('categoria'));
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
        $datosCategorias=request()->except(['_token','_method']);

       if ($request->hasFile('imagen')) {

            $categoria= Categoria::findOrFail($id);

            Storage::delete('public/'.$categoria->imagen);

            $datosCategorias['imagen']=$request->file('imagen')->store('uploads/categoria','public');
        }

        Categoria::where('id','=',$id)->update($datosCategorias);

        $categoria= Categoria::findOrFail($id);

        return view('supervisor.categoria.edit',compact('categoria'));
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
        $categoria= Categoria::findOrFail($id);

       if(Storage::delete('public/'.$categoria->imagen)){

          Categoria::destroy($id);  
       }

        return redirect('categoria');
    }
}
