<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function setTheme($theme)
    {
        session(['theme' => $theme]);
        return redirect()->back();
    }

    public function setMode($mode)
    {
        session(['mode' => $mode]);
        return redirect()->back();
    }

    public function setAccessibility($option)
    {
        session(['accessibility' => $option]);
        return redirect()->back();
    }
}
