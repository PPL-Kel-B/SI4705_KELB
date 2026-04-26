<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProfileLayout extends Component
{
    public $backUrl;

    public function __construct($backUrl = null)
    {
        $this->backUrl = $backUrl;
    }

    public function render(): View
    {
        return view('layouts.profile');
    }
}