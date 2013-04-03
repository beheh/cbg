<?php
header('Content-Type: application/json');
$request = json_decode(file_get_contents('php://input'), true);

define('CHUNKSIZE', pow(8, 2));

if(is_array($request)) {
    $result = array();
    $chunks = array();
    
    foreach($request as $chunk) {
        srand($chunk['x']*$chunk['y']);
        $data = array();
        for($i = 0; $i < CHUNKSIZE; $i++) {
            $tile = array();
            $tile[0] = rand(0, 2);
            $data[] = $tile;
        }

        $chunks[] = array('x' => $chunk['x'], 'y' => $chunk['y'], 'data' => $data);
    }
    $result['chunks'] = $chunks;
} else {
    $result['error'] = 'Invalid request';
}
$result['debug'] = print_r($request, true);

echo json_encode($result);
?>