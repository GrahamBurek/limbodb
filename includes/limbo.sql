# Creates and populates a Database for Limbo
# Maxim Vitkin, Graham Burek, Liam Harwood
# Version 0.0.1

# creates database 'limbo_db' if it does not already exist and begins using it
DROP DATABASE IF EXISTS limbo_db;
CREATE DATABASE IF NOT EXISTS limbo_db;
USE limbo_db;


# creates table for users containing all corresponding information
CREATE TABLE IF NOT EXISTS users(
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
username VARCHAR(40) UNIQUE NOT NULL,
first_name VARCHAR(20) NOT NULL,
last_name VARCHAR(40) NOT NULL,
email VARCHAR(60) NOT NULL,
pass CHAR(40) NOT NULL,
reg_date DATETIME NOT NULL,
PRIMARY KEY(user_id),
UNIQUE(email)
);


# inserts admin credentials into 'users' table
INSERT INTO users(username, first_name, last_name, email, pass, reg_date) VALUES(
"admin",
"Maxim",
"Vitkin",
"maxim.vitkin1@marist.edu",
"gaze11e",
Now());

# creates table 'stuff' for objects containing corresponding information
CREATE TABLE IF NOT EXISTS stuff(
id INT PRIMARY KEY AUTO_INCREMENT,
item TEXT NOT NULL,
location_id INT NOT NULL,
category SET('Electronics', 'Clothing', 'School Supplies', 'Other') NOT NULL,
color TEXT NOT NULL,
description TEXT NOT NULL,
item_date DATE NOT NULL,
create_date DATE NOT NULL,
update_date DATE NOT NULL,
room TEXT,
uploaderEmail TEXT,
status SET('Found', 'Lost', 'Claimed') NOT NULL,
image TEXT,
FOREIGN KEY (location_id) REFERENCES locations(id)
);

INSERT INTO stuff(item, location_id, category, color, description, item_date, create_date, update_date, room, uploaderEmail, status, image) VALUES(
"iPhone Charger",
3,
"Electronics",
"White",
"Basic white Apple iPhone wall charger. Slightly frayed at one end.",
"2015-11-18",
Now(),
Now(),
NULL,
"graham.burek1@gmail.com",
"Lost",
"uploads/charger.jpg"
),

("Marist sweatshirt",
17,
"Clothing",
"Red",
"Solid red sweatshirt with the word 'Marist' across the front in block letters.",
"2015-11-20",
Now(),
Now(),
"HC2020",
"example@example.com",
"Found",
"uploads/sweatshirt.jpg"
),

("Wallet with $200",
1,
"Other",
"Black",
"Black leather wallet holding $200 in twenties, lost somewhere on campus, not sure where.",
"2015-12-6",
Now(),
Now(),
NULL,
"generic@genericwebsite.com",
"Lost",
"uploads/wallet.jpg"
),

("Green Earring",
22,
"Clothing",
"Green",
"Earring with a small green gem attached to it.",
'2015-11-19',
Now(),
Now(),
NULL,
"liam.harwood1@marist.edu",
"Found",
"uploads/earring.jpg"
),

("Calculus Book",
26,
"School Supplies",
"Blue",
"Blue Stewart Calculus book, 7th Edition. Slightly worn with a rental sticker.",
'2015-11-16',
Now(),
Now(),
NULL,
"Bob.Johnson@email.com",
"Lost",
"uploads/book.png"
),

("Apple Watch",
1,
"Electronics",
"Silver",
"Basic Apple Watch with slight scratches on the screen. Not sure where I lost it.",
"2015-11-10",
Now(),
Now(),
NULL,
"Bill.Roberts@marist.edu",
"Found",
"uploads/watch.jpg"
);


# creates table 'locations' of all locations on campus
CREATE TABLE IF NOT EXISTS locations(
id INT PRIMARY KEY AUTO_INCREMENT,
create_date DATETIME NOT NULL,
update_date DATETIME NOT NULL,
location_name TEXT NOT NULL);


# populates 'locations' with all locations on campus
INSERT INTO locations(create_date, update_date, location_name) VALUES
(Now(), Now(), "Unknown/Not Sure"),
(Now(), Now(), "Byrne House"),
(Now(), Now(), "James A. Cannavino Libarary"),
(Now(), Now(), "Champagnat Hall"),
(Now(), Now(), "Our Lady Seat of Wisdom Chapel"),
(Now(), Now(), "Cornell Boathouse"),
(Now(), Now(), "Donnelly Hall"),
(Now(), Now(), "Dyson Center"),
(Now(), Now(), "Fern Tor"),
(Now(), Now(), "Fontaine Annex"),
(Now(), Now(), "Fontaine Hall"),
(Now(), Now(), "Foy Townhouses"),
(Now(), Now(), "Fulton Street Townhouses"),
(Now(), Now(), "Lower Fulton Townhouses"),
(Now(), Now(), "Gartland Apartments"),
(Now(), Now(), "Greystone Hall"),
(Now(), Now(), "Hancock Center"),
(Now(), Now(), "Kieran Gatehouse"),
(Now(), Now(), "Kirk House"),
(Now(), Now(), "Leo Hall"),
(Now(), Now(), "Longview Park"),
(Now(), Now(), "Lowell Thomas Communications Center"),
(Now(), Now(), "Marian Hall"),
(Now(), Now(), "Marist Boathouse"),
(Now(), Now(), "McCann Recreational Center"),
(Now(), Now(), "Mid-Rise Hall"),
(Now(), Now(), "St. Ann's Hermitage"),
(Now(), Now(), "St. Peter's"),
(Now(), Now(), "Sheahan Hall"),
(Now(), Now(), "Steel Plant Art Studios and Gallery"),
(Now(), Now(), "Student Center/Rotunda"),
(Now(), Now(), "Tennis Pavilion"),
(Now(), Now(), "Tenney Stadium"),
(Now(), Now(), "Lower Townhouses"),
(Now(), Now(), "Lower West Cedar Townhouses"),
(Now(), Now(), "Upper West Cedar Townhouses");


# prints out users and locations
SELECT * FROM users;
SELECT * FROM stuff;
SELECT * FROM locations;









