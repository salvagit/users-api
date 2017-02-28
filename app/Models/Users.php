<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @property string name
 * @property string email
 * @property string image
 */
class User extends Model
{
  protected $guarded = ['id'];
}
