<?php

namespace App\View\Components;

use Livewire\Component;

class AppLayout extends Component
{
    public $name = 'app-layout';
    public $view = 'layouts.app';
    public $title = 'app-layout';
    public $layout = 'layouts.app';
    public static $data = [];
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.app');
    }

     public function resolveView()
    {
        return view('layouts.app');
    }

    public static function resolve(): self
    {
        return new self();
    }

    public function shouldRender(): bool
    {
        return true;
    }

    public function withName($method) {
        return $method;
    }
}
