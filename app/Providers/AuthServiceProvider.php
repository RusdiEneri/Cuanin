use Illuminate\Support\Facades\Gate;

public function boot()
{
    Gate::define('seller-access', function ($user) {
        return $user->is_seller || $user->role === 'seller';
    });

    Gate::define('admin-access', function ($user) {
        return $user->role === 'admin';
    });
}