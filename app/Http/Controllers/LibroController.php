<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    //
    public function index(Request $request)
    {
        $elementoPorPagina = $request->cant;
        $libros = Libro::with('autores')->paginate($elementoPorPagina);

        return $libros;
    }

    public function findByLike(Request $request)
    {
        $texto = $request->texto;
        $elementoPorPagina = $request->cant;
        $libros = Libro::where('titulo', 'like', '%' . $texto . '%')->paginate($elementoPorPagina);

        return $libros;
    }

    public function combo()
    {
        $libros = Libro::all();

        return $libros;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|integer',
            'lote' => 'required|string|max:15',
            'description' => 'required|string|max:500',
        ], [
            'titulo.required' => 'El Titulo del Libro es obligatorio.',
            'titulo.string' => 'El Titulo del Libro debe contener letras y numeros.',
            'titulo.max' => 'El Titulo del Libro debe ser maximo 255 letras y numeros.',
            'autor_id.required' => 'El Autor del Libro es obligatorio.',
            'autor_id.integer' => 'Seleccione un Autor valido.',
            'lote.required' => 'El Lote del Libro es obligatorio.',
            'lote.string' => 'El Lote del Libro debe contener numeros.',
            'lote.max' => 'El Lote del Libro debe ser maximo 15 numeros.',
            'lodescriptionte.required' => 'El Lote del Libro es obligatorio.',
            'description.string' => 'El Lote del Libro debe contener numeros.',
            'description.max' => 'El Lote del Libro debe ser maximo 15 numeros.',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 'estado' => false]);
        }

        $libro = Libro::create([
            'titulo' => $request->titulo,
            'autor_id' => $request->autor_id,
            'lote' => $request->lote,
            'description' => $request->description,
        ]);

        return response()->json(['data' => $libro, 'estado' => true]);
    }

    public function edit($id = '')
    {
        try {
            $libro = Libro::with('autores')->findOrFail($id);
            return response()->json(['data' => $libro]);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Libro] ' . $id) {
                if ($id == '') {
                    $mesage = 'Introduzca un valor valido de ID a buscar.';
                } else {
                    $mesage = 'No se encontro resultado con el ID: ' . $id;
                }
            }
            return response()->json(['message' => $mesage]);
        }
    }

    public function update(Request $request, $id)
    {
        $mesage = '';
        try {
            Libro::where('id', '=', $id)->update($request->all());

            $libro = Libro::with('autores')->findOrFail($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Libro] ' . $id) {
                if ($id == '') {
                    $mesage = 'El registro a modificar no fue encontrado. Verifique los datos enviados.';
                } else {
                    $mesage = 'No se pudo modificar, SI el error persiste comuniquese con el Administrador del Sistema.';
                }
            }
            return response()->json(['message' => $mesage]);
        }
        return response()->json(['data' => $libro]);
    }

    public function destroy($id)
    {
        $mesage = '';
        try {
            Libro::findOrFail($id);
            Libro::destroy($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Libro] ' . $id) {
                if ($id == '') {
                    $mesage = 'El registro a eliminar no fue encontrado. Verifique los datos enviados.';
                } else {
                    $mesage = 'No se pudo eliminar, SI el error persiste comuniquese con el Administrador del Sistema.';
                }
            }
            return response()->json(['message' => $mesage]);
        }
        return response()->json(['data' => null, 'eliminado' => 'true']);
    }
}
