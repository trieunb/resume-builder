<?php

namespace App\Events;


use Anam\PhantomMagick\Converter;
use App\Events\Event;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RenderFileWhenCreateTemplateMarket extends Event
{
    use SerializesModels;

    private $slug;
    private $content;
    private $template_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($slug, $content, $template_id)
    {
        $this->slug = $slug;
        $this->content = $content;
        $this->template_id = $template_id;
    }

    public function render(TemplateMarketInterface $template)
    {
        /*\PDF::loadView('admin.template.render', ['content' => $this->content])
            ->save(public_path('pdf/'.$this->slug.'.pdf'));*/
        $pdf_settings = \Config::get('laravel-tcpdf');
        $pdf = new \Elibyy\TCPDF\TCPdf($pdf_settings['page_orientation'], $pdf_settings['page_units'], $pdf_settings['page_format'], true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();
        $pdf->writeHTML(view('admin.template.render', ['content' => $this->content])->render());
        $pdf->output(public_path('pdf/'.$this->slug.'.pdf'), 'F');

        $filename = convertPDFToIMG($this->slug);

        $this->saveFile($template, $filename);
    }

    /**
     * Save thumbnail
     * @param  TemplateInterface $template 
     * @return mixed           
     */
    private function saveFile($TemplateMarketInterface, $filename)
    {
         $resize = \Image::make(public_path('images/template/'.$filename.'.jpg'))
            ->resize(200,150)
            ->save(public_path('thumb/template/'.$filename.'.jpg'));

        if (!$resize) return null;

        $template = $TemplateMarketInterface->getById($this->template_id);
        $template->image = [
            'origin' => 'images/template/'.$filename.'.jpg',
            'thumb' => 'thumb/template/'.$filename.'.jpg'
        ];
        $template->source_file_pdf = 'pdf/'.$this->slug.'.pdf';
        
        return $template->save();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
