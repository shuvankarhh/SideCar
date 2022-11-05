#!/bin/bash

php artisan optimize:clear

php artisan migrate:refresh

#php artisan db:seed --class=PermissionTableSeeder

#php artisan db:seed --class=CreateAdminUserSeeder

#php artisan db:seed --class=CreateDummyDataSeeder
