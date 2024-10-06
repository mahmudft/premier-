


It is fullly dockerized  Insider App

Teams will be generated as Seeder with random strengths

Champions league results are calculated on fly

There is UI based on React intagrated to Blade template and minified for production 

As Tech Stack

Backend Develpent:
- Laravel
- Php
- Repositary Pattern

Forntend Development
- React
- Chakra UI 

Database
- MYSQL

Steps:

Run
docker compose up 

after all is up

go into laravel app

run this command 


php artisan migrate && php artisan db:seed --class=TeamsSeeder


Now Open 
- http://localhost:8000


Extras--

If you want to change frontend 

first install frontend dependencies 
npm install 

Do Changes in
resources/js/components


Build the solution

npm run build 