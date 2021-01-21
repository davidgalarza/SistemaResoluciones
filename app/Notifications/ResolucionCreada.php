<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Formato;
use App\Consejo;
use Carbon\Carbon;

class ResolucionCreada extends Notification implements ShouldQueue
{
    use Queueable;
    private $resolucion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($var1)
    {
        $this->resolucion = $var1;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $formato = Formato::findOrFail($this->resolucion->formato_id);
        $consejo = Consejo::findOrFail($this->resolucion->consejo_id);
        $fechaConsejo = $consejo->updated_at;
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon::parse($fechaConsejo)->timezone('America/Bogota');
        $mes = $meses[($fecha->format('n')) - 1];
        $fechaString = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

        return (new MailMessage)
                    ->subject('Resolución de '. $formato->nombre . ' fue aprobada')
                    ->line('Por medio del presente, me permito notificar que la resolución de '.$formato->nombre . ' fue aceptada el '. $fechaString . ' puede retirarla en secretaría de carrera.')
                    ->action('Buscar en Redi', url('http://redi.uta.edu.ec/simple-search'))
                    ->line('Juntos construimos la mejor universidad del país.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
