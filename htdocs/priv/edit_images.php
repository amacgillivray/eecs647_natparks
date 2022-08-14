<?php
require_once(dirname(__FILE__, 3) . "/lib.php");

const auths = [
    'ADMIN',
    'ZOO',
    'RGR'
];

const cols_editable = [
    "fpath" => [
        "label"       => "Name",
        "type"        => "text",
        "constraints" => 'maxlength="128 readonly"'
    ],
    "author" => [ 
        "label"       => "Author",
        "type"        => "text",
        "constraints" => 'maxlength="128"'
    ],
    "license" => [
        "label"       => "License",
        "type"        => "text",
        "constraints" => 'maxlength="128"'
    ],
    "dattkn" => [
        "label"       => "Date Taken",
        "type"        => "date",
        "constraints" => ''
    ], 
    "natw" => [
        "label"       => "Image Width (Pixels)",
        "type"        => "number",
        "constraints" => 'min="0" max="9999" step="1"'
    ],
    "nath" => [
        "label"       => "Image Height (Pixels)",
        "type"        => "number",
        "constraints" => 'min="0" max="9999" step="1"'
    ],
    "lfauna" => [
        "label"       => "Related Fauna",
        "type"        => "text",
        "constraints" => 'maxlength="10"'
    ],
    "lpark" => [
        "label"       => "Related Park",
        "type"        => "text",
        "constraints" => 'maxlength="10"'
    ],
    "mfr"  => [ 
        "label"       => "Camera Manufacturer",
        "type"        => "text",
        "constraints" => 'maxlength="128"'
    ],
    "mod"  => [
        "label"       => "Camera Model",
        "type"        => "text",
        "constraints" => 'maxlength="64"'
    ],
    "exposure"  => [
        "label"       => "Exposure",
        "type"        => "text",
        "constraints" => 'maxlength="16"'
    ],
    "fnum"  => [ 
        "label"       => "F Number",
        "type"        => "text",
        "constraints" => 'maxlength="16"'
    ],
    "iso"  => [
        "label"       => "ISO Value",
        "type"        => "text",
        "constraints" => 'maxlength="16"'
    ],
    "foclen"  => [
        "label"       => "Focal Length",
        "type"        => "text",
        "constraints" => 'maxlength="16"'
    ],
    "idesc" => [ 
        "label"       => "Description",
        "type"        => "textarea",
        "constraints" => 'rows="20" cols="85"'
    ]
];

function print_form_values_for_img_table(
    array $cols,
    array $row,
    string $row_id = "",
    string $wrap_open = "",
    string $wrap_close = ""
) : void {
    print $wrap_open;

    print '<img src="/_img/' . $row['fpath'] .'">';

    foreach ( $cols as $fieldname => $fieldmeta )
    {
        print '<div class="fl-pair fl-pair-' .$fieldmeta["type"] .'">';
        $tag = $row_id . "_" . $fieldname;
        $value = $row[$fieldname];
        
        $label = '<label for="'.$tag.'">'.$fieldmeta["label"].'</label>';
        $idnty = 'id="'.$tag.'" name="'.$tag.'"';
        $constraints = $fieldmeta["constraints"];

        if ( $fieldmeta["type"] === "checkbox" )
        {
            $comp = '1';
            print '<input '.$idnty.' type="checkbox" ' . $fieldmeta["constraints"];
            print ($value == $comp) ? ' checked>' : '' . '>';
            print $label;
        } else if ( $fieldmeta["type"] === "textarea" ) {
            print $label;
            print '<textarea '.$idnty.' '.$constraints.'>';
            print $value;
            print '</textarea>';
        } else {
            print $label;
            print '<input '.$idnty.
                  ' type="'.$fieldmeta["type"].'"'.
                  ' value="'.$value.'"'.
                  ' '.$constraints.'>';
        }
        print '</div>';
    }
    print $wrap_close;
    return;   
}


try {
    $conn = new PDO( 'odbc:eecs647' );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    throw $e;
}
$stmt = $conn->prepare("select * from eecs647.image;");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
\eecs647\print_html_opener('Edit Images');

// Show the form to edit parks
print '<section id="imgs">';
print '<h1>Editing Images</h1>';
print '<p>Note that, for security reasons, new images cannot be uploaded with this page.</p>';

if (!\eecs647\authorized_user(auths))
{
    \eecs647\print_err_privs();
    print '</section>';
    \eecs647\print_html_closer();
    exit();
}

print '<form>';
foreach ( $rows as $img )
{
    print_form_values_for_img_table(
        cols_editable,
        $img,
        "id".$img['fpath'],
        '<fieldset class="park"><legend>Image: '.$img["fpath"].'</legend>',
        '</fieldset>'
    );
}
print '</form>';
print '</section>';

\eecs647\print_html_closer();


// print_html_opener('Edit Images');
// print_html_closer();