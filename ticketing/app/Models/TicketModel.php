<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// By convention your model name should be singular and
// your database table name should be plural.
// eg.  table name is - tickets
// mode name should be Ticket 
// it automatically detects the table name from the model name. and no need to specify the $table property to detect the table name.
// so if your model name is TicketModel it will assume the table name is ticket_models which is not correct in our case.
// by this we need to specify the $table property to tell laravel which table to use.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class TicketModel extends Model
{
    protected $table = 'tickets'; // table name in database
    protected $primaryKey = 'ticket_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'case_number',
        'case_title',
        'case_type',
        'petitioner_name',
        'respondent_name',
        'filing_date'
    ];
    protected $guarded = ['ticket_id'];
    
    public $timestamps = false;  // to prevent laravel from expecting created_at and updated_at columns in your table
    // in laravel by default it expects these two columns to be present in the table. If they are not present, you need to set this property to false.

    public function formatWordCase($word_value) {
        $word_value = preg_replace('/\s+/', ' ' , trim($word_value));
        return ucwords(strtolower($word_value)); 
    }

    public function setPetitionerNameAttribute($value){
        $this->attributes['petitioner_name'] = $this->formatWordCase(($value));
    }

    public function setRespondentNameAttribute($value){
        $this->attributes["respondent_name"]  = $this->formatWordCase(($value));
    }
}