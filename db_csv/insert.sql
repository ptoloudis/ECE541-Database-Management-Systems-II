LOAD DATA LOCAL INFILE '/var/lib/mysql-files/books.csv'
INTO TABLE Books
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(title, author, description, isbn, genres, publisher, quantity);

LOAD DATA LOCAL INFILE '/var/lib/mysql-files/user.csv'
INTO TABLE User
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(name, surname, email, password, admin);

LOAD DATA LOCAL INFILE '/var/lib/mysql-files/booking.csv'
INTO TABLE Booking
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(user_id, book_id, booked_date, expiration_date, date_returned);