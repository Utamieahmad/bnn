<?php

namespace App\Models\Irtama;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IrtamaLhaRekomendasi extends Authenticatable
{
    /* @author : Daniel Andi */

    use Notifiable;
    protected $table      = 'irtama_lha_rekomendasi';
    protected $guarded    = ['id_rekomendasi'];
    protected $primaryKey    = 'id_rekomendasi';

}
