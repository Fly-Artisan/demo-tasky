-- Write your models here

CREATE TABLE Task (
    id        INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(25) NOT NULL UNIQUE,
    status    ENUM('completed','pending') DEFAULT 'pending',
    dateAdded TIMESTAMP
);
