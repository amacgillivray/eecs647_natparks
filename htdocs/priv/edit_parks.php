<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

const parkcols_editable = [
    "pname" => [
        "label"       => "Name",
        "type"        => "text",
        "constraints" => 'maxlength="128"'
    ],
    "pdesc" => [ 
        "label"       => "Description",
        "type"        => "textarea",
        "constraints" => 'rows="20" cols="85"'
    ],
    "pstat" => [
        "label"       => "2-Digit State Code",
        "type"        => "text",
        "constraints" => 'maxlength="2"'
    ],
    "fnded" => [
        "label"       => "Date Founded",
        "type"        => "date",
        "constraints" => 'min="1776-07-04"'
    ], 
    "sqrmi" => [
        "label"       => "Area (Square Miles)",
        "type"        => "number",
        "constraints" => 'min="0" max="99999.99" step="0.01"'
    ],
    "vsytd" => [
        "label"       => "Visitors, Year-To-Date",
        "type"        => "number",
        "constraints" => 'min="0" max="9999999" step="1"'
    ],
    "vslfy" => [
        "label"       => "Visitors, Last Fiscal Year",
        "type"        => "number",
        "constraints" => 'min="0" max="9999999" step="1"'
    ],
    "t_prc" => [
        "label"       => "Ticket Price (USD)",
        "type"        => "number",
        "constraints" => 'min="0" max="999.99" step=".01"'
    ],
    "alch"  => [ 
        "label"       => "Allows Alcohol",
        "type"        => "checkbox",
        "constraints" => ''
    ],
    "camp"  => [
        "label"       => "Allows Camping",
        "type"        => "checkbox",
        "constraints" => ''
    ],
    "guns"  => [
        "label"       => "Allows Firearms",
        "type"        => "checkbox",
        "constraints" => ''
    ]
];

try {
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    throw $e;
}
$stmt = $conn->prepare("select * from eecs647.parks;");
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
\eecs647\print_html_opener('Edit Parks');

// Show the form to edit parks
print '<section id="parks">';
print '<form>';
foreach ( $rows as $park )
{
    \eecs647\print_form_values_for_table(
        parkcols_editable,
        $park,
        "id".$park["id"],
        '<fieldset class="park"><legend>Park ID#'.$park["id"].'</legend>',
        '</fieldset>'
    );
}
print '</form>';

\eecs647\print_html_closer();