# Hypertube
19 project, Hypertube. #11

VisionTech
This is the 3rd web project in the cursus of 42 (school). It's a web application that allows users to search and watch movies and series. The player is directly integrated to the site and the videos are downloaded through the BitTorrent protocol.

The project is developped in PHP with Laravel and in Node.js for the streaming part. It uses mysql for the database.

*Research is done via the APIs of [yts.mx](https://yts.mx/api) and [eztv.io](https://eztv.io/api).*

### Collaborators
* Mukendi Patrick Tshiswaka (mtshisw)
* Tresor Luzingamu (tluzing)
* Rodrigue Yenyi Okoka (oyenyi-)
* Moses Muamba-Nzambi (mmuamba)

## Get started
```
sudo apt-get -y update && apt-get -y upgrade
sudo apt-get -y install sendmail composer nodejs npm
git clone https://github.com/tluzing/Hypertube
cd Hypertube && composer update
// create an SQL database name hypertube
// configurate .env file with your own data
php artisan migrate
cd node_real_stream && npm install && npm start
```
