<?php

namespace App\Providers;

use App\Events\ConvertHtmlToDocxAfterEditTemplate;
use App\Events\FireContentForTemplate;
use App\Events\GetCountryAndRegionFromLocationUser;
use App\Events\RenderFileWhenCreateTemplateMarket;
use App\Events\RenderImageAfterCreateTemplate;
use App\Events\sendMailAttachFile;
use App\Events\ApplyJobsEvent;
use App\Handlers\Events\ConvertListener;
use App\Listeners\AttachMail;
use App\Listeners\ApplyJobsListener;
use App\Listeners\FireEventCreateCountryRegion;
use App\Listeners\InvoiceCheckoutListener;
use App\Listeners\RenderFileTemplateMarketListener;
use App\Listeners\RenderImageListener;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatePathWhenSaved::class => [
           UpdatePathListener::class,
        ],
        ConvertHtmlToDocxAfterEditTemplate::class => [
            ConvertListener::class
        ],
        sendMailAttachFile::class => [
            AttachMail::class
        ],
        RenderImageAfterCreateTemplate::class => [
            RenderImageListener::class
        ],
        FireContentForTemplate::class => [
            InvoiceCheckoutListener::class
        ],
        RenderFileWhenCreateTemplateMarket::class => [
            RenderFileTemplateMarketListener::class
        ],
        GetCountryAndRegionFromLocationUser::class => [
            FireEventCreateCountryRegion::class
        ],
        ApplyJobsEvent::class => [
            ApplyJobsListener::class
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
