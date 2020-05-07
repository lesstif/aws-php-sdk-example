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

//dump($result);

/**
 * Enable and Disable Monitoring
 */
$instances = $result->get('Reservations')[0]['Instances'] ?? [];

foreach ($instances as $instance) {

    // instance id must be array
    $instanceIds = [$instance['InstanceId']];

    $monitorInstance = 'ON';

    if ($monitorInstance == 'ON') {
        $result = $ec2Client->monitorInstances([
            'InstanceIds' => $instanceIds
        ]);
        dump(['monitorInstances' => $result]);
    } else {
        $result = $ec2Client->unmonitorInstances([
            'InstanceIds' => $instanceIds
        ]);
        dump(['unmonitorInstances' => $result]);
    }
}

