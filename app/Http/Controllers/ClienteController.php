<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    //
    public function index(Request $request)
    {
        // $paginaActual = $request->query('page', 1);
        $elementoPorPagina = $request->cant;

        $cliente = Cliente::paginate($elementoPorPagina);

        return $cliente;
    }

    public function findByLike(Request $request)
    {
        $texto = $request->texto;
        // $paginaActual = $request->query('page', 1);
        $elementoPorPagina = $request->cant;

        $cliente = Cliente::where('name', 'like', '%' . $texto . '%')->paginate($elementoPorPagina);

        return $cliente;
    }

    public function combo()
    {
        $cliente = Cliente::all();

        return $cliente;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:clientes,email|max:150',
            'celular' => 'required|string|max:50',
        ], [
            'name.required' => 'El nombre del Cliente es obligatorio.',
            'name.string' => 'El nombre del Cliente debe contener letras y numeros.',
            'name.max' => 'El nombre del Cliente debes ser maximo de 255 letra y numeros.',
            'email.required' => 'El email del Cliente es obligatorio.',
            'email.string' => 'El Correo Electrónico del Cliente debe contener letras y numeros.',
            'email.unique' => 'El Correo Electrónico del Cliente ya existe, proporcione otro por favor.',
            'celular.required' => 'El número de Celular del Cliente es obligatorio.',
            'celular.string' => 'El número de Celular del Cliente debe contener numeros.',
            'celular.max' => 'El número de Celular del Cliente debes ser maximo de 50 nume',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 'estado' => false]);
        }

        $cliente = Cliente::create([
            'name' => $request->name,
            'email' => $request->email,
            'celular' => $request->celular,
        ]);

        return response()->json(['data' => $cliente, 'estado' => true]);
    }

    public function edit($id = '')
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return response()->json(['data' => $cliente]);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Cliente] ' . $id) {
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
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:150',
                'celular' => 'required|string|max:50',
            ], [
                'name.string' => 'El nombre del Cliente debe contener letras y numeros.',
                'name.max' => 'El nombre del Cliente debes ser maximo de 255 letra y numeros.',
                'email.string' => 'El Correo Electrónico del Cliente debe contener letras y numeros.',
                'celular.string' => 'El número de Celular del Cliente debe contener numeros.',
                'celular.max' => 'El número de Celular del Cliente debes ser maximo de 50 nume',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            Cliente::where('id', '=', $id)->update($request->all());

            $cliente = Cliente::findOrFail($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Cliente] ' . $id) {
                if ($id == '') {
                    $mesage = 'El registro a modificar no fue encontrado. Verifique los datos enviados.';
                } else {
                    $mesage = 'No se pudo modificar, SI el error persiste comuniquese con el Administrador del Sistema.';
                }
            }
            return response()->json(['message' => $mesage]);
        }
        return response()->json(['data' => $cliente]);
    }

    public function destroy($id)
    {
        $mesage = '';
        try {
            Cliente::findOrFail($id);
            Cliente::destroy($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Cliente] ' . $id) {
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
