<?php

use Zend\Diactoros\Response;

class CSVResponse extends Response
{
    public function __construct(array $data, $status = 200, array $headers = [])
    {
        $output = fopen("php://temp",'w');
		$headers["Content-Type"] = "text/csv";
		$headers["Content-Disposition"] = "attachment;filename=data.csv";
     
        //fputcsv($output, array('id','name','description')); write column header line
        foreach($data as $item) 
          fputcsv($output, (array)$item);

        rewind($output);

        $body = (new Zend\Diactoros\StreamFactory())->createStreamFromResource($output);

        parent::__construct($body, $status, $headers);
    }
}

?>
