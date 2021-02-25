# Covid French API

This an API to get daily data about Covid in France. This is a personal training project.

Using data from [data.gouv.fr](https://www.data.gouv.fr/fr/) and [geo.api.gouv.fr](https://geo.api.gouv.fr/).

### Build With
- Slim Framework

## Installation

Use [composer](https://getcomposer.org/) to install the project.

```bash
composer install
```

## Run

```bash
sh start.sh
```
Running on: http://localhost:8888/

## Usage

Get data by date:

`GET /{date}`

    GET http://localhost:8888/2021-02-10

    [{
        "department":"01",
        "departmentName":"Ain",
        "date":"2021-02-10",
        "hospitalization":"13",
        "intensiveCare":"4",
        "death":"2",
        "backAtHome":"30"
    },
    ...]

Get data by date and department:

`GET /{date}/{code}`

    GET http://localhost:8888/2021-02-10/75

    {
    "department":"75",
    "departmentName":"Paris",
    "date":"2021-02-10",
    "hospitalization":"60",
    "intensiveCare":"27",
    "death":"7",
    "backAtHome":"65"
    }

#### Parameter:
Name | Description | Type | Example
-|-|-|-
date | Date | string | 2021-01-30
code | Department code | integer | 75

#### Response:
Name | Description | Type
-|-|-
department | Department code | integer
departmentName | Department name | string
date | Date of notice | string
hospitalization | Daily number of newly hospitalized persons | integer
intensiveCare | Daily number of new intensive care admission | integer
death | Daily number of newly deceased persons | integer
backAtHome | Daily number of new home returns | integer

## License
Distributed under the MIT License.
