# BL Flickr 1 Million Images Collection: Update April 2020
PHP code used to download, store to MySQL and update the BL Flickr 1 Million Images Collection (records' title and description) using Flickr API


## Features / steps:

- Downloads all the metadata associated with the photos at Flickr via its API (flickr.photos.search) -- pages of 250 records each;
- Stores the metadata into a MySQL DB ("snapshot" table);
- Updates the Title and Description, storing them in a new MySQL table;
- Reads the records from that new table and updates the records at Flickr via API (flickr.photos.setMeta) - one record at a time.

## Files:

### Dockerfile
Used to generate an image for the core PHP CLI container -- php:7.2.8-fpm with pdo_mysql, mysqli and memcached PHP extensions installed

### docker-compose.yml
Orchestration of the 3 containers: PHP from image above, MySQL and Memcached official images from DockerHub

Bring it up running:
```sh
$ /usr/local/bin/docker-compose up -d
```
### flic2mysql.php
Imports all the data given by the flickr.photos.search method (https://www.flickr.com/services/api/explore/flickr.photos.search) into MySQL. Each call retrieves 250 records (one page; max allowed by the API = 500 records per page), rendering in 4095 pages (calls) --- Total: 1M23K records.

Run it and log to a file with the current date / time stamp at the file name:
```sh
$ docker exec -it phpFlickr php flic2mysql.php > flic2mysql_`date +\%Y\%m\%d-\%H\%M`.txt
```
As a matter of precaution, do a backup of the DB (mysql dump):
```sh
docker exec -it database mysqldump -u <user> -p<password> Flickr | gzip -9 > flickr_<date>_<description>.sql.gz 
```
### composer.json:
Needed to get and install dependencies of "_this app local directory_/updateDescAndTitle.php" (to be uploaded)
```sh
$ docker run --rm -it -v <this app local directory>:/var/www/html/ jitesoft/composer php phpflickr/updateDescAndTitle.php
```
