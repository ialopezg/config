<?php

require '../vendor/autoload.php';

use ialopezg\Libraries\Config\Config;

$config = Config::load('database.php');
$connections = $config->get('database.connections');
$default_connection = $config->get('database.active_connection'); ?>
    <h2>Connections Count: <?= count($connections); ?></h2>
<?php foreach ($connections as $key => $connection) { ?>
    <h1>Reading values</h1>
    <hr>
    <h3>Connection Name: <?= $default_connection ?></h3>
    <table style="width: 300px;">
        <tr style="text-align: left;">
            <th style="background-color: lightgray;">Name</th>
            <th>Value</th>
        </tr>
        <?php foreach ($connection as $key => $value) { ?>
            <tr style="border; 1px solid #000;">
                <td style="background-color: lightgray;"><?= $key ?></td>
                <td><?= $value ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <h1>Writing values</h1>
    <hr>
    <br>
<?php }

// Get the value
echo "Current value: {$config->get('database.connections.default.db_driver')}<br>";
// Change the value
$config->set('database.connections.default.db_driver', 'MySQL');
// Print new value
echo "New value: {$config->get('database.connections.default.db_driver')}<br>";

// Write the file
$config->toFile('database.php');