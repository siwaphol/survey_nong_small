<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Electric_used_for_machine
 *
 * @property integer $id
 * @property integer $m_id
 * @property string $size
 * @property string $amount
 * @property string $life_time
 * @property string $work_hous
 * @property string $average_per_year
 * @property string $persentage
 * @property string $note
 * @property integer $main_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Main $mains
 * @property-read \App\Machine_and_main_tool $machine_and_main_tools
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereMId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereLifeTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereWorkHous($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereAveragePerYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine wherePersentage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereMainId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Electric_used_for_machine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Electric_used_for_machine extends Model
{
    public function mains()
    {
        return $this->belongsTo(Main::class);
    }


    public function machine_and_main_tools()
    {
        return $this->hasOne('App\Machine_and_main_tool','id','m_id');
    }
}
