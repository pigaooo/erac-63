<?php

namespace App\Livewire\Filament;

use Filament\Livewire\Sidebar;
use Illuminate\Contracts\View\View;

class AdminSidebar extends Sidebar
{
    public function render(): View
    {
        return view('livewire.filament.admin-sidebar');
    }
}
