<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'product_id', 'price', 'quantity', 'status'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }}
