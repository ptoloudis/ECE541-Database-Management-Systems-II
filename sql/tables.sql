CREATE TABLE Books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    author VARCHAR(255),
    description TEXT,
    isbn VARCHAR(13),
    genres VARCHAR(255),
    publisher VARCHAR(255),
    quantity INT
);

CREATE TABLE User (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    admin BOOLEAN NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Booking (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    booked_date DATE NOT NULL,
    expiration_date DATE NOT NULL,
    date_returned DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (book_id) REFERENCES Books(id)
);

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