<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Libro;

class Autor extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' ];

    public function libros()
    {
        return $this->belongsTo(Libro::class);
    }
}
