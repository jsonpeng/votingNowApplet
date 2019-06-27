<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Repositories\SettingRepository;


class BaseComposer
{
  
    private $settingRepository;


    public function __construct(
   
        SettingRepository $settingRepo
      
    )
    {
      
        $this->settingRepository = $settingRepo;
  
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

   
        
    }
}
