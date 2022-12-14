# Crypto API

This project makes the current price query or for a certain date of a cryptocurrency. Accepted cryptocurrencies are `bitcoin`, `ethereum`, `dacxi` and `cosmos`.

To query cryptocurrency data, the [`codenix-sv/coingecko-api`](https://github.com/codenix-sv/coingecko-api) library is used where it consults the [Coingecko](https://www.coingecko.com/en/api). `Adapter` pattern was chosen to decouple the dependency from the library, also the `Repository` pattern for eloquent abstraction and `Action` pattern to separate each programming logic into a single class. An `Enum` was also created, it stores all cryptocurrencies available from the API, the enum was created to remove a table from the db, thus reducing queries. `Requests` were also created for endpoint input rules and `Resources` to format endpoint responses. To save the prices of cryptocurrencies over time, a `Command` was made (`cryptocurrency:set-coin-current-price`), it saves the current price of coins, this command was added to the `Schedule` to be executed every one minute, this ensures that all coin prices will be saved in the database for future use. The structure of the project looks like this:

```
app
├── Action
│   └── Cryptocurrency
│       ├── CoinCurrentPriceAction.php
│       ├── CoinPriceFromDateTimeAction.php
│       └── CoinSetPriceAction.php
├── Adapter
│   └── CoinGecko
│       ├── CoinGeckoApi.php
│       └── CoinGeckoInterface.php
├── Console
│   ├── ...
│   └── Commands
│       └── Cryptocurrency
│           └── CoinSetCurrentPriceCommand.php
├── Enums
│   └── Cryptocurrency
│       └── EnumCoin.php
├── Http
│   ├── Controllers
│   │   ├── ...
│   │   └── Cryptocurrency
│   │       ├── CoinCurrentPriceController.php
│   │       ├── CoinPriceFromDateTimeController.php
│   │       └── ValidCryptocurrencyNameController.php.php
│   ├── Requests
│   │   └── Cryptocurrency
│   │       ├── CoinCurrentPriceRequest.php
│   │       └── CoinPriceFromDateTimeRequest.php
│   ├── Resources
│   │   └── Cryptocurrency
│   │       ├── CoinPriceResource.php
│   │       └── ValidCryptocurrencyNameResource.php
│   └── ...
├── Models
│   ├── Cryptocurrency
│   │   └── CoinPrice.php
│   └── ...
├── Providers
│   ├── Adapter
│   │   └── CoinGecko
│   │       └── CoinGeckoProvider.php
│   ├── Cryptocurrency
│   │   └── CoinPriceRepositoryProvider.php
│   └── ...
├── Repository
│   └── Cryptocurrency
│       ├── CoinPriceRepositoryEloquent.php
│       └── CoinPriceRepositoryInterface.php
├── ...
database
├── factories
│   ├── Cryptocurrency
│   │   └── CoinPriceFactory.php
│   └── ...
├── migrations
│   ├── 2022_11_24_001124_create_coin_prices_table.php
│   └── ...
└── ...
```

## 💻 Requirements

 - Docker
 - Docker Compose

## ⚙️ Setup

1. Clone this project:
```
git clone https://github.com/JoaoFVictor/crypto.git
```

2. Enter the project folder:
```
cd crypto
```

3. Copy the .env.example to .env:
```
cp .env.example .env
```

4. Start application containers:
```
docker-compose up -d
```

5. Run composer install in the application container:
```
docker exec app composer install
```

6. Generate the application key:
```
docker exec app php artisan key:generate
```

7. Run db migrations:
```
docker exec app php artisan migrate
```

## 🛳️ Containers
For the full operation of the project, four containers were created: the app, nginx, postgres and redis
- app: It is the container with responsible for running the application using php 8.1 and laravel 9
- nginx: It is the container with the nginx image responsible for the http server
- postgres: It is the container with the postgres database image
- redis: It is the container with the redis image responsible for the cache for the project

## ✨ Endpoints
The project has three endpoints, one to obtain the current price of a cryptocurrency, other to obtain the price of a cryptocurrency on a given date and finally one to inform which cryptocurrencies are valid in queries.
- cryptocurrency/price/current: This endpoint is responsible for querying the current price of a cryptocurrency. Has as query string the `coin`
- cryptocurrency/price/history: This endpoint is responsible for querying the price of a cryptocurrency on a given date. It has as query string the `coin` and `date`
- cryptocurrency/names: This endpoint is responsible for informing which cryptocurrencies are accepted as a parameter in the other endpoints`

## 🚀 Running

1. Start application containers:
```
docker-compose up -d
```

2. Execute the schedules:
```
docker exec app php artisan schedule:work
```

## 📄 Tests
To run the unit and feature tests run:
```
docker exec app php artisan test
```
