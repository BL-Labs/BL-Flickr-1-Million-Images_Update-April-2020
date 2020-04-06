# BL Flickr 1 Million Images Collection: Update April 2020
### PHP code used to download, store to MySQL and update the BL Flickr 1 Million Images Collection (records' title and description) using Flickr API

## Files:

### Dockerfile
Used to generate an image for the core PHP CLI container -- php:7.2.8-fpm with pdo_mysql, mysqli and memcached PHP extensions installed

### docker-compose.yml
Orchestration of the 3 containers: PHP from image above, MySQL and Memcached official images from DockerHub

Bring it up running "/usr/local/bin/docker-compose up -d"

### flic2mysql.php
Imports all the data given by the flickr.photos.search method (https://www.flickr.com/services/api/explore/flickr.photos.search) into MySQL. Each call retrieves 250 records (one page; max allowed by the API = 500 records per page), rendering in 4095 pages (calls) --- Total: 1M23K records.

Run it and log to a file wirth the current date / time:
docker exec -it phpFlickr php flic2mysql.php > flic2mysql_`date +\%Y\%m\%d-\%H\%M`.txt

### composer.json:
Needed to get and install dependencies of phpflickr/updateDescAndTitle.php (to be uploaded)
docker run --rm -it -v /home/filipeb/FlickrPHP:/var/www/html/ jitesoft/composer php phpflickr/updateDescAndTitle.php