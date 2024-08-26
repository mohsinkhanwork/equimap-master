<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DefaultLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Init layout file
        app(config('settings.KT_THEME_BOOTSTRAP.default'))->init();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        view()->composer('*', function( $view ){
            // user profile
            $user = auth()->user();
            $view->with('name', $user->name );
            $view->with('login', $user->login );

            if( !empty( $user->profile()->first() ) && $user->profile()->first()->profile_image !== null ){
                $view->with('profile_image', $user->profile()->first()->profile_image->url );
            }

            // language
            $locales  = config('app.available_locales' );
            if( $locales && count( $locales ) > 1 ){
                $view->with('current', app()->getLocale());
                $view->with('locales', $locales);
            }
        });

        return view(config('settings.KT_THEME_LAYOUT_DIR').'._default');
    }
}
