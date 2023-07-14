<?php

namespace App\Http\Livewire\Admin\Holiday;

use App\Models\Holiday;
use App\Models\HolidayImage;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Image;
use File;

class HolidayImages extends Component
{
    use WithFileUploads;

    public $total;
    public $newImage;
    public $holidayId;

    public $files = [];

    protected $rules = [
        'image.name' => 'required|max:150',
        'image.holiday_id' => 'required',
        'image.sort' => 'nullable',
        'image.origin' => 'required|max:255',
        'newImage' => 'nullable|image|max:3000000',
    ];

    protected $listeners = ['saveImages' => 'save', 'reorder' => 'reorder'];

    public function mount($holidayId)
    {
        $this->holidayId = $holidayId;
    }

    public function getImagesProperty()
    {
        try {
            $images = Holiday::find($this->holidayId)->holidayImages;
            $this->total = count($images);
            return $images;
        } catch (Exception $e) {
            return [];
        }
    }
    public function save()
    {
        $destinationPathThumbnail = storage_path('app/holidays/images') . '/' . $this->holidayId;
        $this->notify(['message'=>'Start processing image(s)... please wait','type'=>'alert']);
        try {
            File::makeDirectory($destinationPathThumbnail, 0777, false, false);
        } catch (Exception $e) {
        }

        foreach ($this->files as $image) {
            $name = Str::random(30);
            $img = Image::make($image->path());
            // Big Resize 2048
            if ($img->width() >= 2048) {
                $img->resize(2048, 2048, function ($contraint) {
                    $contraint->aspectRatio();
                })->encode('webp', 75)->save($destinationPathThumbnail . '/large_' . $name . '.webp', 75);
            } else {
                $img->encode('webp', 75)->save($destinationPathThumbnail . '/large_' . $name . '.webp', 75);
            }
            if ($img->width() >= 1024) {
                $img->resize(1024, 1024, function ($contraint) {
                    $contraint->aspectRatio();
                })->encode('webp', 75)->save($destinationPathThumbnail . '/medium_' . $name . '.webp', 75);
            } else {
                $img->encode('webp', 75)->save($destinationPathThumbnail . '/medium_' . $name . '.webp', 75);
            }
            if ($img->width() >= 400) {
                $img->encode('webp', 75)->resize(400, 400, function ($contraint) {
                    $contraint->aspectRatio();
                })->encode('webp', 75)->save($destinationPathThumbnail . '/small_' . $name . '.webp', 75);
            } else {
                $img->encode('webp', 75)->save($destinationPathThumbnail . '/small_' . $name . '.webp', 75);
            }
            $holidayImage = new HolidayImage([
                'name' => $name . '.webp',
                'holiday_id' => $this->holidayId,
                'origin' => $image->getClientOriginalName(),
                'sort' => $this->total
            ]);
            $this->total ++;
            $holidayImage->save();
            $img->destroy();
        }
        $this->files = [];

        $this->notify(['message'=>'Image(s) well saved','type'=>'success']);
        $this->emit('initDragAndDrop');
    }
    public function rotate($id)
    {
        $name = Str::random(30);
        $destinationPathThumbnail = storage_path('app/holidays') . '/' . $this->holidayId;
        foreach ([ 'large','medium', 'small']  as $size) {
            
            $image = HolidayImage::find($id);
            $img = Image::make($image->path($size));
            $img->rotate(-90);
            $img->save($destinationPathThumbnail . '/'.$size.'_' . $name . '.webp');
           
            $img->destroy();
            if (File::exists($image->path($size))) {
                File::delete($image->path($size));
            }
        }
        HolidayImage::find($id)->update([
            'name' => $name . '.webp',
            'holiday_id' => $this->holidayId,
            'origin' => $image->origin,
            'sort' => $image->sort
        ]);
    }
    public function delete($id)
    {
        $image = HolidayImage::find($id);
        foreach (['large', 'medium', 'small']  as $size) {
            if (File::exists($image->path($size))) {
                File::delete($image->path($size));
            }
        }
        $image->delete();
    }
    public function reorder($orderedIds)
    {
        collect($orderedIds)->map(function ($id, $key) {
            return HolidayImage::find($id)->update(['sort' => $key]);
        });
    }

    public function render()
    {
        return view('livewire.admin.holiday.holiday-images', [
            'images' => $this->images,
        ]);
    }
}
