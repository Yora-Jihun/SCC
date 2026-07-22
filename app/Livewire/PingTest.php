<?php

namespace App\Livewire;

use App\Services\PingService;
use Livewire\Component;

class PingTest extends Component
{
    
    public string $result = '';

    public function check(PingService $pingService) : void
    {
        $this->result = $pingService->ping();
    }

    public function render()
    {
        return view('livewire.ping-test');
    }
}
