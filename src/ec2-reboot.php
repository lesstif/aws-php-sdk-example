<?php

use Lesstif\AwsSdkExample\Dumper;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$key = getenv('AWS_KEY');
$secret = getenv('AWS_SECRET');

$credentials = new Aws\Credentials\Credentials($key, $secret);

$ec2Client = new Aws\Ec2\Ec2Client([
    'region' => 'ap-northeast-2',
    'version' => 'latest',
    'credentials' => $credentials
]);

/**
 * Describe Instances
 */
$result = $ec2Client->describeInstances();

/**
 * Start/Stop instance
 */
$instances = $result->get('Reservations')[0]['Instances'] ?? [];

foreach ($instances as $instance) {

    // instance id must be array
    $instanceIds = [$instance['InstanceId']];

    $result = $ec2Client->rebootInstances([
        'InstanceIds' => $instanceIds
    ]);

    Dumper::dump($result);
}



