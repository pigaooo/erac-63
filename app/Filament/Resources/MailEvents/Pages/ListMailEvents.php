<?php

namespace App\Filament\Resources\MailEvents\Pages;

use App\Filament\Resources\MailEvents\MailEventResource;
use Filament\Resources\Pages\ListRecords;

class ListMailEvents extends ListRecords
{
    protected static string $resource = MailEventResource::class;
}
