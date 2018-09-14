<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TVProgramm extends Model
{
    protected $table = 'tv_programm_tab';
    protected $primaryKey = 'tv_programm_id';

    protected $appends = ['keydate'];

    public function getKeydateAttribute() {

    	//date('d.m.Y', strtotime(date('Y').'W'.date('W').$i));
    	return date('d.m.Y', strtotime($this->date));;
    }
}
