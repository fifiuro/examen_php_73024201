<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    //
    public function index(Request $request)
    {
        $elementoPorPagina = $request->cant;

        $autor = Autor::paginate($elementoPorPagina);

        return $autor;
    }

    public function findByLike(Request $request)
    {
        $texto = $request->texto;
        $elementoPorPagina = $request->cant;

        $autor = Autor::where('name', 'like', '%' . $texto . '%')->paginate($elementoPorPagina);

        return $autor;
    }

    public function combo()
    {
        $autor = Autor::all();

        return $autor;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ], [
            'name.required' => 'El nombre del Autor es obligatorio.',
            'name.string' => 'El nombre del Autor debe contener letras y numeros.',
            'name.max' => 'El nombre del Autor debe ser maximo de 255 letras y numeros.',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 'estado' => false]);
        }

        $autor = Autor::create([
            'name' => $request->name,
        ]);

        return response()->json(['data' => $autor, 'estado' => true]);
    }

    public function edit($id = '')
    {
        try {
            $autor = Autor::findOrFail($id);
            return response()->json(['data' => $autor]);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Autor] ' . $id) {
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
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255'
            ], [
                'name.string' => 'El nombre del Autor debe contener letras y numeros.',
                'name.max' => 'El nombre del Autor debe ser maximo de 255 letras y numeros.',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            Autor::where('id', '=', $id)->update($request->all());

            $autor = Autor::findOrFail($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Autor] ' . $id) {
                if ($id == '') {
                    $mesage = 'El registro a modificar no fue encontrado. Verifique los datos enviados.';
                } else {
                    $mesage = 'No se pudo modificar, SI el error persiste comuniquese con el Administrador del Sistema.';
                }
            }
            return response()->json(['message' => $mesage]);
        }
        return response()->json(['data' => $autor]);
    }

    public function destroy($id)
    {
        $mesage = '';
        try {
            Autor::findOrFail($id);
            Autor::destroy($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Autor] ' . $id) {
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
