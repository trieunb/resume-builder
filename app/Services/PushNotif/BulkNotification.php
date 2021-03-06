<?php

namespace App\Services\PushNotif;

use Queue;
use PushNotification as NotifPusher;
use Illuminate\Contracts\Support\Arrayable;

class BulkNotification
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $devices;

    /**
     * @param array          $devices      array of DeviceContract instances
     * @param string         $text         title of notification
     * @param array          $notifOptions notification option, such as: alert, sound ...
     * @param array          $customData   notification custom data, will be wrapped by 'custom' key
     */
    public function __construct(Arrayable $devices, $text, array $notifOptions = [], array $customData = [])
    {
        $this->devices = [];

        /* @var App\Contracts\DeviceContract */
        foreach ($devices as $device) {
            $this->devices[] = new Device(
                $device->getDeviceId(),
                $device->getDevicePlatform()
            );
        }
        $this->text = $text;
        if ($customData) {
            $notifOptions['custom'] = $customData;
        }
        $this->options = $notifOptions;
    }

    public function push()
    {
        $andoidDevices = NotifPusher::DeviceCollection();
        $iosDevices = NotifPusher::DeviceCollection();
        /* @var App\Services\PushNotif\Device */
        foreach ($this->devices as $device) {
            $pushToDevice = NotifPusher::Device($device->id);
            if ($device->isAndroidPlatform()) {
                $andoidDevices->add($pushToDevice);
            } elseif ($device->isIOSPlatform()) {
                $iosDevices->add($pushToDevice);
            }
        }

        $message = NotifPusher::Message($this->text, $this->options);
        if (count($andoidDevices->getTokens())) {
            NotifPusher::app('AndroidApp')
                ->to($andoidDevices)
                ->send($message);
        }
        if (count($iosDevices->getTokens())) {
            NotifPusher::app('IOSApp')
                ->to($iosDevices)
                ->send($message);
        }
    }

    public function pushLater()
    {
        Queue::push(function($job) {
            \Log::info('message', ['test']);
            $this->push();
            $job->delete();
        });
    }
}
