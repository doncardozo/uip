<?php
error_reporting(0);

try {

    system("clear");
    
    echo "Begin process.\n";
    
    echo "Get ip...\n";
    
    system("curl ifconfig.me > /var/www/uip/data", $output);
            
    if($output === false)
        throw new Exception("Error: Cannot write file.\n");
        
    echo "Ok.\n";
    
	$sdat = json_decode(file_get_contents("sdat"));

    echo "Connect to ftp server... ";
    
    if ( !($conn_id = ftp_connect($sdat->server)) )
            throw new Exception("Error: cannot connect to ftp server ({$sdat->server}).\n");
    
    if ( !(ftp_login($conn_id, $sdat->user, $sdat->pass)) )
            throw new Exception("Error: cannot login to ftp server ({$sdat->server}).\n");
    
    echo "Ok.\n";
    
    ftp_chdir($conn_id, 'sf2/web'); 
    
    echo "Upload file... ";
    
    if( !ftp_put($conn_id,'data', 'data', FTP_BINARY ) )
            throw new Exception("Error: cannot upload file into ftp server ({$sdat->server}).\n");

    ftp_close($conn_id);   
    
    echo "Ok.\n";
    
    echo "Upload file is successfuly!\n";
    
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}
