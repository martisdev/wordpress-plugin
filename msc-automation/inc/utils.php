<?php
    interface message_type{
        const SUCCES = 'succes';
        const INFO = 'info';
        const WARNING = 'warning';
        const DANGER = 'danger';
    }
    
    function show_msc_message($message, $type){
        ?>
        <script>
            console.log("<?php echo $type.': '.$message ?>");
        </script>       
        <?php
    }

    function test_date($date){        
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        } else {            
            return false;
            
        }
    }
    
    function getImage($img_data,$NameToSave,$widthMax) {        
        if (empty($img_data)) {
            return false;            
        }
        try {            
            $src = imagecreatefromstring($img_data);
            $size = $widthMax;  // new image width
            $width = imagesx($src);
            $height = imagesy($src);
            $aspect_ratio = $height/$width;

            if ($width <= $size) {
                $new_w = $width;
                $new_h = $height;
            } else {
                $new_w = $size;
                $new_h = abs($new_w * $aspect_ratio);
            }            
            $img = imagecreatetruecolor($new_w,$new_h);
            imagecopyresized($img,$src,0,0,0,0,$new_w,$new_h,$width,$height);
            imagedestroy($src);

            imagejpeg($img, $NameToSave, 100);        
            imagedestroy($img);                        
            return true;            
        } catch (Exception $exc) {                  
            return false;
        }
    }
    
    function tag_cloud($listTags,$urlDesti,$div_size = 400) {
        
        $ret = '<div style=\"width:'.$div_size.'px\">';;
        /* Initialize some variables */
        $fmax = 25; /* Maximum font size */
        $fmin = 10; /* Minimum font size */        
        $tmin = 1;//min($listTags[1]['TAG_SUM']); /* Frequency lower-bound */                
        $tmax = $listTags[0]['TAG_SUM'] ; //max($listTags[0]['TAG_SUM']); /* Frequency upper-bound */        
        
        $rows = (count($listTags,1)/count($listTags,0))-1;$counter = 0;
        while($counter < $rows):            
            $size = $listTags[$counter]['TAG_SUM'];
            if ($size > $tmin){
                $font_size = floor(  ( $fmax * ($size - $tmin) ) / ( $tmax - $tmin )  );
            }else{
                $font_size = $fmin;
            }                       
            /* Define a color index based on the frequency of the word */
            $r = $g = 0; $b = floor( 255 * ($size / $tmax) );
            $color = '#' . sprintf('%02s', dechex($r)) . sprintf('%02s', dechex($g)) . sprintf('%02s', dechex($b));        
            $Url = $urlDesti."?tagid=".$listTags[$counter]['ID'];
            $name = $listTags[$counter]['TAG_NAME'];
            $ret.='<a style="font-size: '.$font_size.'px; color:'.$color.';" href="'.$Url.'" >'.$name.'</a>'."\n";            
            $counter = $counter + 1;
        endwhile;
        $ret.= "</div>";
        
        return $ret;
    }
   
   