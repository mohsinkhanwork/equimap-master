<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AuthLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Init layout file
        app(config('settings.KT_THEME_BOOTSTRAP.auth'))->init();
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(){
        view()->composer('partials._auth_lang_switcher', function( $view ){
            $available  = config('app.available_locales' );
            if( $available && count( $available ) > 1 ){
                $view->with('current', app()->getLocale());
                $view->with('available', $available);
            }
        });

        return view(config('settings.KT_THEME_LAYOUT_DIR').'._auth');

    }
}
