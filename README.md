# The British Library's Flickr 1 Million Images Collection 
## 1,023,705 Records' Update April 2020
Infrastructure (Docker Orchestration) and PHP code used to download, store to MySQL (all the records' metadata) and update the [BL Flickr 1 Million Images Collection](https://www.flickr.com/photos/britishlibrary/) (records' title and description) using Flickr API

## Features / steps:

- Downloads all the metadata associated with the photos at Flickr via its API (flickr.photos.search) -- pages of 250 records each;
- Stores the metadata into a MySQL DB ("snapshot" table);
- Updates the Title and Description, storing them in a new MySQL table;
- Reads the records from that new table and updates the records at Flickr via API (flickr.photos.setMeta) - one record at a time.

## Files:
(and steps to be run in the order presented)

### Dockerfile
Used to generate an image for the core PHP CLI container: php:7.2.8-fpm with pdo_mysql, mysqli and memcached PHP extensions installed.

### docker-compose.yml
Orchestration of the 3 containers: PHP from image above, MySQL and Memcached official images from DockerHub.

Bring it up running:
```sh
$ /usr/local/bin/docker-compose up -d
```

### MySQL_DB_Flickr_CREATE_TABLES.sql
SQL to create the tables needed to store the data at the MySQL server (_database_ container, _Flickr_ database)
```sh
$ docker exec -it database mysql -u root -p Flickr < MySQL_DB_Flickr_CREATE_TABLES.sql
```

### flic2mysql.php
Imports all the data given by the [flickr.photos.search](https://www.flickr.com/services/api/explore/flickr.photos.search) method into MySQL. Each call retrieves 250 records (one page; max allowed by the API = 500 records per page), rendering in 4095 pages (calls) --- Total: 1M23K records.

Run it and log to a file with the current date / time stamp at the file name -- it will take more than 24 hours to run:
```sh
$ docker exec -it phpFlickr php flic2mysql.php > flic2mysql_`date +\%Y\%m\%d-\%H\%M`.txt
```
Once finished (or if interrupting running the script above), as a matter of precaution, do a backup of the DB (mysql dump):
```sh
$ docker exec -it database mysqldump -u <user> -p<password> Flickr | gzip -9 > flickr_<date>_<description>.sql.gz 
```
### composer.json:
Needed to get and install dependencies of "_this app local directory_/updateDescAndTitle.php"
```sh
$ docker run --rm -it -v <this app local directory>:/var/www/html/ jitesoft/composer php phpflickr/updateDescAndTitle.php
```

Note -- to save a text file as tab-delimited, UTF-8 encoded from Excel spreadsheet:

* Choose File->Save as from the menu > "Unicode Text"
* Open the output file with Notepad++ > Encoding > Convert to UTF-8