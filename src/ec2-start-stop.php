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

$action = 'START';

foreach ($instances as $instance) {

    // instance id must be array
    $instanceIds = [$instance['InstanceId']];

    if ($action == 'START') {
        $result = $ec2Client->startInstances(array(
            'InstanceIds' => $instanceIds,
        ));
    } else {
        $result = $ec2Client->stopInstances(array(
            'InstanceIds' => $instanceIds,
        ));
    }
    Dumper::dump($result);
}



