<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Cliente
 *
 * Representa um cliente da drogaria.
 *
 * @property int $id
 * @property int $codigo
 * @property string $nome
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Orcamento[] $orcamentos
 * @property-read int|null $orcamentos_count
 */
class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    /**
     * Evento de criação do cliente para gerar o código incremental único.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($cliente) {
            $ultimoCliente = self::orderByDesc('codigo')->first();
            if ($ultimoCliente) {
                $cliente->codigo = $ultimoCliente->codigo + 1;
            } else {
                $cliente->codigo = 1001;
            }
        });
    }

    /**
     * Relacionamento: Cliente possui muitos orçamentos.
     *
     * @return HasMany
     */
    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class);
    }
}
