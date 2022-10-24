# Collect data from multi providers
___
## Description
We have two providers that collect data from them in json files. We need to read them then
insert them into the relation database schema and make some filter operations on them to get
the result.

#### Jsons files
1. users.json
2. transections.json

#### we have three status for `transections`
* authorized which will have `statusCode` `1`
* decline which will have `statusCode` `2`
* refunded which will have `statusCode` `3`

#### Acceptance Criteria
**Implement this APIs should contain :**
* list all users which combine transactions from all the available provider and database
* should be able to filter the result three statusCode (authorized, decline, refunded)
* should be able to filter the result three Currency
* it should be able to filter by amount range
* it should be able to filter by date range

---
## Installation

1. Clone the repo
```sh
git clone https://github.com/mu7mdmagdy/collect-data-from-multi-providers-task.git
```
2. Install Composer packages
```sh
composer install
```
3. Copy `.env.example` to `.env` and set your database credentials
4. Generate an app encryption key
```sh
php artisan key:generate
```
5. Generate an api encryption key
```sh
php artisan apikey:generate --show
```
6. Migrate the database
```sh
php artisan migrate
```
7. Put json files in `//storage/provider` folder
   1. users.json
   2. transections.json

8. Run the command to insert the data from json files to database
```sh
php artisan insert:provider-data
```
other way : this command line is run in background daily with skip the exists data.

9. Run the server
```sh
php artisan serve
```
___

## Code Architecture 
**ONION Architecture**

is used to make the code more readable and maintainable. 

App folder located in `//app` directory with name `PaymentApp`
* **PaymentApp** folder contains one folder for each module in the app.

**BASE**
* `Repository` : is used to implement the main methods of the repository.
* `Service` : is used to implement the main methods of the service.
* `ResponseBuilder` : is used to build the response of the API in One & Same Structure.

**MODULES**

Every module has its own folder and the folder contains the following files:
* `Model` : is used to define the database schema.
* `Request` : is used to validate the request data.
* `Service` : is used to make the business logic of the data.
* `Repository` : is used to make the data layer.
* `DTO` : is used to transfer the data between the layers.
* `Mapper` : is used to map the data between the layers.
* `context` : is used to store static data and make the code more maintainable.

___
## Usage

#### API Documentation
* [Postman Collection](https://www.getpostman.com/collections/9fec3aa6ff4a738c9c9a)
* [Postman Documentation](https://documenter.getpostman.com/view/2887394/2s84LPuqX6)

#### API Endpoints

#### GET `/api/users`
* **Authorization**
   * `x-api-key` : `your api key` _generated in installation step 5_
* **Headers**
   * `Accept` : `application/json`
   * `Content-Type` : `application/json`
   * `Connection` : `keep-alive`


   * **Query Parameters**
       * `status` : (`string` | `array`).
       * `currency` : (`string` | `array`).
       * `minAmount` : (`integer` | min:`0`) , min amount of transaction.
       * `maxAmount` : (`integer` | min:`0` | >`minAmount`) , max amount of transaction.
       * `dateFrom` : (`date` | timezone:`UTC` | format:`Iso8601`) , min date of transaction.
       * `dateTo` : (`date` | timezone:`UTC` | format:`Iso8601` | >`dateFrom`) , max date of transaction.
       * `pageSize` : (`integer` | min:`1`) , number of items per page.
       * `pageNo` : (`integer` | min:`1`) , page number.
   * **Response**
      * `data` : `array`
      * `errors` : `array`
      * `message` : `string`
      * `status_code` : `integer`
      
