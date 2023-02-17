<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;

class MenuLeft extends Component
{

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.menuLeft', ['menu' => $this->menu()]);
    }

    private function menu()
    {
        $fileAddress = env('menuStepup', '');
        $fileContent = '{"left":[]}';
        if(@fopen($fileAddress, 'r')){
            $fileContent =  file_get_contents($fileAddress);
        }
        return json_decode($fileContent, true)['left'];
    }

}