
# Install Laravel globaly
tcl@itcl-home:composer global require laravel/installer
tcl@itcl-home:vim ~/.profile 
tcl@itcl-home:~/www/vacancesweb$ export PATH=~/.config/composer/vendor/bin:$PATH
tcl@itcl-home:~/www/vacancesweb$ source ~/.profile
tcl@itcl-home:laravel -V

# create project
laravel new vacancesweb
php artisan package:discover
# install Mix: https://laravel-mix.com/docs/6.0/installation
npm init -y
npm install laravel-mix --save-dev
touch webpack.mix.js

# Install road runner: https://roadrunner.dev/docs/intro-install
composer require spiral/roadrunner:v2.12.2 nyholm/psr7
# Install octane
composer require laravel/octane
php artisan octane:install

# Run local
tcl@itcl-home:cp .env.example .env
tcl@itcl-home:php artisan key:generate

tcl@itcl-home:php artisan serve
Server running on [http://127.0.0.1:8000].

# Run with octane
tcl@itcl-home:php artisan octane:start --workers=2 --max-requests=5 # --host --port 


# Install Laravel globaly
tcl@itcl-home:composer global require laravel/installer
tcl@itcl-home:vim ~/.profile 
tcl@itcl-home:~/www/vacancesweb$ export PATH=~/.config/composer/vendor/bin:$PATH
tcl@itcl-home:~/www/vacancesweb$ source ~/.profile
tcl@itcl-home:laravel -V

# create project
laravel new vacancesweb
php artisan package:discover
# install Mix: https://laravel-mix.com/docs/6.0/installation
npm init -y
npm install laravel-mix --save-dev
touch webpack.mix.js

# Install road runner: https://roadrunner.dev/docs/intro-install
composer require spiral/roadrunner:v2.12.2 nyholm/psr7


# Install octane
composer require laravel/octane
php artisan octane:install

# Run local
tcl@itcl-home:cp .env.example .env
tcl@itcl-home:php artisan key:generate

tcl@itcl-home:php artisan serve
Server running on [http://127.0.0.1:8000].
# install sail
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up
sail artisan sail:publish

# Run with octane
tcl@itcl-home:php artisan octane:start --workers=2 --max-requests=5 # --host --port
in supervisor.conf adapt this line
command=/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan octane:start --workers=4 --task-workers=4 --max-requests=10 --server=swoole --host=0.0.0.0 --port=80 --watch 
npm install --save-dev chokidar
# DATABASE
# Create the data structure to store the jobs in the queue
./vendor/bin/sail php artisan queue:table
./vendor/bin/sail php artisan migrate
artisan migrate:fresh --seed (delete everything and seed, you can use mfs)

# Create a new job
./vendor/bin/sail php artisan make:job ProcessSomething

./vendor/bin/sail php artisan queue:work
./vendor/bin/sail php artisan queue:monitor default
./vendor/bin/sail php artisan queue:clear database --queue=first,second
./vendor/bin/sail php artisan queue:work --queue=first

./vendor/bin/sail php artisan queue:clear
./vendor/bin/sail php artisan queue:flush
./vendor/bin/sail php artisan queue:forget
./vendor/bin/sail php artisan queue:retry


# install REDIS to manage Queue
./vendor/bin/sail php artisan sail:install # then select 3
# confiure the .env
# and add an ALias to config/app.php
'Redis' => Illuminate\Support\Facades\Redis::class,

./vendor/bin/sail artisan about --only=drivers

# VITE configuration with sass and tailwind
./vendor/bin/sail npm install
./vendor/bin/sail npm add -D sass
create a file 'resources/scss/app.scss'
in vite.config.js add the file 'resources/scss/app.scss'

./vendor/bin/sail npm run dev

# https://tailwindcss.com/docs/guides/laravel
./vendor/bin/sail npm install -D tailwindcss postcss autoprefixer
./vendor/bin/sail npx tailwindcss init -p

./vendor/bin/sail npm run dev

./vendor/bin/sail php artisan make:component Layout/Home
./vendor/bin/sail php artisan make:component Tools/Checkbox --view (anonyme, no View class php)

# Single Action Controller
./vendor/bin/sail php artisan make:controller Home --invokable

# install Allpine js: https://alpinejs.dev/
./vendor/bin/sail npm install alpinejs

# install Livewire: https://laravel-livewire.com/docs/2.x/installation
./vendor/bin/sail composer require livewire/livewire
./vendor/bin/sail php artisan livewire:publish --config
./vendor/bin/sail php artisan livewire:publish --assets
./vendor/bin/sail php artisan make:livewire ShowPosts (--test)

sail artisan | grep livewire

# TEST
# use RefreshDatabase;
sail artisan make:test ProfileTest
sail artisan test


sail artisan storage:link