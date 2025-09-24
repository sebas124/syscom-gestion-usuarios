<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class User extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'correo_electronico',
        'id_rol',
        'fecha_ingreso',
        'firma',
        'contrato',
        'fecha_eliminacion'
    ];

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    /** 
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo para calcular días hábiles (excluye sábados, domingos y festivos de Colombia)
     */
    public function daysWorked()
    {
        $start = Carbon::parse($this->fecha_ingreso)->startOfDay();
        $end = Carbon::now()->startOfDay();
        if ($start->gt($end)) return 0;

        // Agrupar festivos del año en un solo array
        $holidayDates = [];
        for ($y = $start->year; $y <= $end->year; $y++) {
            $response = Http::get("https://api-colombia.com/api/v1/holiday/year/{$y}");
            if ($response->ok()) {
                $items = $response->json();
                foreach ($items as $item) {
                    if (isset($item['date'])) $holidayDates[] = $item['date'];
                    elseif (isset($item['holiday_date'])) $holidayDates[] = $item['holiday_date'];
                }
            }
        }
        $holidayDates = array_unique($holidayDates);

        $days = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->isWeekend()) continue;
            if (in_array($d->toDateString(), $holidayDates)) continue;
            $days++;
        }
        return $days;
    }
}
