<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    protected $primaryKey = 'id'; // default key name
    public $incrementing = true; // true for auto-increment
    protected $keyType = 'int';  // or 'string' if UUID or custom keys

    protected $table = "students" ;//table name
    protected $fillable = ["student","grade", "subject"]; // table columns
    protected $guarded = ["id"]; // attribute that is not mass assigning
    public $timestamps = false; // to revent from creating created_at or updated_at

}
