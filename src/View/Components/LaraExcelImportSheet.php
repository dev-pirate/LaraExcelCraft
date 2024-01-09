<?php

namespace DevPirate\LaraExcelCraft\View\Components;

use Illuminate\View\Component;

class LaraExcelImportSheet extends Component
{
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('lara_excel_craft::components.import_index');
    }
}
