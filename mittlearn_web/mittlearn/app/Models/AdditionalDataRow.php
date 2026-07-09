<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalDataRow extends Model
{
    use HasFactory;
    protected $table = "additional_data_rows";
    protected $fillable = ['type', 'title', 'image', 'description', 'model_id', 'sort_order', 'url_redirection'];
    public function categoriesFront()
    {
        return $this->hasOne(Category::class, 'id', 'title');
    }
}
