<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    protected $primaryKey = 'id'; // default key name
    public $incrementing = true; // true for auto-increment
    protected $keyType = 'int';  // or 'string' if UUID or custom keys
    protected $table = "students" ;//table name
    protected $fillable = ["student","grade", "subject", "email"]; // table columns
    protected $guarded = ["id"]; // attribute that is not mass assigning
    public $timestamps = false; // to revent from creating created_at or updated_at


// word formatting when inputting data
    public static function formatWordCase($value) {

        $value = preg_replace('/\s+/', ' ', trim($value));
        return ucwords(strtolower($value));
    }
    public function setStudentAttribute($value){
        $this->attributes["student"] = self::formatWordCase($value);
        // $this->attributes["student"] = $this->formatWordCase($value);

    }
    public function setSubjectAttribute($value){
        $this->attributes["subject"] = self::formatWordCase($value);
    }
}
