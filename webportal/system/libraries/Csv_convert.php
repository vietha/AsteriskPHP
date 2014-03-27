<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Csv_convert{
    
    public function array_to_csv($array, $titles = null, $download = "")
    {
        if ($download != "")
        {    
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . '"');
        }        
 
        ob_start();
        $f = fopen('php://output', 'w') or show_error("Can't open php://output");
        $n = 0;
        if($titles!= null)
            fputcsv($f, $titles);
        foreach ($array as $line)
        {
            
            $n++;
            if ( ! fputcsv($f, $line))
            {   
                show_error("Can't write line $n: $line");
            }
        }
        fclose($f) or show_error("Can't close php://output");
        $str = ob_get_contents();
        ob_end_clean();
 
        if ($download == "")
        {
            return $str;    
        }
        else
        {    
            echo $str;
        }        
    }
}
?>
