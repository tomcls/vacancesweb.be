<?php

namespace App\Http\Livewire\Admin\Holiday;

use App\Models\Holiday as HolidayModel;
use App\Models\HolidayDescription;
use App\Models\HolidayRegion;
use App\Models\HolidayTitle;
use App\Models\HolidayType;
use App\Models\Region;
use App\Models\User;
use App\Repositories\HereMapRepository;
use App\Traits\Autocomplete\WithHeremap;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class HolidayDetail extends Component
{
    use WithHeremap;

    public HolidayModel $holiday;

    public $lang = null;
    public $users = null;
    public $active = false;
    public $regions = null;
    public $userSearch = null;
    public $regionSearch = null;
    public $holidayTypes = null;

    public $titles = [];
    public $descriptions = [];

    protected $rules = [
        'holiday.user_id' => 'required',
        'holiday.longitude' => 'required',
        'holiday.latitude' => 'required',
        'holiday.holiday_type_id' => 'required',
        'holiday.startdate' => 'required',
        'holiday.enddate' => 'required',
        'holiday.email' => 'required',
        'holiday.phone' => 'sometimes',
    ];

    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
        'onMarkerDragend' => 'setLocation',
        'setTitle' => 'setTitle'
    ];
    public function mount($holidayId)
    {
        $this->holidayTypes = HolidayType::all();

        $this->lang =  App::currentLocale();

        collect(config('app.langs'))->map(function ($lang) {
            $this->titles[$lang] = $this->makeBlankHolidayTitle($lang);
            $this->descriptions[$lang] = $this->makeBlankHolidayDescription($lang);
        });

        try {
            $this->holiday = HolidayModel::findOrFail($holidayId );
            $this->active = $this->holiday->active;
            $this->holiday->holidayTitles->map(function ($title) {
                $this->titles[$title->lang] = $title;
            });
            $this->holiday->holidayDescriptions->map(function ($description) {
                $this->descriptions[$description->lang] = $description;
            });
            $this->userSearch = $this->holiday->user->firstname . "  " . $this->holiday->user->lastname;
            
        } catch (ModelNotFoundException $e) {
            $this->holiday = $this->makeBlankHoliday();
        }
    }

    public function makeBlankHoliday()
    {
        return HolidayModel::make([]);
    }
    public function active()
    {
        if ($this->active) {
            $this->active = false;
        } else {
            $this->active = true;
        }
    }
    public function makeBlankHolidayTitle($lang)
    {
        return new HolidayTitle(['lang' => $lang, 'name' => '', 'slug' => '']);
    }

    public function makeBlankHolidayDescription($lang)
    {
        return HolidayDescription::make(['lang' => $lang]);
    }

    public function save()
    {
        $this->emit("isTitleValid");
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->titleIsInvalid()) {
                    $validator->errors()->add('holidayTitle.name', 'The title should be filled in');
                    $this->notify(['message'=>'The title should be filled in','type'=>'alert']);
                }
                if ($this->slugIsInvalid()) {
                    $validator->errors()->add('slug', 'The slug should be filled in');
                    $this->notify(['message'=>'The slug should be filled in','type'=>'alert']);
                }
            });
        })->validate();
        $this->holiday->active = $this->active;
        $this->holiday->save();
        $this->emit("saveContent", $this->holiday->id);

        $digResult = Region::dig($this->holiday->longitude, $this->holiday->latitude);
        if(!empty($digResult['region'])) {
            $holidayRegions = [];
            foreach ($digResult['region'] as $level) {
               array_push($holidayRegions,['region_id'=>$level['geom']->id,'holiday_id'=>$this->holiday->id]);
            }
            HolidayRegion::insertOrIgnore($holidayRegions);
        }
        $this->emit("setHolidayId", $this->holiday->id);
        $this->notify(['message'=>'Holiday well saved','type'=>'success']);
    }

    public function titleIsInvalid()
    {
        foreach ($this->titles as $title) {
            if ($title['name']) return false;
        }
        return true;
    }

    public function slugIsInvalid()
    {
        foreach ($this->titles as $slug) {
            if (isset($slug['slug'])) return false;
        }
        return true;
    }

    public function usersResult()
    {
        if (!empty($this->userSearch)) {
            $query = User::query()->select(DB::raw("id, CONCAT(firstname,' ', lastname) AS title, email as subtitle"))
                ->when($this->userSearch, fn ($query, $name) => $query
                    ->where('firstname', 'like', '%' . $name . '%')
                    ->orWhere('lastname', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%'))->limit(5);
            $this->users = $query->get();
        }
    }

    public function regionsResult()
    {
        if ($this->regionSearch) {
            $heremap = new HereMapRepository();

            $suggestions = $heremap->suggest([
                'query' => urlencode($this->regionSearch),
                'language' => $this->lang,
            ]);
            $this->regions = $this->formatSuggestions($suggestions);
        }
    }

    public function setAutoCompleteItem($type, $text, $id)
    {
        switch ($type) {
            case 'location':
                $heremap = new HereMapRepository();
                $position = $heremap->geocode([
                    'searchtext' => $id,
                    'language' => $this->lang,
                ]);
                if (isset($position->location->displayPosition)) {

                    $this->regionSearch = $text;

                    $this->holiday->longitude = $position->location->navigationPosition[0]['Longitude'];
                    $this->holiday->latitude = $position->location->navigationPosition[0]['Latitude'];

                    $this->regions = null;

                    $this->emit('locationChanged', ["lat" => $position->location->displayPosition->latitude, "lng" => $position->location->displayPosition->longitude]);
                }
                break;
            case 'holiday.user_id':
                $this->userSearch = $text;
                $this->holiday->user_id = $id;
                $this->users = null;
                break;
            default:
                # code...
                break;
        }
    }

    public function setLocation($location)
    {
        $this->holiday->longitude = $location['lng'];
        $this->holiday->latitude = $location['lat'];
    }

    public function refreshContent()
    {
        $this->emit('refreshContent', $this->lang);
    }

    public function setTitle($lang, $title)
    {
        $this->titles[$lang]['name'] = $title;
        $this->titles[$lang]['slug'] = $title;
        $this->emit('refreshContent', $this->lang);
    }

    public function render()
    {
        return view('livewire.admin.holiday.holiday-detail');
    }
}
