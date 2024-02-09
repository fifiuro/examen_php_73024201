<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Prestamos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamosController extends Controller
{
    //
    public function index(Request $request)
    {
        // $autor = Prestamos::with('libros')->with('clientes')->get();
        $elementoPorPagina = $request->cant;

        $prestamo = Prestamos::with('libros')->with('clientes')->paginate($elementoPorPagina);

        return $prestamo;
    }

    public function findByLike(Request $request)
    {
        $texto = $request->texto;
        // $paginaActual = $request->query('page', 1);
        $elementoPorPagina = $request->cant;

        $prestamo = Prestamos::with('libros')->with('clientes')
            ->whereHas('clientes', function ($query) use ($texto) {
                $query->where('name', 'like', '%' . $texto . '%');
            })
            ->paginate($elementoPorPagina);

        return $prestamo;
    }

    public function prestamosVencidos(Request $request)
    {

        $elementoPorPagina = $request->cant;
        $prestamos = Prestamos::with('libros')->with('clientes')
            ->where('estado', '=', 'En Prestamo')->paginate($elementoPorPagina);
        return $prestamos;
    }

    public function findByLikeprestamosVencidos(Request $request)
    {
        $texto = $request->texto;
        $elementoPorPagina = $request->cant;
        $prestamos = Prestamos::with('libros')->with('clientes')->whereHas('clientes', function ($query) use ($texto) {
            $query->where('name', 'like', '%' . $texto . '%');
        })
            ->where('estado', '=', 'En Prestamo')->paginate($elementoPorPagina);
        return $prestamos;
    }

    public function segmentadosXmes(Request $request)
    {
        $prestamos = DB::table('prestamos')->select(DB::raw('MONTH(fecha_prestamo) as mes'), DB::raw('YEAR(fecha_prestamo) as anio'), DB::raw('COUNT(*) as cantidad_prestamos'))
            ->groupBy(DB::raw('MONTH(fecha_prestamo)'), DB::raw('YEAR(fecha_prestamo)'))
            ->orderBy(DB::raw('YEAR(fecha_prestamo)'), 'asc')
            ->orderBy(DB::raw('MONTH(fecha_prestamo)'), 'asc')
            ->get();
        return $prestamos;
    }

    public function segmentadosXmesXsemana(Request $request)
    {
        $prestamos = DB::table('prestamos')
            ->select(
                DB::raw('YEAR(fecha_prestamo) as anio'),
                DB::raw('MONTH(fecha_prestamo) as mes'),
                DB::raw('WEEK(fecha_prestamo) as semana'),
                DB::raw('COUNT(*) as cantidad_prestamos')
            )
            ->groupBy(DB::raw('YEAR(fecha_prestamo)'), DB::raw('MONTH(fecha_prestamo)'), DB::raw('WEEK(fecha_prestamo)'))
            ->orderBy(DB::raw('YEAR(fecha_prestamo)'), 'asc')
            ->orderBy(DB::raw('MONTH(fecha_prestamo)'), 'asc')
            ->orderBy(DB::raw('WEEK(fecha_prestamo)'), 'asc')
            ->get();
        return $prestamos;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libro_id' => 'required|integer',
            'cliente_id' => 'required|integer',
            'fecha_prestamo' => 'required|date',
            'dias_prestamo' => 'required|integer',
            'estado' => 'required|in:En Prestamo,Devuelto',
        ], [
            'libro_id.required' => 'Seleccione un Libro es obligatorio.',
            'libro_id.integer' => 'El Libro seleccionado no es correcto.',
            'cliente_id.required' => 'Seleccione un Cliente es obligatorio.',
            'cliente_id.integer' => 'El Cliente seleccionado no es correcto.',
            'fecha_prestamo.required' => 'La fecha de Prestamo es obligatorio.',
            'fecha_prestamo.date' => 'La fecha de Prestamo no es correcta.',
            'dias_prestamo.required' => 'Los Días de Prestamo es obligatorio.',
            'dias_prestamo.integer' => 'Los Dás de Prestamos debe ser un número.',
            'estado.required' => 'El Estado de Prestamo es obligatorio.',
            'estado.in' => 'El Estado de Prestamos debe ser una de estas opciones: En Prestamo, Devuelto.',

        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(), 'estado' => false]);
        }

        $prestamos = Prestamos::create([
            'libro_id' => $request->libro_id,
            'cliente_id' => $request->cliente_id,
            'fecha_prestamo' => $request->fecha_prestamo,
            'dias_prestamo' => $request->dias_prestamo,
            'estado' => $request->estado,
        ]);

        return response()->json(['data' => $prestamos, 'estado' => true]);
    }

    public function edit($id = '')
    {
        try {
            $prestamos = Prestamos::with('libros')->with('clientes')->findOrFail($id);
            return response()->json(['data' => $prestamos]);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Prestamos] ' . $id) {
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
                'libro_id' => 'integer',
                'cliente_id' => 'integer',
                'fecha_prestamo' => 'date',
                'dias_prestamo' => 'integer',
                'estado' => 'in:En Prestamo,Devuelto',
            ], [
                'libro_id.integer' => 'El Libro seleccionado no es correcto.',
                'cliente_id.integer' => 'El Cliente seleccionado no es correcto.',
                'fecha_prestamo.date' => 'La fecha de Prestamo no es correcta.',
                'dias_prestamo.integer' => 'Los Dás de Prestamos debe ser un número.',
                'estado.in' => 'El Estado de Prestamos debe ser una de estas opciones: En Prestamo, Devuelto.',

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            Prestamos::where('id', '=', $id)->update($request->all());

            $prestamos = Prestamos::with('libros')->with('clientes')->findOrFail($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Prestamos] ' . $id) {
                if ($id == '') {
                    $mesage = 'El registro a modificar no fue encontrado. Verifique los datos enviados.';
                } else {
                    $mesage = 'No se pudo modificar, SI el error persiste comuniquese con el Administrador del Sistema.';
                }
            }
            return response()->json(['message' => $mesage]);
        }
        return response()->json(['data' => $prestamos]);
    }

    public function destroy($id)
    {
        $mesage = '';
        try {
            Prestamos::findOrFail($id);
            Prestamos::destroy($id);
        } catch (Exception $exception) {
            if ($exception->getMessage() == 'No query results for model [App\Models\Prestamos] ' . $id) {
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
