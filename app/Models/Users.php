<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Auth;


class Users extends Model implements  AuthenticatableContract
{
    use Authenticatable;
    protected $table = 'user_tab';
    protected $primaryKey = 'user_id';

	protected $fillable = ['user_id', 'fio', 'email', 'username', 'quote', 'password', 'google_id', 'facebook_id', 'vkontakte_id'];

    protected $hidden = ['password'];

    // Added from Av.Almaty.tv
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id')->selectLocaled(App::getLocale());
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Users::class, 'subscriptions', 'subscriber_id',
            'subscribed_id')->orderBy('fio')->withTimestamps();
    }

    public function subscribers()
    {
        return $this->belongsToMany( Users::class, 'subscriptions', 'subscribed_id',
            'subscriber_id')->withTimestamps();
    }


    public function subscribed()
    {
        return $this->subscribers()->where('user_id', Auth::user()->user_id)->exists();
    }

    public function posts()
    {
        return $this->hasMany(Blog::class, 'author_id')->where(function ($query) {
                $query->where('is_active_ru', true)->orWhere('is_active_kz', true);
            });;
    }

    public function search()
    {
        return $this->subscriptions()->where('LOWER(fio)', 'saltanat');
    }
}
