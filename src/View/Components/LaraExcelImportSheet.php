<?php

namespace DevPirate\LaraExcelCraft\View\Components;

use Illuminate\View\Component;

class LaraExcelImportSheet extends Component
{
    public $redirectTo = "";

    /**
     * @param $redirectTo
     */
    public function __construct($redirectTo)
    {
        $this->redirectTo = $redirectTo;
    }

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
