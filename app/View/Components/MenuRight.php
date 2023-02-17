<?php

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;

class MenuRight extends Component
{

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.menuRight', ['menu' => $this->menu()]);
    }

    private function menu()
    {
        $fileAddress = env('menuStepup', '');
        $fileContent = '{"right":[]}';
        if(@fopen($fileAddress, 'r')){
            $fileContent =  file_get_contents($fileAddress);
        }
        return json_decode($fileContent, true)['right'];
    }

}