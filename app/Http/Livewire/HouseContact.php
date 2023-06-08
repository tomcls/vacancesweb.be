<?php

namespace App\Http\Livewire;

use App\Models\HouseTitle;
use App\Repositories\MailRepository;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class HouseContact extends Component
{
    public $contact = [
        "date_from"=>null,
    ];
    public $lang;    
    public $showContactModal = false;

    protected $listeners = ['openContactForm' => 'openContactForm'];

    public function rules()
    {
        return [
            'contact.firstname' => 'required|min:2',
            'contact.lastname' => 'required',
            'contact.email' => 'required|email',
            'contact.house_email' => 'required|email',
            'contact.phone' => 'required',
            'contact.date_from' => 'required',
            'contact.stay' => 'required',
            'contact.lang' => 'required',
            'contact.user_id' => 'sometimes',
            'contact.house_id' => 'required',
            'contact.house_url' => 'required',
            'contact.house_title' => 'required',
            'contact.number_people' => 'required',
            'contact.message' => 'sometimes',
        ];
    }

    public function mount() {
        $this->lang = App::currentLocale();
    }

    public function openContactForm($houseId){
        $this->contact['house_id'] = $houseId;
        $this->showContactModal = true;
    }

    public function send() {
        $this->contact['lang'] = App::currentLocale();
        $this->contact['user_id']= auth()->user()->id??null;
        $house = HouseTitle::wherehouseId($this->contact['house_id'])->whereLang(App::currentLocale())->first();
        $this->contact['house_title'] = $house->name;
        $this->contact['house_email'] = $house->house->email;
        $this->contact['house_url'] = env('APP_URL').'/house?house_id='.$this->contact['house_id'];
        $this->validate();
        $this->showContactModal = false;
        $this->notify(['message'=>'Message bien envoyé','type'=>'success']);
        $mail = new MailRepository();
        $mail->sendHouseContact($this->contact);
    }
    
    public function render()
    {
        return view('livewire.house-contact');
    }
}
