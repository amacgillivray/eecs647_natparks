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

\eecs647\print_html_opener('Edit Images');

// Show the form to edit parks
print '<section id="pf">';
print '<h1>Editing Park-Fauna Relationships</h1>';