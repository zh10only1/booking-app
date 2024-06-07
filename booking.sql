CREATE DATABASE IF NOT EXISTS bookingDB;
USE bookingDB;

CREATE TABLE IF NOT EXISTS bookings (
    DepartureDate VARCHAR(20),
    BookingNumber BIGINT PRIMARY KEY,
    TO_Name VARCHAR(255),
    FlightNumber VARCHAR(255),
    FlightDepTime TIME,
    PickUpTime TIME,
    PickupDate VARCHAR(20),
    Hotel VARCHAR(255),
    PickupPoint VARCHAR(255),
    ServiceType VARCHAR(255),
    FlyFrom VARCHAR(10),
    FlyTo VARCHAR(10),
    Lang varchar(50)
    );