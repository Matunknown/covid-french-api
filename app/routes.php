<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

// Read CSV File
function read($csv)
{
    $file = fopen($csv, 'r');
    while (!feof($file)) {
        $line[] = fgetcsv($file, 1024);
    }
    fclose($file);
    $line[count($line) - 1] = '0';
    return $line;
}

return function (App $app) {
    $app->get('/{date}', function (Request $request, Response $response, $args) {
        $date = $args['date'];

        // Read data file
        $csv = 'data.csv';
        $csv = read($csv);

        // Short by date
        $data_by_date = array();
        foreach ($csv as $value) {
            if (preg_match('/' . $date . '/i', $value[0])) {
                array_push($data_by_date, $value[0]);
            }
        }

        // Split data and format to JSON
        $data_splited = array();
        foreach ($data_by_date as $value) {
            $value = explode(';', $value);

            // Get department name
            $department_code = $value[0];
            $url = 'https://geo.api.gouv.fr/departements/' . $department_code;
            $res = file_get_contents($url);
            $json_array_department = json_decode($res, true);

            $data = array('department' => $value[0], 'departmentName' => $json_array_department['nom'], 'date' => $value[1], 'hospitalization' => $value[2], 'intensiveCare' => $value[3], 'death' => $value[4], 'backAtHome' => $value[5]);
            array_push($data_splited, $data);
        }

        // Response
        $payload = json_encode($data_splited);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

    $app->get('/{date}/{code}', function (Request $request, Response $response, $args) {
        $date = $args['date'];
        $code = $args['code'];

        // Read data file
        $csv = 'data.csv';
        $csv = read($csv);

        // Short by date
        $data_by_date = array();
        foreach ($csv as $value) {
            if (preg_match('/' . $date . '/i', $value[0])) {
                array_push($data_by_date, $value[0]);
            }
        }

        // Short by department
        $data_by_department = '';
        foreach ($data_by_date as $value) {
            if (preg_match('/^' . $code . '/i', $value)) {
                $data_by_department = $value;
            }
        }

        // Split data
        $data_by_department = explode(';', $data_by_department);

        // Response with an error if value is null
        if ($data_by_department[0] == "") {
            $response->getBody()->write(json_encode('Error data is null.'));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }

        // Get department name
        $department_code = $data_by_department[0];
        $url = 'https://geo.api.gouv.fr/departements/' . $department_code;
        $res = file_get_contents($url);
        $json_array_department = json_decode($res, true);

        // Response
        $data = array('department' => $data_by_department[0], 'departmentName' => $json_array_department['nom'], 'date' => $data_by_department[1], 'hospitalization' => $data_by_department[2], 'intensiveCare' => $data_by_department[3], 'death' => $data_by_department[4], 'backAtHome' => $data_by_department[5]);
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });
};
