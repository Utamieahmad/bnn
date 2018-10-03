<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuditRekomendasiBidang extends Authenticatable
{
   

    use Notifiable;
    protected $table      = 'irtama_lha_rekomendasi';
    protected $primaryKey      = 'id_rekomendasi';
    protected $guarded    = ['id_rekomendasi'];

}
