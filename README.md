### CSV source

1. Get the CSV from [geodatasource](https://www.geodatasource.com/world-cities-database/free), contains the following:

        GEODATASOURCE-CITIES-FREE.TXT 
        GEODATASOURCE-COUNTRY.TXT           
        GEODATASOURCE_LICENSE_AGREEMENT.PDF 
        GEODATASOURCE-REGION.TXT            
        GEODATASOURCE-SUBREGION.TXT         
        README.HTML                  
       
2. Check `GEODATASOURCE-CITIES-FREE.TXT`. **if it contains multiple line endings**: resolve with

        dos2unix GEODATASOURCE-CITIES-FREE.TXT

3. Import the `city` CSV with:

        LOAD DATA LOCAL
        INFILE 'D:/Users/ZiriusPH/Desktop/New folder/GEODATASOURCE-CITIES-FREE.TXT'
        INTO TABLE `city`
        FIELDS TERMINATED BY '\t'
        LINES TERMINATED BY '\r\n'
        IGNORE 1 LINES
        (cc_fips,full_name_nd);

4. Import the `country` csv with:

        LOAD DATA LOCAL
        INFILE 'D:/Users/ZiriusPH/Desktop/New folder/GEODATASOURCE-COUNTRY.TXT'
        INTO TABLE `country`
        FIELDS TERMINATED BY '\t'
        LINES TERMINATED BY '\r\n'
        IGNORE 1 LINES
        (cc_fips,cc_iso,tld,country_name);