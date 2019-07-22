<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class time_table extends Model
{
    public $timestamps = false;
    public $table = 'time_table';
    protected $connection;

    public function __construct($value,array $attributes = array()){
        parent::__construct($attributes);
        $this->connection=$value;
    }
}
