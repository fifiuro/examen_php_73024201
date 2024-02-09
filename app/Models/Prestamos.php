<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Libro;
use App\Models\Cliente;

class Prestamos extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = ['libro_id', 'cliente_id', 'fecha_prestamo', 'dias_prestamo', 'estado'];

    public function libros()
    {
        return $this->hasMany(Libro::class,'id','libro_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class,'id','cliente_id');
    }
}
