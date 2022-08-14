<?php 
require_once '../../lib.php';

const pfa_cols_editable = [
    "pname" => [
        "label"       => "Park Name",
        "type"        => "text",
        "constraints" => 'maxlength="128"'
    ],
    "code" => [
        "label"       => "Fauna Code",
        "type"        => "text",
        "constraints" => 'maxlength="10"'
    ]
];

function get_entries() : array
{
    $query = <<< 'PF_FETCH'
    SELECT eecs647.parks.pname, 
           eecs647.fauna.name,
           eecs647.parkfauna.id, 
           eecs647.parkfauna.code 
    FROM eecs647.parkfauna
    LEFT JOIN eecs647.parks ON eecs647.parks.id = eecs647.parkfauna.id
    LEFT JOIN eecs647.fauna ON eecs647.fauna.code = eecs647.parkfauna.code
    GROUP BY eecs647.parks.pname, eecs647.fauna.name, eecs647.parkfauna.id, eecs647.parkfauna.code
    ORDER BY parkfauna.id DESC
    PF_FETCH;

    $conn = new PDO('odbc:eecs647');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $entries;
}
\eecs647\print_html_opener('Edit Images');

// Show the form to edit parks
print '<section id="pf">';
print '<h1>Editing Park-Fauna Relationships</h1>';