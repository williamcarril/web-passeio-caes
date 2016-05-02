<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passeio extends Model {

    public function cancelamento() {
        return $this->hasMany("\App\Models\Cancelamento", "idPasseio", "idPasseio");
    }

}
