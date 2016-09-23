<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\Servico;
use App\Models\Eloquent\Enums\Periodo;
use App\Models\Eloquent\Enums\Frequencia;

class Modalidade extends \WGPC\Eloquent\Model {

    use Traits\Ativavel;

    protected $table = "modalidade";
    protected $primaryKey = "idModalidade";
    protected $fillable = [
        "nome",
        "descricao",
        "tipo",
        "periodo",
        "frequencia",
        "ativo",
        "coletivo",
        "precoPorCaoPorHora"
    ];
    protected $casts = [
        "ativo" => "boolean",
        "coletivo" => "boolean",
        "precoPorCaoPorHora" => "float"
    ];
    protected $attributes = [
        "ativo" => true,
        "coletivo" => false
    ];
    protected $appends = ["frequenciaNumericaPorSemana", "periodoNumericoPorMes"];
    protected static $rules = [
        "nome" => ["required", "string"],
        "descricao" => ["required", "string"],
        "tipo" => ["required", "string"],
        "periodo" => ["string"],
        "frequencia" => ["string"],
        "ativo" => ["required", "boolean"],
        "coletivo" => ["required", "boolean"],
        "precoPorCaoPorHora" => ["required", "numeric", "min:1"]
    ];

    public static function boot() {
        parent::boot();
        static::$rules["tipo"][] = "in:" . implode(",", Servico::getConstants());
        static::$rules["periodo"][] = "in:" . implode(",", Periodo::getConstants());
        static::$rules["frequencia"][] = "in:" . implode(",", Frequencia::getConstants());
    }

    public function overrideNormalRules($rules) {
        $rules["nome"][] = "unique:modalidade,nome,{$this->idModalidade},idModalidade";
        return $rules;
    }

    public function getTipoFormatadoAttribute() {
        return Servico::format($this->tipo);
    }

    public function getPeriodoFormatadoAttribute() {
        $formatado = Periodo::format($this->periodo);
        return $formatado ? $formatado : "Não aplicável";
    }

    public function getFrequenciaFormatadaAttribute() {
        $formatado = Frequencia::format($this->frequencia);
        return $formatado ? $formatado : "Não aplicável";
    }

    public function getPrecoPorCaoPorHoraFormatadoAttribute() {
        return "R$ " . number_format($this->precoPorCaoPorHora, 2, ",", ".");
    }

    public function getColetivoFormatadoAttribute() {
        return $this->coletivo ? "Sim" : "Não";
    }

    public function getFrequenciaNumericaPorSemanaAttribute() {
        switch ($this->frequencia) {
            case Frequencia::SEMANAL:
                return 1;
            case Frequencia::BISEMANAL:
                return 2;
        }
        return null;
    }
    
    public function getPeriodoNumericoPorMesAttribute() {
        switch($this->periodo) {
            case Periodo::MENSAL:
                return 1;
            case Periodo::BIMESTRAL:
                return 2;
            case Periodo::TRIMESTRAL:
                return 3;
            case Periodo::SEMESTRAL:
                return 6;
            case Periodo::ANUAL:
                return 12;
        }
        return null;
    }

}
