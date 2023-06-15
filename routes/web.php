<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LogoController;
use App\Http\Livewire\Admin\Geo\Countries;
use App\Http\Livewire\Admin\Geo\Country as GeoCountry;
use App\Http\Livewire\Admin\Geo\Region as GeoRegion;
use App\Http\Livewire\Admin\Geo\Regions;
use App\Http\Livewire\Admin\Holiday\Contacts as HolidayContacts;
use App\Http\Livewire\Admin\Holiday\Holiday;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Holiday\Holidays;
use App\Http\Livewire\Admin\Holiday\Transactions as HolidayTransactions;
use App\Http\Livewire\Admin\Homepage;
use App\Http\Livewire\Admin\House\Contacts;
use App\Http\Livewire\Admin\House\ContactsBanned;
use App\Http\Livewire\Admin\House\House;
use App\Http\Livewire\Admin\House\HousePromos;
use App\Http\Livewire\Admin\House\Houses;
use App\Http\Livewire\Admin\House\Highlights;
use App\Http\Livewire\Admin\House\Reservations;
use App\Http\Livewire\Admin\House\Transactions;
use App\Http\Livewire\Admin\House\Icals;
use App\Http\Livewire\Admin\House\Packages;
use App\Http\Livewire\Admin\House\PackageUsers;
use App\Http\Livewire\Admin\House\Publications;
use App\Http\Livewire\Admin\House\Seasons;
use App\Http\Livewire\Admin\Partner\Boxes;
use App\Http\Livewire\Admin\Partner\Catalogs;
use App\Http\Livewire\Admin\Partner\Holidays as PartnerHolidays;
use App\Http\Livewire\Admin\Partner\Homes;
use App\Http\Livewire\Admin\User\Companies;
use App\Http\Livewire\Admin\User\Newsletters;
use App\Http\Livewire\Admin\User\Users;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Holidays as LivewireHolidays;
use App\Http\Livewire\Home;
use App\Http\Livewire\Houses as LivewireHouses;
use App\Http\Livewire\Profile;
use App\Http\Middleware\SearchMiddelware;

use App\Http\Livewire\Me\House\Houses as MeHouses;
use App\Http\Livewire\Me\House\House as MeHouse;
use App\Http\Livewire\Me\House\HouseDetail as MeHousDetail;
use App\Http\Livewire\Me\Invoices as MeInvoices;
use App\Http\Livewire\Me\Messages as MeMessages;
use App\Http\Livewire\Me\Dashboard as MeDashboard;
use App\Http\Livewire\Me\Profile as MeProfile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::post('livewire/message/{name}', '\Livewire\Controllers\HttpConnectionHandler'); 

//Route::get('/', function () {echo 'home';});
//Octane::route('GET','/index',function () {return new Response((new HomeController)->index());});

//Route::get('/register', Register::class);

//Route::get('/profile', Profile::class);
//Route::get('/profile', function () {return view('components.pages.profile');});

Route::get('/', Home::class)->name('home');
Route::get('/index', Home::class)->name('home');
Route::get('/logo-image', [LogoController::class,'index'])->name('logo');

Route::get('/vacances', LivewireHolidays::class)->name('holidays');
Route::get('/chercher/location-vacances', LivewireHouses::class)->name('houses');
Route::get('/chercher/location-vacances/{search}', LivewireHouses::class)->where('search','.*')->name('searchHouses')->middleware(SearchMiddelware::class);
/*Route::get('/chercher/location-vacances/{house_type}/{search}', function (string $houseType,string $search) {
    return 'User '.$houseType;
})->whereIn('house_type',HouseTypeTranslation::select('slug')->get()->pluck('slug'))->where('search','.*');*/
/**
 * Authentication
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('auth.login');
    Route::get('/register', Register::class)->name('auth.register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');

    Route::get('/admin/homepage', Homepage::class)->name('admin.homepage');
    Route::get('/admin/user/users', Users::class)->name('admin.users');
    Route::get('/admin/user/companies', Companies::class)->name('admin.companies');
    Route::get('/admin/user/newsletters', Newsletters::class)->name('admin.newsletters');

    Route::get('/admin/holidays', Holidays::class)->name('admin.holidays');
    Route::get('/admin/holidays/transactions', HolidayTransactions::class)->name('admin.holiday.transactions');
    Route::get('/admin/holidays/contacts', HolidayContacts::class)->name('admin.holiday.contacts');
    Route::get('/admin/holiday/{id?}', Holiday::class)->name('admin.holiday');

    Route::get('/admin/houses', Houses::class)->name('admin.houses');
    Route::get('/admin/house/transactions', Transactions::class)->name('admin.transactions');
    Route::get('/admin/house/reservations', Reservations::class)->name('admin.reservations');
    Route::get('/admin/house/seasons', Seasons::class)->name('admin.seasons');
    Route::get('/admin/house/highlights', Highlights::class)->name('admin.highlights');
    Route::get('/admin/house/packages', Packages::class)->name('admin.packages');
    Route::get('/admin/house/package-users', PackageUsers::class)->name('admin.package-users');
    Route::get('/admin/house/icals', Icals::class)->name('admin.icals');
    Route::get('/admin/house/promos', HousePromos::class)->name('admin.promos');
    Route::get('/admin/house/publications', Publications::class)->name('admin.publications');
    Route::get('/admin/house/contacts', Contacts::class)->name('admin.house.contacts');
    Route::get('/admin/house/contactsbanned', ContactsBanned::class)->name('admin.house.contactsbanned');
    Route::get('/admin/house/{id?}', House::class)->name('admin.house');
    
    Route::get('/admin/geo/countries', Countries::class)->name('admin.countries');
    Route::get('/admin/geo/country/{id?}', GeoCountry::class)->name('admin.country');
    
    Route::get('/admin/geo/regions', Regions::class)->name('admin.regions');
    Route::get('/admin/geo/region/{id?}', GeoRegion::class)->name('admin.region');

    Route::get('/admin/partner/homes', Homes::class)->name('admin.partner.homes');
    Route::get('/admin/partner/catalogs', Catalogs::class)->name('admin.partner.catalogs');
    Route::get('/admin/partner/holidays', PartnerHolidays::class)->name('admin.partner.holidays');
    Route::get('/admin/partner/boxes', Boxes::class)->name('admin.partner.boxes');

    Route::get('/admin/invoice/{id?}', [InvoiceController::class,'download'])->name('admin.invoice');

    Route::get('/me/house/{id?}', MeHouse::class)->name('me.house');
    Route::get('/me/profile', MeProfile::class)->name('me.profile');
    Route::get('/me/dashboard', MeDashboard::class)->name('me.dashboard');
    Route::get('/me/houses', MeHouses::class)->name('me.houses');
    Route::get('/me/invoices', MeInvoices::class)->name('me.invoices');
    Route::get('/me/messages', MeMessages::class)->name('me.messages');
});