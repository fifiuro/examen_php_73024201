<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Autor;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';
    protected $fillable = ['titulo', 'autor_id', 'lote', 'description'];

    public function autores()
    {
        return $this->hasMany(Autor::class, 'id', 'autor_id');
    }
}
